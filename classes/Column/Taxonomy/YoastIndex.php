<?php

namespace ACA\ExtraColumns\Column\Taxonomy;

use ACA\ExtraColumns\Column\Experimental;

class YoastIndex extends Experimental {

	public function __construct() {
		parent::__construct();

		$this->set_label( 'Yoast Term Index' );
		$this->set_type( 'column-term_yoast_settings' );
	}

	public function get_raw_value( $id ) {
		$global_options = get_option( 'wpseo_taxonomy_meta' );
		if ( ! isset( $global_options[ $this->get_taxonomy() ] ) ) {
			return false;
		}

		$taxonomy_options = $global_options[ $this->get_taxonomy() ];

		if ( ! isset( $taxonomy_options[ $id ] ) ) {
			return false;
		}

		$term_options = $taxonomy_options[ $id ];

		return $term_options['wpseo_noindex'];
	}

}