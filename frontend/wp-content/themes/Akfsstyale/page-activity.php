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
        <ul class="menu2">
            <li><a href="<?php echo home_url(); ?>" class="<?php if (is_home()) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/beranda-merah.png' ?>" alt="icon"> Home</a></li>
            <li><a href="<?php echo get_permalink(get_page_by_path('about-us')); ?>" class="<?php if (is_page('about-us')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/about-us-merah.png' ?>" alt="icon"> About Us</a></li>
            <li><a href="<?php echo get_permalink(get_page_by_path('units-affiliates')); ?>" class="<?php if (is_page('units-affiliates')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/units-merah.png' ?>" alt="icon"> Units & Affiliates</a></li>
            <li><a href="<?php echo get_permalink(get_page_by_path('activity')); ?>" class="<?php if (is_page('activity')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/activity.png' ?>" alt="icon"> Activity</a></li>
            <li><a href="<?php echo get_permalink(get_page_by_path('contact')); ?>" class="<?php if (is_page('contact')) echo 'active'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/icons/cs-merah.png' ?>" alt="icon"> Contact</a></li>
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
            $(".menu2").toggleClass("active");
        });
    });
</script>

<main>
    <div class="mt-4"></div>
    <div><?php the_content(); ?></div>
</main>

<?php get_footer(); ?>