<?php
/**
 * WP Contributors Plugin
 *
 * @package WP Contributors Plugin
 */

/*
Plugin Name: WP Contributors
Plugin URI: http://localhost/plugin-development
Description:
Vesion: 1.0
Author: rtCamp
Author URI:
License: GPLv2 or later
Text Domain: WP Contributors
*/

/*
The WP Contributors is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

The FAQ Schema is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with FAQ Schema. If not, see http://localhost/plugin-development.

Copyright 2022-2023 Automattic, Inc.
*/

/*
* If check abspath exists or not.
*/
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Hey, what are you doing here? You silly human!' );
}

define( 'WP_CONTRIBUTORS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_CONTRIBUTORS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_CONTRIBUTORS_PLUGIN_VER', '1.0' );

/**
 * Required plugin core file.
 */
if ( file_exists( WP_CONTRIBUTORS_PLUGIN_DIR . 'function.php' ) ) {
	require_once WP_CONTRIBUTORS_PLUGIN_DIR . 'function.php';
} else {
	esc_html_e( "WP Contributors plugin's core files are missing! Please re-install the plugin.", 'wp-contributors' );
	wp_die();
}

/**
 * Register activation hook.
 */
function wp_contributors_activate() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
}
register_activation_hook( __FILE__, 'wp_contributors_activate' );

/**
 * Register uninstall hook.
 */
function wp_contributors_deactivation() {
}
register_deactivation_hook( __FILE__, 'wp_contributors_deactivation' );

/**
 * Register uninstall hook.
 */
function wp_contributors_uninstall() {
	if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit();
	}
}
register_uninstall_hook( __FILE__, 'wp_contributors_uninstall' );

require_once 'admin/class-wp-contributors-init.php';
