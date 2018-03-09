<?php

/**
 * @since 4.0
 */
class ACA_Extra_Columns_Column_ShopOrder_LineItems extends ACA_Extra_Columns_Column_Experimental
	implements ACP_Export_Column {

	public function __construct() {
		parent::__construct();

		$this->set_label( 'Order Line Items' );
		$this->set_type( 'column-extra-columns_line_items' );
	}

	public function get_value( $id ) {
		/** @var WC_Order_Item_Product[] $items */
		$items = $this->get_raw_value( $id );
		$line_items = array();

		foreach ( $items as $item ) {
			$line_items[] = sprintf( '<strong>%sx</strong> %s', $item->get_quantity(), $item->get_name() );
		}

		return implode( ',<br>', $line_items );
	}

	public function get_raw_value( $id ) {
		$order = new WC_Order( $id );
		$items = $order->get_items();

		return $items;
	}

	public function export() {
		return new ACP_Export_Model_StrippedValue( $this );
	}

}