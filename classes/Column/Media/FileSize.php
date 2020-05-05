<?php

namespace ACA\ExtraColumns\Column\Media;

use AC;
use ACP\Sorting;
use ACP\Sorting\Type\DataType;

class FileSize extends AC\Column\Meta
	implements Sorting\Sortable {

	public function __construct() {
		$this->set_group( 'experimental' );

		$this->set_type( 'column-media_filesize' );
		$this->set_label( __( 'Filesize', 'codepress-admin-columns' ) );
	}

	public function get_meta_key() {
		return 'media_file_size';
	}

	public function get_value( $id ) {
		return ac_helper()->file->get_readable_filesize( $this->get_raw_value( $id ) );
	}

	public function get_file_size( $id ) {
		$value = 0;
		$abs = get_attached_file( $id );

		if ( file_exists( $abs ) ) {
			$value = filesize( $abs );
		}

		return $value;
	}

	public function get_raw_value( $id ) {
		$value = get_post_meta( $id, $this->get_meta_key(), true );

		if ( ! $value ) {
			$value = $this->get_file_size( $id );
			update_post_meta( $id, $this->get_meta_key(), $value );
		}

		return $value;
	}

	public function sorting() {
		return new Sorting\Model\MetaData( $this->get_meta_key(), $this, new DataType( DataType::NUMERIC ) );
	}

}