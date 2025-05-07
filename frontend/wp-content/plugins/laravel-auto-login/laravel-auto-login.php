<?php

/**
 * Plugin Name: Laravel Auto Login
 * Description: Memungkinkan login otomatis dari Laravel ke WordPress dan buka Elementor.
 * Version: 1.0
 * Author: Fahmi
 */

add_action('init', function () {
    if (isset($_GET['token']) && isset($_GET['post'])) {
        $token = $_GET['token'];
        $post_id = intval($_GET['post']);

        // Ganti ini dengan token rahasia sesuai Laravel kamu
        if ($token === '20250505yayasandel512131') {
            $user = get_user_by('login', 'Admin Del');

            if ($user) {
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);

                wp_redirect(admin_url("post.php?post={$post_id}&action=elementor"));
                exit;
            } else {
                echo 'User tidak ditemukan.';
                exit;
            }
        } else {
            echo 'Token tidak valid.';
            exit;
        }
    }
});
