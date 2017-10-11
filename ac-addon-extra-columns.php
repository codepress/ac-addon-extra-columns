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

/**
 * @since 1.0
 */
final class ACA_Extra_Columns {

	CONST CLASS_PREFIX = 'ACA_Extra_Columns_';

	private $version;

	private $columns;

	private static $_instance = null;

	/**
	 * @var ACA_NinjaForms
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	private function __construct() {
		add_action( 'after_setup_theme', array( $this, 'init' ) );
	}

	/**
	 * Init
	 */
	public function init() {
		if ( ! is_admin() ) {
			return;
		}

		if ( $this->has_missing_dependencies() ) {
			return;
		}

		AC()->autoloader()->register_prefix( self::CLASS_PREFIX, plugin_dir_path( __FILE__ ) . 'classes/' );

		add_action( 'ac/column_groups', array( $this, 'register_column_group' ) );
		add_action( 'acp/column_types', array( $this, 'register_columns' ) );

		add_action( 'ac/admin_pages', array( $this, 'register_pages' ) );

		$this->set_available_extra_columns();
	}

	/**
	 * @return bool True when there are missing dependencies
	 */
	private function has_missing_dependencies() {
		require_once $this->get_plugin_dir() . 'classes/Dependencies.php';

		$dependencies = new ACA_Extra_Columns_Dependencies( __FILE__ );

		$dependencies->is_acp_active( '4.0.10' );

		return $dependencies->has_missing();
	}

	public function register_columns( $list_screen ) {
		if ( $list_screen instanceof AC_ListScreen_Post ) {
			$list_screen->register_column_types_from_dir( ac_addon_extra_columns()->get_plugin_dir() . 'classes/Column/Post', self::CLASS_PREFIX );
		}
	}

	/**
	 * @param AC_Groups $groups
	 */
	public function register_column_group( $groups ) {
		$groups->register_group( 'experimental', 'Expirimental' );
	}

	public function get_basename() {
		return plugin_basename( __FILE__ );
	}

	public function get_plugin_dir() {
		return plugin_dir_path( __FILE__ );
	}

	public function get_plugin_url() {
		return plugin_dir_url( __FILE__ );
	}

	/**
	 * Set plugin version
	 */
	private function set_version() {
		$plugins = get_plugins();

		$this->version = $plugins[ $this->get_basename() ]['Version'];
	}

	public function get_version() {
		if ( null === $this->version ) {
			$this->set_version();
		}

		return $this->version;
	}

	public function get_active_extra_columns() {
		return get_option( ACA_Extra_Columns_Admin_Page_ExperimentalColumns::SETTINGS_NAME );
	}

	/**
	 * @return array
	 */
	public function get_available_extra_columns() {
		if ( ! $this->columns ) {

			$this->set_available_extra_columns();

		}

		return $this->columns;
	}

	public function set_available_extra_columns() {
		$columns = array();
		$dirs = array(
			ac_addon_extra_columns()->get_plugin_dir() . 'classes/Column/Post',
		);

		foreach ( $dirs as $dir ) {
			$columns = array_merge( $columns, $this->get_column_types_from_dir( $dir ) );
		}

		$this->columns = (array) $columns;
	}

	/**
	 * @param $dir
	 */
	private function get_column_types_from_dir( $dir ) {
		$column_types = array();
		$prefix = rtrim( self::CLASS_PREFIX, '_' ) . '_';
		$classes = AC()->autoloader()->get_class_names_from_dir( $dir, $prefix );

		foreach ( $classes as $class ) {
			$column = new $class;

			if ( ! $column instanceof AC_Column ) {
				continue;
			}

			$column_types[ $column->get_type() ] = $column->get_label();
		}

		return $column_types;
	}

	/**
	 * @param AC_Admin_Pages $pages
	 */
	public function register_pages( $pages ) {
		$pages->register_page( new ACA_Extra_Columns_Admin_Page_ExperimentalColumns() );
	}

}

function ac_addon_extra_columns() {
	return ACA_Extra_Columns::instance();
}

ac_addon_extra_columns();