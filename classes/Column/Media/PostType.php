<?php

namespace ACA\ExtraColumns\Column\Media;

use ACA\ExtraColumns;
use ACA\ExtraColumns\Column;
use ACP\Filtering;

/**
 * @since 2.0
 */
class PostType extends Column\Experimental
	implements Filtering\Filterable {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-media_post_type' );
		$this->set_label( __( 'Post Type', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$post_type = $this->get_raw_value( $id );

		if ( ! $post_type ) {
			return $this->get_empty_char();
		}

		$post_type_object = get_post_type_object( $post_type );

		if ( ! $post_type_object ) {
			return $this->get_empty_char();
		}

		return $post_type_object->labels->singular_name;
	}

	public function get_raw_value( $id ) {
		$parent = wp_get_post_parent_id( $id );

		if ( ! $parent ) {
			return false;
		}

		return get_post_type( $parent );
	}

	public function filtering() {
		return new ExtraColumns\Filtering\Media\PostType( $this );
	}

}