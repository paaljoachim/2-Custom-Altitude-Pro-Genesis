<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'altitude', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'altitude' ) );

//* Add Image upload and Color select to WordPress Theme Customizer
require_once( get_stylesheet_directory() . '/lib/customize.php' );

//* Include Customizer CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Altitude Pro Theme' );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/altitude/' );
define( 'CHILD_THEME_VERSION', '1.0.3' );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'altitude_enqueue_scripts_styles' );
function altitude_enqueue_scripts_styles() {

	wp_enqueue_script( 'altitude-global', get_bloginfo( 'stylesheet_directory' ) . '/js/global.js', array( 'jquery' ), '1.0.0' );

	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'altitude-google-fonts', '//fonts.googleapis.com/css?family=Ek+Mukta:200,800', array(), CHILD_THEME_VERSION );
		
	//* Enqueue Parallax on non handhelds i.e., desktops, laptops etc. and not on tablets and mobiles
	// Source: http://daneden.github.io/animate.css/
	wp_enqueue_style( 'animate', get_stylesheet_directory_uri() . '/animate.css' );
	wp_enqueue_script( 'waypoints', get_stylesheet_directory_uri() . '/js/jquery.waypoints.min.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'waypoints-init', get_stylesheet_directory_uri() .'/js/waypoints-init.js' , array( 'jquery', 'waypoints' ), '1.0.0' );	
	
	// Custom code
	wp_enqueue_style( 'custom', get_stylesheet_directory_uri() . 'custom.css' );
}


//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add new image sizes
add_image_size( 'featured-page', 1140, 400, TRUE );

//* Add support for 1-column footer widget area
add_theme_support( 'genesis-footer-widgets', 1 );

//* Add support for footer menu
add_theme_support( 'genesis-menus' , array( 'secondary' => __( 'Before Header Menu', 'altitude' ), 'primary' => __( 'Header Menu', 'altitude' ), 'footer' => __( 'Footer Menu', 'altitude' ) ) );

//* Unregister the header right widget area
unregister_sidebar( 'header-right' );

//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

//* Remove output of primary navigation right extras
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

//* Remove navigation meta box
add_action( 'genesis_theme_settings_metaboxes', 'altitude_remove_genesis_metaboxes' );
function altitude_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {

    remove_meta_box( 'genesis-theme-settings-nav', $_genesis_theme_settings_pagehook, 'main' );

}

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_header', 'genesis_do_subnav', 5 );

//* Add secondary-nav class if secondary navigation is used
add_filter( 'body_class', 'altitude_secondary_nav_class' );
function altitude_secondary_nav_class( $classes ) {

	$menu_locations = get_theme_mod( 'nav_menu_locations' );

	if ( ! empty( $menu_locations['secondary'] ) ) {
		$classes[] = 'secondary-nav';
	}

	return $classes;

}

//* Hook menu in footer
add_action( 'genesis_footer', 'altitude_footer_menu', 7 );
function altitude_footer_menu() {

	genesis_nav_menu( array(
		'theme_location' => 'footer',
		'container'      => false,
		'depth'          => 1,
		'fallback_cb'    => false,
		'menu_class'     => 'genesis-nav-menu',	
	) );

}

//* Add Attributes for Footer Navigation
add_filter( 'genesis_attr_nav-footer', 'genesis_attributes_nav' ); 

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'flex-height'     => true,
	'width'           => 360,
	'height'          => 76,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'footer-widgets',
	'footer',
) );

//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'altitude_author_box_gravatar' );
function altitude_author_box_gravatar( $size ) {

	return 176;

}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'altitude_comments_gravatar' );
function altitude_comments_gravatar( $args ) {

	$args['avatar_size'] = 120;

	return $args;

}

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Relocate after entry widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area', 5 );

//* Setup widget counts
function altitude_count_widgets( $id ) {
	global $sidebars_widgets;

	if ( isset( $sidebars_widgets[ $id ] ) ) {
		return count( $sidebars_widgets[ $id ] );
	}

}

function altitude_widget_area_class( $id ) {

	$count = altitude_count_widgets( $id );

	$class = '';
	
	if ( $count == 1 ) {
		$class .= ' widget-full';
	} elseif ( $count % 3 == 1 ) {
		$class .= ' widget-thirds';
	} elseif ( $count % 4 == 1 ) {
		$class .= ' widget-fourths';
	} elseif ( $count % 2 == 0 ) {
		$class .= ' widget-halves uneven';
	} else {	
		$class .= ' widget-halves';
	}

	return $class;
	
}

//* Relocate the post info
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'genesis_post_info', 5 );

//* Customize the entry meta in the entry header
add_filter( 'genesis_post_info', 'altitude_post_info_filter' );
function altitude_post_info_filter( $post_info ) {

    $post_info = '[post_date format="M d Y"] [post_edit]';

    return $post_info;

}

//* Customize the entry meta in the entry footer
add_filter( 'genesis_post_meta', 'altitude_post_meta_filter' );
function altitude_post_meta_filter( $post_meta ) {

	$post_meta = 'Written by [post_author_posts_link] [post_categories before=" &middot; Categorized: "]  [post_tags before=" &middot; Tagged: "]';

	return $post_meta;
	
}

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'front-page-1',
	'name'        => __( 'Front Page 1', 'altitude' ),
	'description' => __( 'This is the front page 1 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-2',
	'name'        => __( 'Front Page 2', 'altitude' ),
	'description' => __( 'This is the front page 2 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-3',
	'name'        => __( 'Front Page 3', 'altitude' ),
	'description' => __( 'This is the front page 3 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-4',
	'name'        => __( 'Front Page 4', 'altitude' ),
	'description' => __( 'This is the front page 4 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-5',
	'name'        => __( 'Front Page 5', 'altitude' ),
	'description' => __( 'This is the front page 5 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-6',
	'name'        => __( 'Front Page 6', 'altitude' ),
	'description' => __( 'This is the front page 6 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-7',
	'name'        => __( 'Front Page 7', 'altitude' ),
	'description' => __( 'This is the front page 7 section.', 'altitude' ),
) );


/*---------- CUSTOM -----*/

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'inner-page-1',
	'name'        => __( 'Inner Page 1', 'altitude' ),
	'description' => __( 'This is the inner page 1 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
	'id'          => 'inner-page-2',
	'name'        => __( 'Inner Page 2', 'altitude' ),
	'description' => __( 'This is the inner page 2 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
	'id'          => 'inner-page-3',
	'name'        => __( 'Inner Page 3', 'altitude' ),
	'description' => __( 'This is the inner page 3 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
	'id'          => 'inner-page-4',
	'name'        => __( 'Inner Page 4', 'altitude' ),
	'description' => __( 'This is the inner page 4 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
	'id'          => 'inner-page-5',
	'name'        => __( 'Inner Page 5', 'altitude' ),
	'description' => __( 'This is the inner page 5 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
	'id'          => 'inner-page-6',
	'name'        => __( 'Inner Page 6', 'altitude' ),
	'description' => __( 'This is the inner page 6 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
	'id'          => 'inner-page-7',
	'name'        => __( 'Inner Page 7', 'altitude' ),
	'description' => __( 'This is the inner page 7 section.', 'altitude' ),
) );



/*********** Add a category featured image. **************/
//include_once( get_stylesheet_directory() . '/category-featured-image.php' );


/* ------ Sticky/Fixed Footer Functions https://9seeds.com/sticky-footer-genesis/ ---------*/
add_action( 'genesis_after_header', 'stickyfoot_wrap_begin'); // Changed genesis_before_header to after header.
function stickyfoot_wrap_begin() {
 echo '<div class="page-wrap">';
}
 
add_action( 'genesis_before_footer', 'stickyfoot_wrap_end');
function stickyfoot_wrap_end() {
 echo '</div><!-- page-wrap -->';
}




// Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'hello_bar_scripts_styles' );
function hello_bar_scripts_styles() {
	wp_enqueue_script( 'hello-bar', esc_url( get_stylesheet_directory_uri() ) . '/js/hello-bar.js', array( 'jquery' ), '1.0.0' );
}


//Add in new Widget areas
add_action( 'widgets_init', 'hello_bar_extra_widgets' );
function hello_bar_extra_widgets() {
	genesis_register_sidebar( array(
	'id'          => 'preheaderleft',
	'name'        => __( 'preHeaderLeft', 'altitude-pro' ),
	'description' => __( 'This is the preheader Left area', 'altitude-pro' ),
	'before_widget' => '<div class="first one-half preheaderleft">',
    'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id'          => 'preheaderright',
	'name'        => __( 'preHeaderRight', 'altitude-pro' ),
	'description' => __( 'This is the preheader Left area', 'altitude-pro' ),
	'before_widget' => '<div class="one-half preheaderright">',
    'after_widget' => '</div>',
	) );
}

//Position the preHeader Area
add_action('genesis_before_header','hello_bar_preheader_widget');
function hello_bar_preheader_widget() {
	echo '<div class="preheadercontainer hello-bar "><div class="wrap">';
    	genesis_widget_area ('preheaderleft', array(
        'before' => '<div class="preheaderleftcontainer">',
        'after' => '</div>',));
    	genesis_widget_area ('preheaderright', array(
        'before' => '<div class="preheaderrightcontainer">',
        'after' => '</div>',));
    	echo '</div></div>';
}

add_filter( 'body_class', 'sk_body_class' );
/**
 * Add "inner" class to 'body' element for inner pages
 * i.e., for all pages other than site's homepage/front page.
 *
 * @author Sridhar Katakam
 * @link   http://sridharkatakam.com/add-inner-body-class-inner-pages-wordpress/
 */
function sk_body_class( $classes ) {
	if ( ! is_front_page() ) {
		$classes[] = 'inner';
	}

	return $classes;
}


/* http://genesisdeveloper.me/different-primary-menu-on-pages-using-altitude-pro-theme/ and http://victorfont.com/conditional-secondary-menus-genesis-themes/ 
function gd_nav_menu_args( $args ){
 if( ( 'primary' == $args['theme_location'] ) && is_page('inner-page') ) {
 $args['menu'] = 'Inner menu'; // Add your menu name here. My case it is "Menu for Page"
 }
 return $args;
}
add_filter( 'wp_nav_menu_args', 'gd_nav_menu_args' );




/*--------- Code from Showcase Pro - for adding a full width featured image below the title -----------

//* Page Header
 include_once( get_stylesheet_directory() . '/lib/page-header.php' );

//* Add screen reader class to archive description
add_filter( 'genesis_attr_author-archive-description', 'genesis_attributes_screen_reader_class' );

//* Add viewport meta tag for mobile browsers
// add_theme_support( 'genesis-responsive-viewport' );


/* ==========================================================================
 * Helper Functions
 * ========================================================================== */

/**
 * Bar to Line Break
 *
 *
function showcase_bar_to_br( $content ) {
	return str_replace( ' | ', '<br class="mobile-hide">', $content );
}

