<?php
/**
 * Hooks & filters
 */
add_shortcode( 'meta_to_lightbox_url', 'shortcode_meta_to_lightbox_url' );

/**
 * Get Youtube embed url from a youtube url
 *
 * @param  mixed $url
 * @return void
 */
function get_youtube_embed_url( $url ){
    $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';
    $longUrlRegex = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
	$youtube_id  = '';

    if (preg_match($longUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }

    if (preg_match($shortUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }
    return 'https://www.youtube.com/embed/' . $youtube_id ;
}

/**
 * Shortcode to get meta value and convert it to lightbox url
 *
 * @param  mixed $atts
 * @param  mixed $content
 * @return void
 */
function shortcode_meta_to_lightbox_url( $atts, $content = null ){
	global $wp;
    $a = shortcode_atts( array(
        'post_id' => '',
        'key' => ''
    ), $atts );

	$post_id = !empty($a['post_id']) ? $a['post_id'] : get_the_ID();

    $url = get_post_meta( $post_id, $a['key'], true );


	$youtube_url_pattern = '/(youtube\.com)|(youtu\.be)/i' ;
	if( preg_match($youtube_url_pattern, $url, $matches) ) {
		$url = get_youtube_embed_url($url);
		$video_type = 'youtube';
	} else {
		$video_type = 'hosted';
	}


	$render = '#elementor-action%3Aaction%3Dlightbox%26settings%3D'. urlencode ( base64_encode('{"type":"video","videoType":"'. $video_type .'","url":"'. $url .'?feature=oembed"}') );

    return $render;
    
}