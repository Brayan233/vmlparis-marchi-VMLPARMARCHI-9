<?php

require_once ( dirname(__DIR__).'/vendor/autoload.php');

use Symfony\Component\Dotenv\Dotenv;

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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

$dotenv = new Dotenv();
if ( file_exists( dirname( __DIR__ ) . '/.superdock' ) ) {
  $dotenv->load( dirname( __DIR__ ) . '/.superdock');
}
if ( ! file_exists( dirname( __DIR__ ) . '/.env' ) && file_exists( dirname( __DIR__ ) . '/.env.local' ) ) {
	$dotenv->load( dirname( __DIR__ ) . '/.env.local');
} else if ( file_exists( dirname( __DIR__ ) . '/.env' ) ) {
	$dotenv->load( dirname( __DIR__ ) . '/.env');
}

$SUPERDOCK = $_ENV['SUPERDOCK'];
$SUPERDOCK_DB_NAME = $_ENV['SUPERDOCK_' . $_ENV['SUPERDOCK'] . '_DB_NAME'];
$SUPERDOCK_DB_USER = $_ENV['SUPERDOCK_' . $_ENV['SUPERDOCK'] . '_DB_USER'];
$SUPERDOCK_DB_PASS = $_ENV['SUPERDOCK_' . $_ENV['SUPERDOCK'] . '_DB_PASS'];
$SUPERDOCK_DB_HOST = $_ENV['SUPERDOCK_' . $_ENV['SUPERDOCK'] . '_DB_HOST'];

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', $SUPERDOCK_DB_NAME );

/** MySQL database username */
define( 'DB_USER', $SUPERDOCK_DB_USER );

/** MySQL database password */
define( 'DB_PASSWORD', $SUPERDOCK_DB_PASS );

/** MySQL hostname */
define( 'DB_HOST', $SUPERDOCK_DB_HOST );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '?a8i/PIF{X)*!},:UuIH3]m;WXeY!^mb:~hBh_YRs=)bS;AO^9k>[Uz*E]dU*VF]' );
define( 'SECURE_AUTH_KEY',  'nTg-mLz/olGIpe(EM,%p%`eGI(X9=@TRuyz%uPAme3 Vq,PY|ghbeE:{/:znO ],' );
define( 'LOGGED_IN_KEY',    '1F2D<Fv(jZ$v 9>aEv9?0 +Zs`.:h$J!dO^N0$,>,l[L+75<+~%vP[h!*wr}W?<a' );
define( 'NONCE_KEY',        'uyZpG@U3}3Op iV(H7+,k]!cj*}wOqT6,~`Fw7[_7 1&gRjcWWLbD;GG0c*J6r#h' );
define( 'AUTH_SALT',        'fTr~Dk;*(jmAy#d}#Zbg%gQQ#_($HUZh{w6!K~?5=}77UE)oJg38D.Gh>VY3jHPD' );
define( 'SECURE_AUTH_SALT', '/q~q%#@Br!BY_+93r61w|#{[E`Rs9n4x=O,0hOfY(^K.OXK9U`1v5KUcz<<hzi_i' );
define( 'LOGGED_IN_SALT',   'k~>EiJvFw4n+pBkP>4W?*>>^0<8e IqE0pNm*ji`SQV[kC,:$?r@Yrwr H-HMe#w' );
define( 'NONCE_SALT',       ')`CG]SoXFH}|.-I#;Wz-_a66URK2*;rG>0W6Z97!+w6OSJVOFeQ((;X*=W0m? g5' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';