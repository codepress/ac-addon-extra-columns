<?php

class ACA_Extra_Columns_Column_Experimental extends AC_Column {

	public function __construct() {
		$this->set_group( 'experimental' );
	}

	public function is_valid() {
		return in_array( $this->get_type(), ac_addon_extra_columns()->get_active_extra_columns() );
	}

}