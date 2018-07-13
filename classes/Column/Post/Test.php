<?php

namespace ACA\ExtraColumns\Column\Post;

use ACA\ExtraColumns\Column;

class Test extends Column\Experimental {

	public function __construct() {
		parent::__construct();

		$this->set_label( 'Test Column' );
		$this->set_type( 'column-extra-columns_test' );
	}

	public function get_value( $id ) {
		return 'Test for PostID: #' . $id;
	}
}