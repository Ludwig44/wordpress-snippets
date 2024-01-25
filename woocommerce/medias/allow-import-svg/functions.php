<?php
/**
 * Hooks & filters
 */
add_filter( 'wp_check_filetype_and_ext', 'add_filetype_and_ext', 10, 4 );
add_filter( 'upload_mimes', 'add_svg_mime_type' );

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

    $mimes['svg'] = 'image/svg+xml';

    return $mimes;
}