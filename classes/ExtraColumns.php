<?php

namespace ACA\ExtraColumns;

use AC;
use ACA\WC;
use ACP;

final class ExtraColumns extends AC\Plugin {

	private $columns;

	/**
	 * @var string
	 */
	protected $file;

	public function __construct( $file ) {
		$this->file = $file;
	}

	public function register() {
		add_action( 'ac/column_groups', array( $this, 'register_column_group' ) );
		add_action( 'acp/column_types', array( $this, 'register_columns' ) );

		AC()->admin()->register_page( new Admin\Page\ExperimentalColumns() );
	}

	protected function get_file() {
		return $this->file;
	}

	protected function get_version_key() {
		return 'aca_extra_columns';
	}

	/**
	 * @param AC\Groups $groups
	 */
	public function register_column_group( $groups ) {
		$groups->register_group( 'experimental', 'Experimental' );
	}

	/**
	 * @param AC\ListScreen $list_screen
	 */
	public function register_columns( $list_screen ) {
		if ( $list_screen instanceof AC\ListScreen\Post ) {
			$list_screen->register_column_types_from_dir( 'ACA\ExtraColumns\Column\Post' );
		}

		if ( $list_screen instanceof AC\ListScreen\Media ) {
			$list_screen->register_column_types_from_dir( 'ACA\ExtraColumns\Column\Post' );
			$list_screen->register_column_types_from_dir( 'ACA\ExtraColumns\Column\Media' );
		}

		if ( $list_screen instanceof AC\ListScreen\User ) {
			$list_screen->register_column_types_from_dir( 'ACA\ExtraColumns\Column\User' );
		}

		if ( $list_screen instanceof WC\ListScreen\ShopOrder ) {
			$list_screen->register_column_types_from_dir( 'ACA\ExtraColumns\Column\ShopOrder' );
		}

		if ( $list_screen instanceof ACP\ListScreen\Taxonomy ) {
			$list_screen->register_column_types_from_dir( 'ACA\ExtraColumns\Column\Taxonomy' );
		}

		if ( $list_screen instanceof AC\ListScreen\Comment ) {
			$list_screen->register_column_types_from_dir( 'ACA\ExtraColumns\Column\Comment' );
		}
	}

	/**
	 * Register list screens
	 */
	public function register_list_screens() {

		// List Screen for Profile Builder Pro
		if ( function_exists( 'wppb_plugin_init' ) ) {
			AC()->register_list_screen( new ListScreen\AdminApproval() );
		}

	}

	public function set_available_extra_columns() {
		$columns = array();
		$dirs = array(
			'ACA\ExtraColumns\Column\Post',
			'ACA\ExtraColumns\Column\Media',
			'ACA\ExtraColumns\Column\ShopOrder',
			'ACA\ExtraColumns\Column\User',
			'ACA\ExtraColumns\Column\Taxonomy',
			'ACA\ExtraColumns\Column\Comment',
		);

		foreach ( $dirs as $dir ) {
			$columns = array_merge( $columns, $this->get_column_types_from_dir( $dir ) );
		}

		$this->columns = $columns;
	}

	/**
	 * @param $dir
	 */
	private function get_column_types_from_dir( $dir ) {

		$classes = AC\Autoloader::instance()->get_class_names_from_dir( $dir );

		foreach ( $classes as $class ) {
			$column = new $class;
			if ( ! $column instanceof AC\Column ) {
				continue;
			}
			$column_types[ $column->get_type() ] = $column->get_label();
		}

		return $column_types;
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

	public function get_active_extra_columns() {
		return get_option( Admin\Page\ExperimentalColumns::SETTINGS_NAME );
	}

}