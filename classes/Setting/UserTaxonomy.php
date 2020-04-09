<?php

namespace ACA\ExtraColumns\Setting;

use AC;

class UserTaxonomy extends AC\Settings\Column\Taxonomy {

	/**
	 * @var string
	 */
	private $taxonomy;

	protected function define_options() {
		return [ 'taxonomy' ];
	}

	/**
	 * @return AC\View
	 */
	public function create_view() {
		$taxonomy = $this->create_element( 'select', 'taxonomy' );
		$taxonomy->set_no_result( __( 'No taxonomies available.', 'codepress-admin-columns' ) )
		         ->set_options( ac_helper()->taxonomy->get_taxonomy_selection_options( 'user' ) )
		         ->set_attribute( 'data-label', 'update' )
		         ->set_attribute( 'data-refresh', 'column' );

		return new AC\View( [
			'setting' => $taxonomy,
			'label'   => __( 'Taxonomy', 'codepress-admin-columns' ),
		] );
	}

	/**
	 * @return string
	 */
	public function get_taxonomy() {
		return $this->taxonomy;
	}

	/**
	 * @param string $taxonomy
	 *
	 * @return bool
	 */
	public function set_taxonomy( $taxonomy ) {
		$this->taxonomy = $taxonomy;

		return true;
	}

}