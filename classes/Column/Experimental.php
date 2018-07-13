<?php
namespace ACA\ExtraColumns\Column;

use AC;

class Experimental extends AC\Column {

	public function __construct() {
		$this->set_group( 'experimental' );
	}

	public function is_valid() {
		return in_array( $this->get_type(), (array) ac_addon_extra_columns()->get_active_extra_columns() );
	}

}