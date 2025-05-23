<?php

namespace App\Http\Controllers;

use App\Lib\Wordpress;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class PageController extends BaseController
{
    public function index()
    {
        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        // Ambil halaman bahasa Indonesia
        $response_id = Http::withToken($token)
            ->get(env('URL_WP') . '/wp-json/wp/v2/pages', [
                'status' => 'any',
                'lang' => 'id'
            ]);

        // Ambil halaman bahasa Inggris
        $response_en = Http::withToken($token)
            ->get(env('URL_WP') . '/wp-json/wp/v2/pages', [
                'status' => 'any',
                'lang' => 'en'
            ]);

        $pages_id = $response_id->json();
        $pages_en = $response_en->json();

        // Tambahkan informasi bahasa manual
        foreach ($pages_id as &$page) {
            $page['lang'] = 'id';
        }
        foreach ($pages_en as &$page) {
            $page['lang'] = 'en';
        }

        // Gabungkan
        $pages = array_merge($pages_id, $pages_en);

        return view('page.index', compact('pages'));
    }


    public function UpdatePage(Request $request)
    {
        // Test Login
        $wordpress = new Wordpress();
        $wordpress->Token();

        $id = $request->input('id');
        $status = $request->input('status');

        // Pastikan token ada di session
        $token = session('wp_token');
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token tidak ditemukan atau telah kadaluarsa.'
            ], 401);
        }

        // Kirim permintaan PUT untuk memperbarui status halaman di WordPress
        $response = Http::withToken($token)
            ->put(env('URL_WP') . '/wp-json/wp/v2/pages/' . $id, [
                'status' => $status
            ]);

        // Periksa apakah respons sukses
        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Halaman berhasil diperbarui'
            ]);
        } else {
            // Jika gagal, periksa dan tangani error
            $error_message = $response->json()['message'] ?? 'Gagal memperbarui halaman';

            // Jika perlu, dapat menambahkan log atau lebih detail untuk error debugging
            return response()->json([
                'status' => 'error',
                'message' => $error_message
            ], $response->status());
        }
    }

    public function DeletePage($id)
    {
        // Test Login
        $wordpress = new Wordpress();
        $wordpress->Token();

        // Pastikan token ada di session
        $token = session('wp_token');
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token tidak ditemukan atau telah kadaluarsa.'
            ], 401);
        }

        // Kirim permintaan PUT untuk memperbarui status halaman di WordPress
        $response = Http::withToken($token)
            ->delete(env('URL_WP') . '/wp-json/wp/v2/pages/' . $id, [
                'force' => true
            ]);

        // Periksa apakah respons sukses
        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Halaman berhasil delete'
            ]);
        } else {
            // Jika gagal, periksa dan tangani error
            $error_message = $response->json()['message'] ?? 'Gagal delete halaman';

            // Jika perlu, dapat menambahkan log atau lebih detail untuk error debugging
            return response()->json([
                'status' => 'error',
                'message' => $error_message
            ], $response->status());
        }
    }
}
