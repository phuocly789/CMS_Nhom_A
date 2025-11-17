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
define( 'DB_NAME', 'cms_nhom_a' );

/** Database username */
define( 'DB_USER', 'wpuser' );

/** Database password */
define( 'DB_PASSWORD', 'Phuoccao@123' );

/** Database hostname */
define( 'DB_HOST', '103.75.183.156' );

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
define( 'AUTH_KEY',         '6EV{mtG<kR18RS(iPnoN_KI?j5Xe&4Ms%y!#6Q9EolubN Z|($acO][):_a.b>du' );
define( 'SECURE_AUTH_KEY',  'yJq/ 8^qQ!1N#t(uj`4On5:ZrDr64RqJ[^DO9l`[yS,lJ=;UyIv`PeNG2@}R/:u{' );
define( 'LOGGED_IN_KEY',    'ZK;Npntj~NC6YoU-~%OPh6Qay+~?uzYEKL>XD=0i@rdM5`FTOXYnc{Sjf/i1Qtii' );
define( 'NONCE_KEY',        ',DhM~I,(B#tv&bm!:<1F j$yO]axJqpt6yf+Xk+xyzno|`?,K%W_!fEF}K6Wj1YQ' );
define( 'AUTH_SALT',        'a,]*=d-ZNO_.[XhjY-Hii=UcGr*@pqM0J[iOs.V[U0)xjl~8#/VA<*_4H?TajnP,' );
define( 'SECURE_AUTH_SALT', 'Wl>3Hm.yFP |c?Cf#g_2N_%9`MF?OC}aFPw2CL`]K0N!!5W2Sti,pE_KXr4l-Vj0' );
define( 'LOGGED_IN_SALT',   '@iXG qF|ZF}yE1|{DNTWHy4a.eit>}Us<cSg<^-P;CA<,i z[^Q3]q%#<ympTt!X' );
define( 'NONCE_SALT',       'EU[QOz8^CzvT3!q?umm]}7T~d6:~^[m>sgJbiXY>_6Bry9>dhU|c3X8q$t!d}4at' );

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
