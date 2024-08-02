<?php
/**
 * Hooks & filters
 */
add_shortcode( 'get_acf_subfield', 'shortcode_get_acf_subfield' );

/**
 * Get ACF subfield
 *
 * @param array $atts
 *
 * @return mixed
 */
function shortcode_get_acf_subfield( $atts ) {
	$a = shortcode_atts( array(
		'post_id' => '',
		'field'   => '',
		'path'    => '',
	), $atts );
  
	$post_id = !empty($a['post_id']) ? $a['post_id'] : get_the_ID();
	$data    = get_field( $a['field'], $post_id );
	
	if( !empty($a['path']) ){
	  $path = explode('->', $a['path']);
  
	  foreach ($path as $key) {
		if(isset($data[$key])){
		  $data = $data[$key];
		}
	  }
	}
  
	if( is_array( $data) ) {
	  $data = wp_json_encode($data);
	}
	
	return $data;
}