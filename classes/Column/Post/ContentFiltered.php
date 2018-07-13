<?php

namespace ACA\ExtraColumns\Column\Post;

use ACA\ExtraColumns\Column;
use AC;

class ContentFiltered extends Column\Experimental {

	public function __construct() {
		parent::__construct();

		$this->set_label( 'Post Content Filtered' );
		$this->set_type( 'column-extra-columns_post_content_filtered' );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_content_filtered', $post_id, 'raw' );
	}

	public function register_settings() {
		$this->add_setting( new AC\Settings\Column\WordLimit( $this ) );
	}

}