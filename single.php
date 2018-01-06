<?php
/**
 * Showcase Pro
 *
 * This file edits the single template in the Showcase Pro Theme.
 *
 * @package Showcase
 * @author  Bloom
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/showcase/
 */

// Add page header body class to the head
add_filter( 'body_class', 'showcase_single_page_header_body_class' );
function showcase_single_page_header_body_class( $classes ) {

    if( has_post_thumbnail() )
        $classes[] = 'with-page-header';

    return $classes;

}

// Add page header
add_action( 'genesis_after_header', 'showcase_single_page_header', 8 );
function showcase_single_page_header() {
	$output = false;

    $image = get_post_thumbnail_id();

    if( $image ) {

        // Remove the title since we'll add it later
        remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

        $image = wp_get_attachment_image_src( $image, 'showcase_hero' );
        $background_image_class = 'with-background-image';
        $title = the_title( '<h1>', '</h1>', false );
        
        $output .= '<div class="page-header bg-primary with-background-image" style="background-image: url(' . $image[0] . ');"><div class="wrap">';
        $output .= '<div class="header-content">' . $title . '</div>';
        $output .= '</div></div>';
    }

	if( $output )
		echo $output;
}

genesis();