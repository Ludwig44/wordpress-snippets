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
	global $shortcode_get_acf_subfield_cache;
	
	if( !is_array($shortcode_get_acf_subfield_cache) ){
		$shortcode_get_acf_subfield_cache = array();
	}

	$a = shortcode_atts( array(
		'post_id' => '',
		'field'   => '',
		'path'    => '',
		'cache'   => 'true',
	), $atts );
  
	$post_id = !empty($a['post_id']) ? $a['post_id'] : get_the_ID();
	
	if( $a['cache'] == 'true' && isset($shortcode_get_acf_subfield_cache[$post_id][$a['field']]) ){
		$data = $shortcode_get_acf_subfield_cache[$post_id][$a['field']];
	} else {
		$data = get_field( $a['field'], $post_id );
		if( $a['cache'] == 'true' ){
			$shortcode_get_acf_subfield_cache[$post_id][$a['field']] = $data;
		}
	}
	
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