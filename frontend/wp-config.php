<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'akfsmyid_yayasan');


/** Database username */
define('DB_USER', 'akfsmyid_yayasan');


/** Database password */
define('DB_PASSWORD', 'akfsmyid_yayasan');


/** Database hostname */
define('DB_HOST', 'localhost');


/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');


/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '^=k$`z8C|vI]<8La;:Q1 #UQoBMQq& NrqFi;GOpd/%iI/8#2VynX&Oyd#!#;%rw');

define('SECURE_AUTH_KEY',  '6]gzouB&d#| :T@2@DLlW[2>.tL3papb>~):b: G*Dxa}#A7B(kq@~H=/bmlQ&s[');

define('LOGGED_IN_KEY',    '}by#?%xOeqa|lqrifQGIPh]DQ0)_kIYIRp ;LZwZbEt[q${M=aCgf+81d|xZG##B');

define('NONCE_KEY',        '8V3*kTP1-F_3a)cz}8`?#_ybk(j/=+*S1c@ijQ4nd-H5_2Y64(^U$rtv^.;/#8jc');

define('AUTH_SALT',        'M.m#ix^$*Vd`LYJml{MIY5hy_&e)U|f%Yupe^]&[fkVD<RmR=XTrfEU_V k_gtd,');

define('SECURE_AUTH_SALT', '>O%etEqT~QVP@M<]2.?f;aF:BYaDR{%n20?*bCe6$kJpqN59g1@QWn43ylffmI#$');

define('LOGGED_IN_SALT',   'KxC_5|Q(~kRUP[*?>[eambzP|%f6kvWp.{3E27xhvoh9V;rV-S!L>[v<!^~?OyY*');

define('NONCE_SALT',       'Q{S}%U24L0/Nmdji~Nuo.&:brM*}[&t]sEbdd]WV{ngCpP]I2$15z{ EH|)1weT;');

define('JWT_AUTH_SECRET_KEY', 'Yayasandel#Webkomplex');

define('JWT_AUTH_CORS_ENABLE', true);

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
