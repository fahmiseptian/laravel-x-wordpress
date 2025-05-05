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
define( 'DB_NAME', 'yayasan-del' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'w9de(P4bx9UGXg1S>lP(0^KYsI?e|xOq54z?UZKMMA^)@G3&y?)>G^1HyEF7t{[X' );
define( 'SECURE_AUTH_KEY',  'zFw3ZC?)x}#agJG0pZFn4_.=9rt3V1d4o|(bBP+0ZPE>b5&k<a#z$GR25Hk9hV_H' );
define( 'LOGGED_IN_KEY',    '-]X3;KEZg#UKq,*lBp.of+pSA[?BQy6Y.2#O&OMoTcKx7OSf*yQAA`x`[WT:y?8g' );
define( 'NONCE_KEY',        'ldD8`JD3R!O/<P+!17C*Le:!>UR7jAAfV-^?,WU(t__29TXF^6CO^~wB=nUz@oc>' );
define( 'AUTH_SALT',        'tsODQSnP6ZZx.1c>7Oy%LP;<[7td|se_&y6PLm;C+W*1a4o#XDWJDe-P)L;_q]4<' );
define( 'SECURE_AUTH_SALT', '1zmAC){7>EDp<wS`m*00E^~0DQXj9=BbKon[!Di<D)7_4>]C626K#@+2lG*[f(s8' );
define( 'LOGGED_IN_SALT',   'UrzaF+P>Z;XjWM #R[a&fQ?>TG%>pJ^U?I#l|8ra`B%:z_#krAgexMf5RVXYd6Lz' );
define( 'NONCE_SALT',       '+qEu=t<uDjR=#(}dY^lehID%>b<KB*6j/_BMixC0XCE4cGCoTJ%UDx:n,Cl!!-pn' );
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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
