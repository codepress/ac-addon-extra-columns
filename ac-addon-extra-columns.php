<?php
/*
Plugin Name: 		Admin Columns Pro - Extra (Experimental) Columns
Version: 			1.0
Description: 		Add extra columns to Admin Columns that are not suitable for the core of Admin Columns Pro
Author: 			Codepress
Author URI: 		https://www.admincolumns.com
Text Domain: 		codepress-admin-columns
*/

use AC\Autoloader;
use ACA\ExtraColumns\Dependencies;
use ACA\ExtraColumns\ExtraColumns;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ACA_EXTRA_COLUMNS_FILE', __FILE__ );

require_once __DIR__ . '/classes/Dependencies.php';

add_action( 'after_setup_theme', function () {
	$dependencies = new Dependencies( plugin_basename( __FILE__ ), '1.0' );
	$dependencies->requires_acp( '5.1' );
	$dependencies->requires_php( '5.6.20' );

	if ( $dependencies->has_missing() ) {
		return;
	}

	Autoloader::instance()->register_prefix( 'ACA\ExtraColumns', __DIR__ . '/classes/' );

	$plugin = new ExtraColumns( __FILE__ );
	$plugin->register();
} );

function ac_addon_extra_columns() {
	return new ExtraColumns( __FILE__ );
}