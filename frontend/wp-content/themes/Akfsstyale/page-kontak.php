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
            <li><a href="<?php echo home_url(); ?>" class="<?php if (is_home()) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/beranda.png' ?>" alt="icon"> Beranda</a></li>
            <li><a href="<?php echo get_permalink(get_page_by_path('tentang-kami')); ?>" class="<?php if (is_page('tentang-kami')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/about-us.png' ?>" alt="icon"> Tentang Kami</a></li>
            <li><a href="<?php echo get_permalink(get_page_by_path('unit-afiliasi')); ?>" class="<?php if (is_page('unit-afiliasi')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/units.png' ?>" alt="icon"> Unit & Afiliasi</a></li>
            <li><a href="<?php echo get_permalink(get_page_by_path('aktivitas')); ?>" class="<?php if (is_page('aktivitas')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/activity.png' ?>" alt="icon"> Aktivitas</a></li>
            <li><a href="<?php echo get_permalink(get_page_by_path('kontak')); ?>" class="<?php if (is_page('kontak')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/cs.png' ?>" alt="icon"> Kontak</a></li>
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

<main>
    <div><?php the_content(); ?></div>
</main>

<?php get_footer(); ?>