<?php
/*
Plugin Name: 		Admin Columns Pro - Extra (Experimental) Columns
Version: 			1.0
Description: 		Add extra columns to Admin Columns that are not suitable for the core of Admin Columns Pro
Author: 			Codepress
Author URI: 		https://www.admincolumns.com
Text Domain: 		codepress-admin-columns
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ACA_EXTRA_COLUMNS_FILE', __FILE__ );

require_once __DIR__ . '/classes/Dependencies.php';

function aca_extra_columns_init() {
	$dependencies = new ACA_Extra_Columns_Dependencies( plugin_basename( ACA_EXTRA_COLUMNS_FILE ) );
	$dependencies->check_acp( '4.3' );

	if ( $dependencies->has_missing() ) {
		return;
	}

	require_once 'bootstrap.php';
}

add_action( 'after_setup_theme', 'aca_extra_columns_init' );