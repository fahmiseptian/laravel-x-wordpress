<?php

namespace App\Http\Controllers;

use App\Lib\Wordpress;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class PostsController extends BaseController
{
    function index()
    {
        // Test Login
        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        $response = Http::withToken($token)
            ->get(env('URL_WP') . '/wp-json/wp/v2/posts', [
                'status' => 'any'
            ]);

        $posts = $response->json();
        return view('posts.index', compact('posts'));
    }

    function addView()
    {
        return view('posts.add');
    }

    public function addPost(Request $request)
    {
        $title = $request->input('title');
        $content = $request->input('content');
        $status = $request->input('status');
        $featured_image = $request->file('featured_image');

        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        // Jika ada gambar, upload terlebih dahulu dan ambil ID-nya
        $featured_image_id = null;
        if ($featured_image) {
            $featured_image_id = $this->uploadFeaturedImage($featured_image);
        }

        // Kirim permintaan POST untuk menambahkan post baru
        $response = Http::withToken($token)
            ->post(env('URL_WP') . '/wp-json/wp/v2/posts', [
                'title' => $title,
                'content' => $content,
                'status' => $status,
                'featured_media' => $featured_image_id,
            ]);

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Post berhasil ditambahkan'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan post'
            ], $response->status());
        }
    }

    public function uploadFeaturedImage($file)
    {
        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        // Mengirim permintaan POST untuk mengupload gambar ke WordPress
        $response = Http::withToken($token)
            ->attach('file', file_get_contents($file), 'featured_image.jpg') // pastikan file diattach dengan benar
            ->post(env('URL_WP') . '/wp-json/wp/v2/media', [
                'headers' => [
                    'Content-Type' => 'image/jpeg'
                ]
            ]);

        if ($response->successful()) {
            return $response->json()['id'];
        } else {
            return null;
        }
    }


    public function updatePost(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $content = $request->input('content');

        // Test Login
        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        // Kirim permintaan PUT untuk memperbarui post
        $response = Http::withToken($token)
            ->put(env('URL_WP') . "/wp-json/wp/v2/posts/{$id}", [
                'title' => $title,
                'content' => $content
            ]);

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Post berhasil diperbarui'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui post'
            ], $response->status());
        }
    }

    public function updatePostStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        // Test Login
        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        // Kirim permintaan PUT untuk memperbarui status post
        $response = Http::withToken($token)
            ->put(env('URL_WP') . "/wp-json/wp/v2/posts/{$id}", [
                'status' => $status
            ]);

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Status post berhasil diperbarui'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui status post'
            ], $response->status());
        }
    }

    public function deletePost($id)
    {
        // Test Login
        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        $response = Http::withToken($token)
            ->delete(env('URL_WP') . "/wp-json/wp/v2/posts/{$id}");

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Post berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus post'
            ], $response->status());
        }
    }
}
