<?php

namespace ACA\ExtraColumns\Column;

use AC;
use ACA\ExtraColumns\Option\ActiveColumns;

class Experimental extends AC\Column {

	public function __construct() {
		$this->set_group( 'experimental' );
	}

	public function is_valid() {
		return in_array( $this->get_type(), ( new ActiveColumns() )->get() );
	}

}