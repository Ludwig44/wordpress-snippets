<?php
/**
 * Hooks & filters
 */
add_filter( 'wp_check_filetype_and_ext', 'add_filetype_and_ext', 10, 4 );
add_filter( 'upload_mimes', 'add_svg_mime_type' );
add_filter( 'wp_handle_upload_prefilter', 'sanitize_svg' );

/**
 * Add file type and extension to the media library
 *
 * @param  mixed $data
 * @param  mixed $file
 * @param  mixed $filename
 * @param  mixed $mimes
 * @return void
 */
function add_filetype_and_ext( $data, $file, $filename, $mimes ){
    
    global $wp_version;
    if ( $wp_version !== '4.7.1' && $wp_version !== '4.7.2' ) return $data;

    $filetype = wp_check_filetype( $filename, $mimes );

    return array(
        'ext'             => $filetype['ext'] ?? '',
        'type'            => $filetype['type'] ?? '',
        'proper_filename' => $data['proper_filename'] ?? ''
    );
}

/**
 * Add svg mime type
 *
 * @param  mixed $mimes
 * @return void
 */
function add_svg_mime_type( $mimes ){

    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    return $mimes;
}

function sanitize_svg( $file ){

    if ( 'image/svg+xml' !== $file['type'] ) return $file;

    /** 
     * TODO: here add Sanitizer like enshrined/svg-sanitize
     * 
     * @link https://github.com/darylldoyle/svg-sanitizer
     */

    return $file;
}