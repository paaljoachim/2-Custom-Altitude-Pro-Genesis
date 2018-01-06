<?php
/* 
 * Adds the required CSS to the front end.
 */

add_action( 'wp_enqueue_scripts', 'altitude_css' );
/**
* Checks the settings for the images and background colors for each image
* If any of these value are set the appropriate CSS is output
*
* @since 1.0
*/
function altitude_css() {

	$handle  = defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme';

	$color = get_theme_mod( 'altitude_accent_color', altitude_customizer_get_default_accent_color() );

	// Add Front Page Background Images filter
	$opts = apply_filters( 'altitude_images', array( '1', '2', '3', '4', '5', '6', '7' ) );
	// Add Inner Page Background Images filter
	$inner_opts = apply_filters( 'altitude_inner_images', array( '1', '2', '3', '4', '5', '6', '7' ) );
	
	// Add Front Page Background Images settings
	$settings = array();
	// Add Inner Page Background Images settings
	$inner_settings = array();

	// Front Page Background Images options
	foreach( $opts as $opt ) {
		$settings[$opt]['image'] = preg_replace( '/^https?:/', '', get_option( $opt .'-altitude-image', sprintf( '%s/images/bg-%s.jpg', get_stylesheet_directory_uri(), $opt ) ) );
	}
	
	// Inner Page Background Images options
	foreach( $inner_opts as $inner_opt ) {
		$inner_settings[$inner_opt]['image'] = preg_replace( '/^https?:/', '', get_option( $inner_opt .'-altitude-inner-image', sprintf( '%s/images/bg-%s.jpg', get_stylesheet_directory_uri(), $inner_opt ) ) );
	}
	$css = '';
	
	// Front Page Background
	foreach ( $settings as $section => $value ) {
		$background = $value['image'] ? sprintf( 'background-image: url(%s);', $value['image'] ) : '';
		if ( is_front_page() ) {
			$css .= ( ! empty( $section ) && ! empty( $background ) ) ? sprintf( '.front-page-%s { %s }', $section, $background ) : '';
		}

	}
	
	// Inner page Background	
	foreach ( $inner_settings as $inner_section => $inner_value ) {
		$inner_background = $inner_value['image'] ? sprintf( 'background-image: url(%s);', $inner_value['image'] ) : '';
		if ( is_page_template( 'template_inner-page.php' ) ) {
			$css .= ( ! empty( $inner_section ) && ! empty( $inner_background ) ) ? sprintf( '.inner-page-%s { %s }', $inner_section, $inner_background ) : '';
			}
	
		}
		
	

	$css .= ( altitude_customizer_get_default_accent_color() !== $color ) ? sprintf( '
			a,
			.entry-title a:hover,
			.image-section a:hover,
			.image-section .featured-content .entry-title a:hover,
			.site-footer a:hover {
				color: %1$s;
			}
			button,
			input[type="button"],
			input[type="reset"],
			input[type="submit"],
			.archive-pagination li a:hover,
			.archive-pagination .active a,
			.button,
			.footer-widgets,
			.widget .button {
				background-color: %1$s;
			}
			button,
			input[type="button"],
			input[type="reset"],
			input[type="submit"],
			.button,
			.front-page input:focus,
			.front-page textarea:focus,
			.widget .button {
				border-color: %1$s;
			}
			', $color ) : '';
		if( $css ){
			wp_add_inline_style( $handle, $css );
		}
	}