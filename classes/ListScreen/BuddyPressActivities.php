<?php

namespace ACA\ExtraColumns\ListScreen;

use AC;
use BP_Activity_List_Table;

class BuddyPressActivities extends AC\ListScreenWP {

	public function __construct() {
		$this->set_key( 'bp-activity' )
		     ->set_screen_id( 'toplevel_page_bp-activity' )
		     ->set_screen_base( 'admin' )
		     ->set_page( 'bp-activity' )
		     ->set_label( __( 'Activity', 'codepress-admin-columns' ) )
		     ->set_group( 'buddypress' );
	}

	public function get_heading_hookname() {
		return 'bp_activity_list_table_get_columns';
	}

	public function manage_value( $value, $column_name, $activity ) {
		return $this->get_display_value_by_column_name( $column_name, $activity['id'], $value );
	}

	protected function get_object( $id ) {
		return $id;
	}

	public function set_manage_value_callback() {
		add_action( 'bp_activity_admin_get_custom_column', [ $this, 'manage_value' ], 100, 3 );
	}

	public function is_current_screen( $wp_screen ) {
		return $wp_screen && $wp_screen->id === $this->get_screen_id() && 'edit' !== filter_input( INPUT_GET, 'action' );
	}

	public function get_screen_link() {
		return add_query_arg( [ 'page' => $this->get_page(), 'layout' => $this->get_layout_id() ], $this->get_admin_url() );
	}

	public function get_list_table() {
		return new BP_Activity_List_Table();
	}

	/**
	 * @throws \ReflectionException
	 */
	protected function register_column_types() {
		//$this->register_column_types_from_dir( 'ACA\BP\Column\Activity' );
	}

	public function get_table_attr_id() {
		return '#bp-activities-form';
	}

}