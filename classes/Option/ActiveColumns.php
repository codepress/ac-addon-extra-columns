<?php

namespace ACA\ExtraColumns\Option;

use AC;

class ActiveColumns extends AC\Storage\SiteOption {

	const KEY = 'aca_ec_active_columns';

	public function __construct() {
		parent::__construct( self::KEY );
	}

}