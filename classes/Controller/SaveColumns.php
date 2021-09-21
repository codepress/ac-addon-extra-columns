<?php

namespace ACA\ExtraColumns\Controller;

use AC\Registrable;
use AC\Request;
use AC\Storage\SiteOption;

class SaveColumns implements Registrable {

	const ACTION = 'acaec_save_columns';

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var SiteOption
	 */
	private $option;



	public function __construct( Request $request, SiteOption $option ) {
		$this->request = $request;
		$this->option = $option;
	}

	public function register(): void {
		add_action( 'admin_init', [ $this, 'handle_request' ] );
	}

	public function handle_request() {
		if ( self::ACTION !== $this->request->get( 'ec_action' ) ) {
			return;
		}

		$columns = $this->request->get('ac_active_extra_columns');

		$this->option->save( $columns );
	}

}