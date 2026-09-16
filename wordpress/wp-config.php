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
define( 'DB_NAME', 'wp_gd_cn' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'pezDispenser1!' );

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
define( 'AUTH_KEY',         'ip2woZW.2NdkeVkG,$[3Bh(=1.W3zY 7$VK vQ5 `HM1E1,b!YtyVzj7rQlf;z.&' );
define( 'SECURE_AUTH_KEY',  '0@V}l|=*T$fHs=4Bu#$]]3h|{5%I;*U;}]o|=lS#G|q,E@3jEZ7!%(,BLOwLrAg#' );
define( 'LOGGED_IN_KEY',    'RSu,+y2b.^xZ3uRNz<MqpLReQbM59h1DyiZD!JYAEN]|V5Gh4)3Pqh8aAIVTm33g' );
define( 'NONCE_KEY',        'Z5^oY.lM0MbLb0:&/UEm EujJR&xY+LibROT+Si<x0!@27qezfQ%LjR $zaXTmg&' );
define( 'AUTH_SALT',        's,clmlnkCW}e#^MZ@:jA<KiU`m{V1{$%NW{~9O%981Opk,/tC(.%E>/Yoz6r<c,`' );
define( 'SECURE_AUTH_SALT', '7EN<(ozSCd_-ZX8TMGAGN/X3M#[6;Xh/+ijqTB?,~6=R(eoF}ry(V]r6d!R;i;Rg' );
define( 'LOGGED_IN_SALT',   'V[-HUkqDP1:[=bA8N*<?: _AMMGN`;dJl,EIbKi#NEX<A>nVaBLR&^akO]mr %.v' );
define( 'NONCE_SALT',       'zwm<u:xM=/s*P<1?D-<F 2Fb_Sl+EyqLuquXq{!CHQ7~MN%Da%4rR6rQ?X{nzml)' );

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
