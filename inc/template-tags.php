<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package IP_Theme
 */

/**
 * Conditional Schema attributes for `<div id="page"`.
 */
if ( ! function_exists( 'ip_site_schema' ) ) :

	function ip_site_schema() {

		// Change page slugs and template names as needed.
		if ( is_page( 'about' ) || is_page( 'about-us' ) || is_page_template( 'page-about.php' ) || is_page_template( 'about.php' ) ) {
			$itemtype = esc_attr( 'AboutPage' );
		} elseif ( is_page( 'contact' ) || is_page( 'contact-us' ) || is_page_template( 'page-contact.php' ) || is_page_template( 'contact.php' ) ) {
			$itemtype = esc_attr( 'ContactPage' );
		} elseif ( is_page( 'faq' ) || is_page( 'faqs' ) || is_page_template( 'page-faq.php' ) || is_page_template( 'faq.php' ) ) {
			$itemtype = esc_attr( 'QAPage' );
		} elseif ( is_page( 'cart' ) || is_page( 'shopping-cart' ) || is_page( 'checkout' ) || is_page_template( 'cart.php' ) || is_page_template( 'checkout.php' ) ) {
			$itemtype = esc_attr( 'CheckoutPage' );
		} elseif ( is_front_page() || is_page() ) {
			$itemtype = esc_attr( 'WebPage' );
		} elseif ( is_author() || is_plugin_active( 'buddypress/bp-loader.php' ) && bp_is_home() || is_plugin_active( 'bbpress/bbpress.php' ) && bbp_is_user_home() ) {
			$itemtype = esc_attr( 'ProfilePage' );
		} elseif ( is_search() ) {
			$itemtype = esc_attr( 'SearchResultsPage' );
		} else {
			$itemtype = esc_attr( 'Blog' );
		}

		echo $itemtype;

	}

endif;

/**
 * Prints HTML with meta information for the current post-date/time.
 */
if ( ! function_exists( 'ip_theme_posted_on' ) ) :

	function ip_theme_posted_on() {

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'ip-theme' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}

endif;

/**
 * Prints HTML with meta information for the current author.
 */
if ( ! function_exists( 'ip_theme_posted_by' ) ) :

	function ip_theme_posted_by() {

		$byline = sprintf(
			esc_html_x( 'by %s', 'post author', 'ip-theme' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}

endif;

/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
if ( ! function_exists( 'ip_theme_entry_footer' ) ) :

	function ip_theme_entry_footer() {

		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {

			$categories_list = get_the_category_list( esc_html__( ', ', 'ip-theme' ) );
			if ( $categories_list ) {
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'ip-theme' ) . '</span>', $categories_list );
			}

			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'ip-theme' ) );

			if ( $tags_list ) {
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'ip-theme' ) . '</span>', $tags_list );
			}

		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {

			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'ip-theme' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					__( 'Edit <span class="screen-reader-text">%s</span>', 'ip-theme' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);

	}

endif;

/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
if ( ! function_exists( 'ip_theme_post_thumbnail' ) ) :

	function ip_theme_post_thumbnail() {

		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div>

		<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail( 'post-thumbnail', array(
				'alt' => the_title_attribute( array(
					'echo' => false,
				) ),
			) );
			?>
		</a>

		<?php
		endif;
	}
endif;