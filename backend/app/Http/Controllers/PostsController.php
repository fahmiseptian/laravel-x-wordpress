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
        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        $response_id = Http::withToken($token)->get(env('URL_WP') . '/wp-json/wp/v2/posts', [
            'status' => 'any',
            'lang' => 'id',
        ]);

        $response_en = Http::withToken($token)->get(env('URL_WP') . '/wp-json/wp/v2/posts', [
            'status' => 'any',
            'lang' => 'en',
        ]);

        $posts_id = $response_id->json();
        $posts_en = $response_en->json();

        // Tambahkan field 'lang' secara manual jika belum ada
        foreach ($posts_id as &$post) {
            $post['lang'] = 'id';
        }
        foreach ($posts_en as &$post) {
            $post['lang'] = 'en';
        }

        // Gabungkan keduanya
        $posts = array_merge($posts_id, $posts_en);

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
        $lang = $request->input('lang'); //id or en
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
                'lang' => $lang,
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

    function editView($id)
    {
        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        $response = Http::withToken($token)->get(env('URL_WP') . '/wp-json/wp/v2/posts/' . $id);

        if ($response->successful()) {
            $post = $response->json();
            return view('posts.edit', compact('post'));
        } else {
            abort(404, 'Post tidak ditemukan');
        }
    }


    public function uploadFeaturedImage($file)
    {
        $wordpress = new Wordpress();
        $wordpress->Token();
        $token = session('wp_token');

        $response = Http::withToken($token)
            ->attach(
                'file',
                file_get_contents($file),
                $file->getClientOriginalName()
            )
            ->post(env('URL_WP') . '/wp-json/wp/v2/media');

        if ($response->successful()) {
            return $response->json()['id'];
        } else {
            // Debugging jika gagal
            logger($response->body());
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
