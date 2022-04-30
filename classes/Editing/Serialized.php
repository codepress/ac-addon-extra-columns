<?php
declare( strict_types=1 );

namespace ACA\ExtraColumns\Editing;

use AC\Request;
use ACP\Editing\Service;
use ACP\Editing\Storage;
use ACP\Editing\View\Text;

class Serialized implements Service {

	/**
	 * @var Storage\Meta
	 */
	private $storage;

	public function __construct( Storage\Meta $storage ) {
		$this->storage = $storage;
	}

	public function get_view( $context ) {
		// Type of input field.
		return new Text();
	}

	public function get_value( $id ) {
		$values = $this->storage->get( $id );

		return isset( $values['my_sub_key'] )
			? $values['my_sub_key']
			: false;
	}

	public function update( Request $request ) {
		$value = $request->get( 'value' );
		$id = (int) $request->get( 'id' );

		// Get the stored serialized data
		$values = (array) $this->storage->get( $id );

		// Update the submitted value. e.g. $values[ 'artist' ] = 'Arthur Tatum Jr.'
		$values['my_sub_key'] = $value;

		// Save
		$this->storage->update( $id, $values );
	}

}