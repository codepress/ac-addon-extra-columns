<?php

namespace ACA\ExtraColumns\ListScreen;

use AC;

class AdminApproval extends AC\ListScreen\User {

	public function __construct() {
		parent::__construct();

		$this->set_label( 'Users - Admin Approval' );
		$this->set_screen_id( 'users_page_admin_approval' );
		$this->set_screen_base( 'users' );
		$this->set_key( 'wp-users_admin_approval' );
		$this->set_page( 'admin_approval' );
	}

	public function get_screen_link() {
		return add_query_arg( array( 'page' => $this->get_page(), 'layout' => $this->get_layout_id() ), $this->get_admin_url() );
	}

	/**
	 * @since 2.4.10
	 */
	public function is_current_screen( $wp_screen ) {
		return $wp_screen && $wp_screen->id === $this->get_screen_id() && 'admin_approval' === filter_input( INPUT_GET, 'page' );
	}

	protected function register_column_types() {
		parent::register_column_types();
	}

	public function get_list_table() {
		return new \wpp_list_approved_unapproved_users();
	}
}