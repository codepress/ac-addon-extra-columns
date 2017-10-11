<?php

/**
 * @since 4.0
 */
class ACA_Extra_Columns_Column_Post_Test extends ACA_Extra_Columns_Column_Experimental {

	public function __construct() {
		parent::__construct();

		$this->set_label( 'Test Column' );
		$this->set_type( 'column-extra-columns_test' );
	}

	public function get_value( $id ) {
		return 'Test for PostID: #' . $id;
	}
}