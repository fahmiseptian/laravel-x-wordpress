<div class="wrap">
    <h1>Pengaturan Banner</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('my_theme_settings');
        do_settings_sections('theme-settings');
        ?>

        <h2>Banner Dashboard</h2>
        <div id="banner_dashboard_wrapper">
            <?php
            $banner_dashboard = get_option('banner_dashboard', []);
            if (!is_array($banner_dashboard)) {
                $banner_dashboard = unserialize($banner_dashboard);
            }
            if (!empty($banner_dashboard)) {
                foreach ($banner_dashboard as $img_url) {
                    echo '<div class="banner-item">
                            <img src="' . esc_url($img_url) . '" style="max-width:150px; height:auto; margin:5px;">
                            <button type="button" class="button remove-banner">Hapus</button>
                            <input type="hidden" name="banner_dashboard[]" value="' . esc_attr($img_url) . '">
                          </div>';
                }
            }
            ?>
        </div>
        <input type="button" class="button upload_banner_button" value="Pilih Gambar (Maks 4)" data-target="banner_dashboard"/>
        <br><br>

        <h2>Banner Contact Us</h2>
        <div id="banner_contact_wrapper">
            <?php
            $banner_contact = get_option('banner_contact');
            if ($banner_contact) {
                echo '<div class="banner-item">
                        <img src="' . esc_url($banner_contact) . '" style="max-width:300px; height:auto;">
                        <button type="button" class="button remove-banner">Hapus</button>
                        <input type="hidden" name="banner_contact" value="' . esc_attr($banner_contact) . '">
                      </div>';
            }
            ?>
        </div>
        <input type="button" class="button upload_banner_button" value="Pilih Gambar" data-target="banner_contact"/>
        <br><br>

        <?php submit_button(); ?>
    </form>
</div>
