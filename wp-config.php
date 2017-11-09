<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'nueva');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '123456');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'P1E{caUj!oGT5,M,bi7Ugo(#lz*<rdX]1GwGr1?|r(_Eu&+EzF!Z(?<rM6.Ra<6<');
define('SECURE_AUTH_KEY',  'GsrEbzCogQRy:{nQ`Dj?5@Gro0t/9J8MptCGi ,ibUL9H]-rXl,AZ9IQ0GxvK=bM');
define('LOGGED_IN_KEY',    'wc+:&w1.,y2k%Ww#r_Tp,i.!q6yImYnO/hD&O+-k17*-I4ivMfA@SR<r.v;Mi]s=');
define('NONCE_KEY',        'NqxDp%Lg6fWTg.F_v@I+*[ftEQlwVLl/ QAzA>;lQ~e;5I[IOE1@QMS^KI,pWf0:');
define('AUTH_SALT',        'wQ{+PxFN4{xIa+zoEF|*WzlFuu?WiH5Wo;bfgnZ[r1&vo/ lGQ&Dy}b|q=kSuz.=');
define('SECURE_AUTH_SALT', '?q EJ<RZ>eTsu3`BR=fE/}y#TLk=;,FW<9R#0fRuN]35&J0iUgD?S/+%e?*2ex7K');
define('LOGGED_IN_SALT',   'jxtvI6;a4kQ5s=pg*u:8q+B;@sPEq I}(e{znM*Jy~At?5uVq$1*#T@:%azm!V[S');
define('NONCE_SALT',       'q$~?{F&=U!<ue;7JKNhZJt/NV^M_?;D! >M>[Wt#M^35TF$2(|C}19u#^^zLo|i,');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


if(is_admin()) {
add_filter('filesystem_method', create_function('$a', 'return "direct";' ));
define( 'FS_CHMOD_DIR', 0751 );
}

