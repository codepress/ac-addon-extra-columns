<?php

class ACA_Extra_Columns_Column_User_Taxonomy extends AC_Column_Taxonomy
	implements ACP_Export_Column {

	public function __construct() {
		parent::__construct();

		$this->set_label( 'User Taxonomy' );
	}

	/**
	 * @return bool True when post type has associated taxonomies
	 */
	public function is_valid() {
		return in_array( $this->get_type(), (array) ac_addon_extra_columns()->get_active_extra_columns() );
	}

	public function register_settings() {
		$this->add_setting( new ACA_Extra_Columns_Setting_UserTaxonomy( $this ) );
	}

	public function export() {
		return new ACP_Export_Model_Post_Taxonomy( $this );
	}

}