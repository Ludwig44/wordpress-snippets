<?php
function webdeclic_get_gift_product_id() {
	return 123456; // Change with your gifted product ID
}

add_action( 'woocommerce_before_calculate_totals', 'add_gift_product_on_cart' );
function add_gift_product_on_cart( $cart ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

	$free_product_id   = webdeclic_get_gift_product_id(); 
	$targeted_subtotal = 100; // Change the min subtotal
	$cart_subtotal 	   = 0;

	foreach ( $cart->get_cart() as $cart_item_key => $cart_item ){
		if ( $free_product_id == $cart_item['product_id'] && isset($cart_item['is_gift_product']) ) {
			$free_key = $cart_item_key;
			$free_qty = $cart_item['quantity'];
			$cart_item['data']->set_price(0);
		} else {
			$cart_subtotal += $cart_item['line_total'] + $cart_item['line_tax'];
		}
	}

	if ( ! isset($free_key) && $cart_subtotal >= $targeted_subtotal ) {
		$cart->add_to_cart( $free_product_id, 1, '', '', array('is_gift_product' => true), 0 );
	}
	elseif ( isset($free_key) && $cart_subtotal < $targeted_subtotal ) { 
		$cart->remove_cart_item( $free_key );
	}
	elseif ( isset($free_qty) && $free_qty > 1 ) {
		$cart->set_quantity( $free_key, 1 );
	}
}

add_filter( 'woocommerce_quantity_input_args', 'force_product_gift_quantity', 10, 2 );
function force_product_gift_quantity( $args, $product ) {
	
	if(!isset($args['input_name'])) return $args;
	
	$cart_items = WC()->cart->get_cart();
	$input_name = $args['input_name'];
	$regex 		= '/cart\[(.+)\]\[qty\]/';

	preg_match($regex, $input_name, $matches);
	
	if(!isset($matches[1])) return $args;

	$cart_item_key = $matches[1];

	if ( $product->get_id() == webdeclic_get_gift_product_id() && isset($cart_items[$cart_item_key]['is_gift_product']) ) {
		$args['input_value'] = 1;
		$args['min_value'] = 1;
		$args['max_value'] = 1;
	}
	return $args;
}
