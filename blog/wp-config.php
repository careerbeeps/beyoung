<?php
//Begin Really Simple SSL Load balancing fix
$server_opts = array("HTTP_CLOUDFRONT_FORWARDED_PROTO" => "https", "HTTP_CF_VISITOR"=>"https", "HTTP_X_FORWARDED_PROTO"=>"https", "HTTP_X_FORWARDED_SSL"=>"on", "HTTP_X_FORWARDED_SSL"=>"1");
foreach( $server_opts as $option => $value ) {
if ( (isset($_ENV["HTTPS"]) && ( "on" == $_ENV["HTTPS"] )) || (isset( $_SERVER[ $option ] ) && ( strpos( $_SERVER[ $option ], $value ) !== false )) ) {
$_SERVER[ "HTTPS" ] = "on";
break;
}
}
//END Really Simple SSL

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
define('DB_NAME', 'beyoung_blog');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'RT2x5w7!');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('FS_METHOD', 'direct');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ' H^!P%S-OQkDqZ4Z1!7d(jmj:WLid_xp~j[z46;5%S)@_n6yjKJzWb;6(fqQt7ck');
define('SECURE_AUTH_KEY',  '__(B`|vN+m |1Z} p8Uwt9S5$wpY`}+P5CI)@,*GbM~)/&3/5{KXlV0j;U+t$(W]');
define('LOGGED_IN_KEY',    '>d0`^1HXKM=p[:#lntEqZ6D7j_7Jr-*A~lu|]NJxGr,vC{#He|:7<C9DWOloeI-`');
define('NONCE_KEY',        '6Q!3p_uFELoGqi9@eoe{5ah7!GiDIbS[g]qh^Jb(9VdcPWnLk!rAUoXP4z&2Pvri');
define('AUTH_SALT',        'bftl{$8gFA8zc$$7r~i[xqImk4x}J-6fy&vaj%8WgY2XX+U8v<uBlU9#Rbae)uLM');
define('SECURE_AUTH_SALT', 'XsE4qF{+sRpyS_-Gl)[C6zm1W)9`mfp{d0)SKe~~LUnN(jEf`56+Q{+0ajg3R|2A');
define('LOGGED_IN_SALT',   'Z#riuT=~oO4Z:ZcaIL|+8=M@B%<Stg5<KvdQEEo%)KKB-!AZrC,:/xqu#@)viI[/');
define('NONCE_SALT',       'YP=EI`IhV;dc~Q4]po~2Dq?ufnN9SgVK8A1(-RALUQ?@BZaBXDvrcz~y)Hj.J0+U');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'magento_';

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

$host = 'https://' . $_SERVER['HTTP_HOST'] . '/blog/';
define('WP_HOME',$host);
define('WP_SITEURL',$host);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
