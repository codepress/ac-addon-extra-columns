<?php
namespace ACA\ExtraColumns\Column\Comment;

use ACA\ExtraColumns\Column;


class PostCategory extends Column\Experimental {

	public function __construct() {
		parent::__construct();

		$this->set_label( 'Post Category' );
		$this->set_type( 'column-extra-columns_post_category' );
	}

	public function get_value( $id ) {
		$post_id = $this->get_post_id( $id );

		if ( ! $post_id ) {
			return $this->get_empty_char();
		}

		$terms = ac_helper()->taxonomy->get_term_links( $this->get_raw_value( $id ), get_post_type( $post_id ) );

		if ( empty( $terms ) ) {
			return $this->get_empty_char();
		}

		return ac_helper()->string->enumeration_list( $terms, 'and' );
	}

	public function get_raw_value( $id ) {
		$post_id = $this->get_post_id( $id );

		if ( ! $post_id ) {
			return false;
		}

		$terms = get_the_terms( $post_id, 'category' );

		if ( ! $terms || is_wp_error( $terms ) ) {
			return false;
		}

		return $terms;
	}

	private function get_post_id( $id ) {
		$comment = get_comment( $id );

		if ( ! $comment ) {
			return false;
		}

		return $comment->comment_post_ID;
	}
}