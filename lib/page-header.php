<?php

//* Add opening markup for the Page Header
$header_color = get_option( 'showcase_header_color', 'true' );
if ( $header_color === 'white' ) {
	add_action( 'genesis_after_header', 'showcase_opening_page_header', 8 );
} else {
	add_action( 'genesis_before_header', 'showcase_opening_page_header' );
}

function showcase_opening_page_header() {
	if ( ( is_front_page() && is_active_sidebar( '' ) ) || ( is_page() && !is_page_template( 'page_landing.php' ) ) || is_single() || is_archive() || is_home() ) {

		/*if ( is_front_page() && is_active_sidebar( 'front-page-hero' ) ) {
			$hero_image = get_option( 'showcase-hero-image', sprintf( '%s/images/hero-image-1.jpg', get_stylesheet_directory_uri() ) );
			$background_image = 'style="background-image: url(' . $hero_image . ')"';
		}*/

		if ( ( is_page() || is_single() || ( is_home() && get_option('page_for_posts') ) ) && has_post_thumbnail() ) {
			if ( is_home() && get_option('page_for_posts') ) {
				$posts_page_id = get_option('page_for_posts');
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $posts_page_id ), 'full' );
			} else {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			}
			$background_image = 'style="background-image: url(' . $image[0] . ')"';
		}

		if ( $background_image ) {
			$background_image_class = 'with-background-image';
		}

		?>

		<div class="header-wrap bg-primary <?php echo $background_image_class; ?>" <?php echo $background_image; ?>>

		<?php
	}
}

//* Add closing markup for the Page Header
add_action( 'genesis_after_header', 'showcase_closing_page_header' );
function showcase_closing_page_header() {

	if ( ( is_front_page() && is_active_sidebar( 'front-page-hero' ) ) || ( is_page() && !is_page_template( 'page_landing.php' ) ) || is_single() || is_archive() || is_home() ) {

		?>
			<div class="page-header">
				<div class="wrap">
					<?php

					if ( is_front_page() && is_active_sidebar( 'front-page-hero' ) ) {

						/* genesis_widget_area( 'front-page-hero', array(
							'before' => '<div id="front-page-hero" class="front-page-hero widget-area">',
							'after'  => '</div>',
						) ); */

					} elseif ( is_archive() ) {

						if ( is_category() || is_tag() || is_tax() ) {

							//* Remove the description
							remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );

							echo single_term_title( '<h1>', '</h1>', true );
							/**
							  * This is a weird hack to fix the non-closing h1 above, for some reason
							  *	it's including the paragraph below, so I'm echoing a closing h1 tag.
							  */
							echo '</h1>';
							echo term_description();

						} elseif ( is_author() ) {

							echo genesis_author_box();

						}

					} elseif( is_home() && get_option('page_for_posts') ) {

						$posts_page_id = get_option('page_for_posts');
						echo '<h1>' . get_the_title($posts_page_id) . '</h1>';
						remove_action( 'genesis_before_loop', 'genesis_do_posts_page_heading' );

					} else {

						if ( is_singular() && !is_page_template( 'page_blog.php' ) ) {

							/*if ( !is_singular( 'team' ) ) {
								genesis_post_info();
							}*/

							//* Remove the entry title (requires HTML5 theme support)
							remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

						}

						echo showcase_bar_to_br( the_title( '<h1>', '</h1>', false ) );
					}

					?>
				</div>
				<?php genesis_do_subnav(); ?>
			</div>
		</div>

		<?php
	}
}