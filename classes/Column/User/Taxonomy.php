<?php

namespace ACA\ExtraColumns\Column\User;

use AC;
use ACA\ExtraColumns\Option\ActiveColumns;
use ACA\ExtraColumns\Setting;
use ACP;

class Taxonomy extends AC\Column\Taxonomy
	implements ACP\Export\Exportable, ACP\Editing\Editable {

	public function __construct() {
		parent::__construct();

		$this->set_label( 'User Taxonomy' );
	}

	/**
	 * @return bool True when post type has associated taxonomies
	 */
	public function is_valid() {
		return in_array( $this->get_type(), ( new ActiveColumns() )->get() );
	}

	public function register_settings() {
		$this->add_setting( new Setting\UserTaxonomy( $this ) );
	}

	public function export() {
		return new ACP\Export\Model\Post\Taxonomy( $this );
	}

	public function editing() {
		return new ACP\Editing\Service\Post\Taxonomy( $this->get_taxonomy(), true );
	}

}