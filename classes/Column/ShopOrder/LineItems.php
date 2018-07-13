<?php

namespace ACA\ExtraColumns\Column\ShopOrder;

use ACA\ExtraColumns\Column;
use ACP;

class LineItems extends Column\Experimental
	implements ACP\Export\Exportable {

	public function __construct() {
		parent::__construct();

		$this->set_label( 'Order Line Items' );
		$this->set_type( 'column-extra-columns_line_items' );
	}

	public function get_value( $id ) {
		/** @var \WC_Order_Item_Product[] $items */
		$items = $this->get_raw_value( $id );
		$line_items = array();

		foreach ( $items as $item ) {
			$line_items[] = sprintf( '<strong>%sx</strong> %s', $item->get_quantity(), $item->get_name() );
		}

		return implode( ',<br>', $line_items );
	}

	public function get_raw_value( $id ) {
		$order = new \WC_Order( $id );
		$items = $order->get_items();

		return $items;
	}

	public function export() {
		return new ACP\Export\Model\StrippedValue( $this );
	}

}