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
define( 'DB_NAME', 'testwork64768' );

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
define( 'AUTH_KEY',         'G28|/:IzPvy$dHWT^0yB[.3rHUioEsv%#xX k.QsWh%d?We:_4gX:@o4uxX6&:(i' );
define( 'SECURE_AUTH_KEY',  '!@aQ@il9z(apv&Q{pE5)3K**,iDBT@@4%f{~Z=nJwuAy8U@Jj;Ks$eQf~vE=>IH~' );
define( 'LOGGED_IN_KEY',    '?]Jm-#0Ova<{=lbz/sZaruGMye8nuA]bS4 .~~l&pp6LZmU>Bo3rc]$@faBKU#vy' );
define( 'NONCE_KEY',        'T>;y6C.2x&**7p0oSGS4S/^7GqT!9PC.PwjF^%6cGyG6g:5?W5:6+YyShE;5!a5E' );
define( 'AUTH_SALT',        '=AhyLlOLUqZnQ@I3eaMEtW-fEIxb7|rfNljD)u3L>#d56k2}J8hL~3SaoPq7sO_x' );
define( 'SECURE_AUTH_SALT', 'b/>M9rc59[3X Oa1Orvn gV4D`Aja^F_Ha4grgU^PlT5mi{.kH>L}h>Qx4RFn&$}' );
define( 'LOGGED_IN_SALT',   'QPkAgchz~HnG{8>b%c?#ocZ+#]m4c0W[}5yY@J%x-!5s-X8hoPC[!cN;.bXUOp3B' );
define( 'NONCE_SALT',       'v3%(PHLNho,!PEESn7xlEU:plwFoUx k!mAyDNw~<6MR:wFZ~>1m!:P=.t3&B7)R' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
