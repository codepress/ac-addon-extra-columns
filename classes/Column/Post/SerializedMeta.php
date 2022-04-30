<?php
declare( strict_types=1 );

namespace ACA\ExtraColumns\Column\Post;

use ACA\ExtraColumns\Column\Experimental;
use ACA\ExtraColumns\Editing\Serialized;
use ACP\Editing\Editable;
use ACP\Editing\Storage;

class SerializedMeta extends Experimental implements Editable {

	public function __construct() {
		parent::__construct();

		// Change the type and label
		$this->set_type( 'column-serialized-meta-type' )
		     ->set_label( __( 'My Serialized Column', 'codepress-admin-columns' ) );
	}

	public function get_meta_key() {
		// Serialized data is stored within this meta key
		return 'my_meta_key';
	}

	private function get_meta_storage() {
		return new Storage\Post\Meta( $this->get_meta_key() );
	}

	public function get_value( $id ) {
		// Get data from meta table
		$values = $this->get_meta_storage()->get( $id );

		// Display a single serialized by array key
		return isset( $values['my_sub_key'] )
			? $values['my_sub_key']
			: null;
	}

	public function editing() {
		return new Serialized( $this->get_meta_storage() );
	}

}