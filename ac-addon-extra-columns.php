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
use AC\Entity\Plugin;
use AC\Plugin\Version;
use AC\Vendor\DI\Container;
use ACA\ExtraColumns\ExtraColumns;
use ACA\Houzez\Loader;

if ( ! defined('ABSPATH')) {
    exit;
}

define('ACA_EXTRA_COLUMNS_FILE', __FILE__);

require_once __DIR__ . '/classes/Dependencies.php';

add_action('acp/init', static function (Container $container) {
    $plugin = Plugin::from_plugin_file(__FILE__, new Version('1.0'));

    new Loader($plugin, $container);
});

function ac_addon_extra_columns()
{
    return new ExtraColumns(__FILE__, new Version('1.0'));
}