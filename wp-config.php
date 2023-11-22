<?php
define( 'WP_CACHE', true /* Modified by NitroPack */ ); // Added by WP Rocket

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'i9579684_wp1' );

/** Database username */
define( 'DB_USER', 'i9579684_wp1' );

/** Database password */
define( 'DB_PASSWORD', 'Q.1wqQNVHnK431YO0gU85' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY',         'Y24kkHFzO5Z2ziuSc8c3VFzRq2kMih7fIBZxl6BKQFg108h12uAibxdkJNT8iRtX');
define('SECURE_AUTH_KEY',  'mKg6nN2lG2xlhmMNl2bKQtxng3uIrqquMUX6ZIcYUOd0fUHERhmR2e80lkgry302');
define('LOGGED_IN_KEY',    'LuDTRi0a2ILLLm3csdzU0bhIKNXw0ZR3x07xGlVI6vO5awLPLue0vUB57nYtMqYo');
define('NONCE_KEY',        '1oIo6HAHJPtb3OgKgCAxndJr8EKK8BJwLuGMRjEIwMv3XkiUgtLrBcHBTq1LObkL');
define('AUTH_SALT',        'IFNqGSoPiVdWYGvR4IuzsDZE2jBE09utHTSVpHIeVxPb5SRoUEjH0HesQFsSCrWp');
define('SECURE_AUTH_SALT', '9knRyZyH8BfKPg8aXMyA5z9AqnTSXT5L9ksRINi6QUqwHabjn2Urj5b1Q1RBb3gD');
define('LOGGED_IN_SALT',   'vjPIiUPZPfZpcdRvUlCydW5To6GJu02fg768s8fpn42tHGMTo3kz8ZDSbzzRmfsz');
define('NONCE_SALT',       'fRkJQxXZnPJYiuUvfWjbkMeey9WPPDhQP86ahHK9ycwNiT36JxOWO9Lnf1O3Qxuy');

/**
 * Other customizations.
 */
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');


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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
