<?php

namespace ACA\ExtraColumns;

use AC;
use AC\Admin\RequestHandler;
use ACA\ExtraColumns\Admin\Page\ExperimentalColumns;
use ACA\WC;
use ACP;

final class ExtraColumns extends AC\Plugin {

	private $columns;

	/**
	 * @var string
	 */
	protected $file;

	public function __construct( $file ) {
		parent::__construct( $file, 'aca_extra_columns' );
	}

	public function register() {
		add_action( 'ac/column_groups', [ $this, 'register_column_group' ] );
		add_action( 'acp/column_types', [ $this, 'register_columns' ] );
		add_action( 'ac/list_screens', [ $this, 'register_list_screens' ] );

		add_action( 'ac/admin/page/menu', [ $this, 'add_menu_item' ] );
		add_filter( 'ac/admin/request/page', [ $this, 'handle_page_request' ], 10, 2 );
		//

		register_setting( ExperimentalColumns::SETTINGS_GROUP, ExperimentalColumns::SETTINGS_NAME );
	}

	public function add_menu_item( AC\Admin\Menu $menu ) {
		$url = add_query_arg(
			[
				RequestHandler::PARAM_PAGE => AC\Admin\Admin::NAME,
				RequestHandler::PARAM_TAB  => ExperimentalColumns::SETTINGS_NAME,
			],
			admin_url( 'options-general.php' )
		);

		$menu->add_item(
			new AC\Admin\Menu\Item( ExperimentalColumns::SETTINGS_NAME, $url, __( 'Experimental Columns', 'codepress-admin-columns' ) )
		);
	}

	public function handle_page_request( $page, $request ) {
		if ( ExperimentalColumns::SETTINGS_NAME === $request->get( 'tab' ) ) {
			$page = new ExperimentalColumns();
		}

		return $page;
	}

	protected function get_file() {
		return $this->file;
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
	public function register_list_screens( AC\ListScreens $list_screens ) {

		$list_screens->register_list_screen( new ListScreen\BuddyPressActivities() );

		// List Screen for Profile Builder Pro
		if ( function_exists( 'wppb_plugin_init' ) ) {
			$list_screens->register_list_screen( new ListScreen\AdminApproval() );
		}
	}

	public function set_available_extra_columns() {
		$columns = [];
		$dirs = [
			'ACA\ExtraColumns\Column\Post',
			'ACA\ExtraColumns\Column\Media',
			'ACA\ExtraColumns\Column\ShopOrder',
			'ACA\ExtraColumns\Column\User',
			'ACA\ExtraColumns\Column\Taxonomy',
			'ACA\ExtraColumns\Column\Comment',
		];

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