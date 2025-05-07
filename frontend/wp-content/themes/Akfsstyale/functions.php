<?php
function theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus([
        'main-menu' => __('Menu Utama', 'theme-text-domain')
    ]);
}
add_action('after_setup_theme', 'theme_setup');

function load_fontawesome()
{
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'load_fontawesome');

function enqueue_bootstrap()
{
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap');

// function my_theme_add_admin_menu() {
//     add_menu_page(
//         'Theme Settings', // Nama Halaman
//         'Theme Settings', // Nama di Menu
//         'manage_options', // Hak Akses
//         'theme-settings', // Slug Menu
//         'my_theme_settings_page', // Fungsi yang Menampilkan Halaman
//         'dashicons-admin-generic', // Ikon Menu
//         60 // Posisi Menu
//     );
// }
// add_action('admin_menu', 'my_theme_add_admin_menu');

function my_theme_settings_page()
{
    include("includes/generalsetting.php");
}

function my_theme_admin_scripts()
{
    wp_enqueue_media();
    wp_enqueue_script('my-admin-script', get_template_directory_uri() . '/admin.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'my_theme_admin_scripts');

function my_theme_settings_init()
{
    register_setting('my_theme_settings', 'banner_dashboard', [
        'sanitize_callback' => 'maybe_serialize'
    ]);
    register_setting('my_theme_settings', 'banner_contact');

    add_settings_section('my_theme_section', 'Pengaturan Banner', null, 'theme-settings');
}
add_action('admin_init', 'my_theme_settings_init');



// Callback untuk input Banner Dashboard
function banner_dashboard_callback()
{
    $value = get_option('banner_dashboard', '');
    echo '<input type="text" id="banner_dashboard" name="banner_dashboard" value="' . esc_attr($value) . '" style="width:70%;" />';
}

// Callback untuk input Banner Contact Us
function banner_contact_callback()
{
    $value = get_option('banner_contact', '');
    echo '<input type="text" id="banner_contact" name="banner_contact" value="' . esc_attr($value) . '" style="width:70%;" />';
}

function my_theme_enqueue_admin_scripts($hook)
{
    if ($hook !== 'toplevel_page_theme-settings') return;

    wp_enqueue_media();
    wp_enqueue_script('admin-script', get_template_directory_uri() . '/js/admin.js', ['jquery'], null, true);
}
add_action('admin_enqueue_scripts', 'my_theme_enqueue_admin_scripts');

// function my_custom_menu()
// {
//     add_menu_page(
//         'General Setting',    // Judul halaman
//         'General Setting',    // Nama menu di sidebar
//         'manage_options',     // Hak akses (hanya admin)
//         'generalsetting',     // Slug halaman
//         'my_theme_settings_page' // Callback function untuk konten halaman
//     );


//     add_submenu_page(
//         'generalsetting',     // Parent slug
//         'Pengaturan Banner',  // Judul halaman
//         'Pengaturan Banner',  // Nama submenu
//         'manage_options',     // Hak akses
//         'bannersetting',      // Slug halaman submenu
//         'banner_setting_page' // Callback function untuk konten halaman
//     );

//     add_submenu_page(
//         'generalsetting',
//         'Pengaturan Beranda',
//         'Pengaturan Beranda',
//         'manage_options',
//         'berandasetting',
//         'beranda_setting_page'
//     );
// }
// add_action('admin_menu', 'my_custom_menu');

function new_menu()
{
    add_menu_page(
        'Banner Settings',         // Page title
        'Banner Setting',          // Menu title
        'manage_options',          // Capability
        'bannersetting',           // Menu slug
        'banner_setting_page',     // Function callback
        'dashicons-format-image',  // Icon URL (atau dashicon)
        21                        // Position
    );
}
add_action('admin_menu', 'new_menu');



function banner_setting_page()
{
    include("includes/bannersetting.php");
}

add_action('elementor_pro/forms/new_record', function ($record, $handler) {
    $form_name = $record->get_form_settings('form_name');
    if ('contact-form' !== $form_name) {
        return;
    }

    $raw_fields = $record->get('fields');

    $name    = $raw_fields['name']['value'];
    $email   = $raw_fields['email']['value'];
    $phone   = $raw_fields['phone']['value'];
    $message = $raw_fields['message']['value'];

    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_us';
    $wpdb->insert($table_name, array(
        'name'       => $name,
        'email'      => $email,
        'phone'      => $phone,
        'message'    => $message,
        'created_at' => current_time('mysql')
    ));
}, 10, 2);

function buat_tabel_contact_us()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_us';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      name varchar(100) NOT NULL,
      email varchar(100) NOT NULL,
      phone varchar(20) NOT NULL,
      message text NOT NULL,
      created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'buat_tabel_contact_us');

add_action('admin_menu', 'register_contact_admin_page');

function register_contact_admin_page()
{
    add_menu_page(
        'Contact Messages',
        'Contact Us',
        'manage_options',
        'contact-us',
        'show_contact_messages',
        'dashicons-email',
        20
    );
}

function show_contact_messages()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_us';
    $messages = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");

    echo '<div class="wrap">';
    echo '<h1>Pesan Contact Us</h1>';
    echo '<table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>';
    foreach ($messages as $msg) {
        echo '<tr>
                <td>' . esc_html($msg->name) . '</td>
                <td>' . esc_html($msg->email) . '</td>
                <td>' . esc_html($msg->phone) . '</td>
                <td>' . esc_html($msg->message) . '</td>
                <td>' . esc_html($msg->created_at) . '</td>
                <td>
                    <a href="?page=contact-us&delete_id=' . $msg->id . '" 
                       onclick="return confirm(\'Yakin ingin hapus pesan ini?\')" 
                       class="button button-danger">Hapus</a>
                </td>
            </tr>';
    }
    echo '</tbody></table></div>';

    // proses hapus jika ada parameter delete_id
    if (isset($_GET['delete_id'])) {
        $delete_id = intval($_GET['delete_id']);
        $wpdb->delete($table_name, array('id' => $delete_id));
        echo "<script>location.href='?page=contact-us';</script>";
    }
}

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/contact-messages', [
        'methods' => 'GET',
        'callback' => 'get_contact_messages',
        'permission_callback' => '__return_true', // Aman hanya jika WP tidak terbuka ke publik!
    ]);
});

function get_contact_messages(WP_REST_Request $request)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_us';
    $messages = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");

    return rest_ensure_response($messages);
}

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/banners', [
        'methods' => 'GET',
        'callback' => 'get_banner_settings',
        'permission_callback' => '__return_true',
    ]);
});

function get_banner_settings()
{
    $dashboard = get_option('banner_dashboard', []);
    $contact = get_option('banner_contact');

    // Jika data diserialisasi secara manual sebelumnya
    if (!is_array($dashboard)) {
        $dashboard = @unserialize($dashboard);
    }

    return [
        'banner_dashboard' => $dashboard ?: [],
        'banner_contact' => $contact ?: '',
    ];
}

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/banners', [
        'methods' => 'POST',
        'callback' => 'update_banner_settings',
        'permission_callback' => '__return_true',
    ]);
});

function update_banner_settings(WP_REST_Request $request)
{
    $banner_dashboard = $request->get_param('banner_dashboard'); // array of URLs
    $banner_contact = $request->get_param('banner_contact'); // single URL

    if (!is_array($banner_dashboard)) {
        return new WP_Error('invalid_format', 'banner_dashboard harus array', ['status' => 400]);
    }

    update_option('banner_dashboard', $banner_dashboard);
    update_option('banner_contact', $banner_contact);

    return ['success' => true, 'message' => 'Banners updated'];
}
