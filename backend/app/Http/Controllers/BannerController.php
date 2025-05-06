<?php

namespace App\Http\Controllers;

use App\Lib\Wordpress;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BannerController extends BaseController
{
    public function index()
    {
        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        $response = Http::withToken($token)->get(env('URL_WP') . '/wp-json/custom/v1/banners');

        if ($response->successful()) {
            $banners = $response->json();
            return view('banners.index', compact('banners'));
        }

        return response()->json(['error' => 'Gagal ambil banner dari WordPress'], 500);
    }

    public function updateBanners(Request $request)
    {
        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        // Validate the inputs
        $request->validate([
            'banner_dashboard_url.*' => 'nullable|url',
            'banner_dashboard_file.*' => 'nullable|image|max:2048',
            'banner_contact_url' => 'nullable|url',
            'banner_contact_file' => 'nullable|image|max:2048',
        ]);

        // Ambil data lama dari WordPress
        $existing = Http::withToken($token)->get(env('URL_WP') . '/wp-json/custom/v1/banners')->json();
        $oldDashboard = $existing['banner_dashboard'] ?? [];
        $oldContact = $existing['banner_contact'] ?? null;

        // Siapkan list baru dari input URL
        $newDashboard = $request->input('banner_dashboard_url', []);

        // Validate that each URL is valid
        foreach ($newDashboard as $url) {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return back()->withErrors(['banner_dashboard_url.0' => 'One of the banner URLs is invalid.']);
            }
        }

        // Upload file dan tambahkan ke newDashboard
        if ($request->hasFile('banner_dashboard_file')) {
            foreach ($request->file('banner_dashboard_file') as $file) {
                $path = $file->store('public/banners');
                $fileUrl = Storage::url($path);

                // Hanya tambahkan jika belum ada di oldDashboard
                if (!in_array($fileUrl, $oldDashboard)) {
                    $newDashboard[] = env('APP_URL').$fileUrl;
                }
            }
        }

        // Gabungkan: old + yang baru saja (tanpa duplikat)
        $dashboardResult = array_unique(array_merge($oldDashboard, $newDashboard));

        // Contact banner
        $contactBanner = $request->input('banner_contact_url') ?: $oldContact;
        if ($request->hasFile('banner_contact_file')) {
            $path = $request->file('banner_contact_file')->store('public/banners');
            $contactBanner = Storage::url($path);
        }

        // Kirim ke WordPress
        $response = Http::withToken($token)->post(env('URL_WP') . '/wp-json/custom/v1/banners', [
            'banner_dashboard' => array_values($dashboardResult),
            'banner_contact' => $contactBanner,
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Banner berhasil diperbarui');
        }

        return back()->withErrors(['msg' => 'Gagal mengirim data ke WordPress']);
    }


    public function deleteBanner(Request $request)
    {
        $url = $request->input('url');
        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        if (!$url) {
            return response()->json(['error' => 'URL tidak ditemukan'], 400);
        }

        $existing = Http::withToken($token)->get(env('URL_WP') . '/wp-json/custom/v1/banners')->json();
        $bannerDashboard = $existing['banner_dashboard'] ?? [];

        // Filter banner yang tidak dihapus
        $updatedBanner = array_filter($bannerDashboard, function ($item) use ($url) {
            return $item !== $url;
        });

        $response = Http::withToken($token)->post(env('URL_WP') . '/wp-json/custom/v1/banners', [
            'banner_dashboard' => array_values($updatedBanner),
            'banner_contact' => $existing['banner_contact'] ?? null,
        ]);

        if ($response->successful()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Gagal menghapus banner'], 500);
    }
}
