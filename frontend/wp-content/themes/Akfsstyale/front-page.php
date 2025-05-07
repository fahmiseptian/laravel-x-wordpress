<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
</head>

<header>
    <nav class="navbar">
        <div class="logo">
            <a href="<?php echo home_url(); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/logo.png" alt="Logo">
            </a>
        </div>
        <!-- Hamburger Icon -->
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="menu">
            <?php if (function_exists('pll_current_language') && pll_current_language() == 'id') : ?>
                <li><a href="<?php echo get_permalink(get_page_by_path('beranda')); ?>" class="<?php if (is_page('beranda')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/beranda.png' ?>" alt="icon"> Beranda</a></li>
                <li><a href="<?php echo get_permalink(get_page_by_path('tentang-kami')); ?>" class="<?php if (is_page('tentang-kami')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/about-us.png' ?>" alt="icon"> Tentang Kami</a></li>
                <li><a href="<?php echo get_permalink(get_page_by_path('unit-afiliasi')); ?>" class="<?php if (is_page('unit-afiliasi')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/units.png' ?>" alt="icon"> Unit & Afiliasi</a></li>
                <li><a href="<?php echo get_permalink(get_page_by_path('aktivitas')); ?>" class="<?php if (is_page('aktivitas')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/activity.png' ?>" alt="icon"> Aktivitas</a></li>
                <li><a href="<?php echo get_permalink(get_page_by_path('kontak')); ?>" class="<?php if (is_page('kontak')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/cs.png' ?>" alt="icon"> Kontak</a></li>
            <?php else : ?>
                <li><a href="<?php echo home_url(); ?>" class="<?php echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/beranda.png' ?>" alt="icon"> Home</a></li>
                <li><a href="<?php echo get_permalink(get_page_by_path('about-us')); ?>" class="<?php if (is_page('about-us')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/about-us.png' ?>" alt="icon"> About Us</a></li>
                <li><a href="<?php echo get_permalink(get_page_by_path('units-affiliates')); ?>" class="<?php if (is_page('units-affiliates')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/units.png' ?>" alt="icon"> Units & Affiliates</a></li>
                <li><a href="<?php echo get_permalink(get_page_by_path('activity')); ?>" class="<?php if (is_page('activity')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/activity.png' ?>" alt="icon"> Activity</a></li>
                <li><a href="<?php echo get_permalink(get_page_by_path('contact')); ?>" class="<?php if (is_page('contact')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/cs.png' ?>" alt="icon"> Contact</a></li>
            <?php endif; ?>
            <?php
            if (function_exists('pll_the_languages')) {
                pll_the_languages(array('dropdown' => 1));
            }
            ?>

        </ul>
    </nav>
</header>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".hamburger").click(function() {
            $(".menu").toggleClass("active");
        });
    });
</script>

<div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php
        // Ambil banner_dashboard sebagai array
        $banners = get_option('banner_dashboard', []);

        // Jika data bukan array, ubah menjadi array
        if (!is_array($banners)) {
            $banners = maybe_unserialize($banners); // Pastikan array
        }

        if (!empty($banners) && is_array($banners)) {
            $active = 'active'; // Menandai slide pertama sebagai aktif
            foreach ($banners as $banner) {
                if (!empty($banner)) {
        ?>
                    <div class="carousel-item <?php echo $active; ?>">
                        <img src="<?php echo esc_url($banner); ?>" class="d-block w-100" alt="Banner">
                    </div>
        <?php
                    $active = ''; // Hanya slide pertama yang `active`
                }
            }
        } else {
            echo '<p class="text-center">Tidak ada banner tersedia.</p>';
        }
        ?>
    </div>

    <!-- Tombol Navigasi -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<main>
    <div><?php the_content(); ?></div>
</main>

<?php get_footer(); ?>