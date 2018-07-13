<?php

namespace ACA\ExtraColumns\Column\User;

use ACA\ExtraColumns\Setting;
use AC;
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
		return in_array( $this->get_type(), (array) ac_addon_extra_columns()->get_active_extra_columns() );
	}

	public function register_settings() {
		$this->add_setting( new Setting\UserTaxonomy( $this ) );
	}

	public function export() {
		return new ACP\Export\Model\Post\Taxonomy( $this );
	}

	public function editing() {
		return new ACP\Editing\Model\Post\Taxonomy( $this );
	}

}