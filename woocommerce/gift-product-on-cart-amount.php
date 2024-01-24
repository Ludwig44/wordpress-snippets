<?php
function webdeclic_get_gift_product_id() {
	return 123456; // Change with your gifted product ID
}

add_action( 'woocommerce_before_calculate_totals', 'add_gift_product_on_cart' );
function add_gift_product_on_cart( $cart ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

	$free_product_id   = webdeclic_get_gift_product_id(); 
	$targeted_subtotal = 100; // Change with your targeted subtotal
	$cart_subtotal 	   = 0;

	foreach ( $cart->get_cart() as $cart_item_key => $cart_item ){
		$cart_product_id = $cart_item['product_id'] ?? false;
		$cart_variation_id = $cart_item['variation_id'] ?? false;

		if ( ($free_product_id == $cart_product_id || $free_product_id == $cart_variation_id) && isset($cart_item['is_gift_product']) ) {
			// if the product is already in cart, we update the set the price to 0
			$free_key = $cart_item_key;
			$free_qty = $cart_item['quantity'];
			$cart_item['data']->set_price(0);
		} else if(isset($cart_item['is_gift_product'])) {
			// if old gift product is in cart, we remove it. Prevent changing the product gift ID
			$cart->remove_cart_item( $cart_item_key );
		} else {
			// if the product is not the gift product, we calculate the subtotal
			$cart_subtotal += $cart_item['line_total'] + $cart_item['line_tax'];
		}
	}

	if ( ! isset($free_key) && $cart_subtotal >= $targeted_subtotal ) {
		// if the gift product is not in cart and the subtotal is ok, we add it
		$cart->add_to_cart( $free_product_id, 1, '', '', array('is_gift_product' => true), 0 );
	}
	else if ( isset($free_key) && $cart_subtotal < $targeted_subtotal ) { 
		// if the gift product is in cart and the subtotal is not ok, we remove it
		$cart->remove_cart_item( $free_key );
	}
	else if ( isset($free_qty) && $free_qty > 1 ) {
		// if the gift product is in cart and the quantity is more than 1, we set the quantity to 1
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
