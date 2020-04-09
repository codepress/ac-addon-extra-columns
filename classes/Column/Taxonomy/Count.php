<?php

namespace ACA\ExtraColumns\Column\Taxonomy;

use ACA\ExtraColumns\Column\Experimental;

class Count extends Experimental {

	public function __construct() {
		parent::__construct();

		$this->set_label( 'Count for current Post Type' );
		$this->set_type( 'column-term_count_post_type' );
	}

	public function get_value( $id ) {
		$raw_value = $this->get_raw_value( $id );
		$count = $raw_value ? $raw_value : 0;
		$term = get_term( $id, $this->get_taxonomy() );

		$url = add_query_arg( [ 'post_type' => $this->get_current_post_type(), $this->get_taxonomy() => $term->slug ], admin_url( 'edit.php' ) );
		$value = sprintf( '<a href="%s">%s</a>', $url, $count );

		return $value;
	}

	private function get_current_post_type() {
		$post_type = filter_input( INPUT_GET, 'post_type' );

		if ( ! $post_type ) {
			$post_type = 'post';
		}

		return $post_type;
	}

	public function get_raw_value( $id ) {
		$posts = get_posts( [
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'post_type'      => $this->get_current_post_type() ? $this->get_current_post_type() : 'any',
			'tax_query'      => [
				[
					'taxonomy' => $this->get_taxonomy(),
					'field'    => 'id',
					'terms'    => $id,
				],
			],
		] );

		return count( $posts );
	}

}