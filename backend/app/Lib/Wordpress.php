<?php

namespace App\Lib;

use Illuminate\Support\Facades\Http;

class Wordpress
{
    function Token()
    {
        // Data login yang dikirim ke WordPress
        $response = Http::post(env('URL_WP') . '/wp-json/jwt-auth/v1/token', [
            'username' => 'Admin Del',
            'password' => 'Fahmi@1020',
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Akses token dan user info
            $token = $data['token'];
            $email = $data['user_email'];
            $name = $data['user_display_name'];

            // Simpan token ke sesi atau database jika perlu
            session(['wp_token' => $token]);

            return $token;
        } else {
            // Menangani error dari request
            return response()->json([
                'status' => 'error',
                'message' => $response->json()['message'] ?? 'Login failed'
            ], $response->status());
        }
    }
}
