<?php
/**
 * Class WP Contributors Post Content
 *
 * @package WP Contributors Plugin
 * @version 1.0
 */

if ( ! class_exists( 'WP_Contributors_Post_Content' ) ) {
	/**
	 * Post Content function of WP Contributors.
	 *
	 * @package WP Contributors Post Content
	 */
	class WP_Contributors_Post_Content {

		/**
		 * Construct.
		 */
		public function __construct() {
			// Register Metabox for post.
			add_filter( 'the_content', array( $this, 'wp_contributor_filter_the_content' ) );
			// Save Meta Box to post.
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_contributor_custom_enqueue' ) );
		}

		/**
		 * Display WP contributor Data.
		 *
		 * @param object/array $content get post/page content.
		 */
		public function wp_contributor_filter_the_content( $content ) {

			// Check if we're inside the main loop in a single Post.
			$contributor_data = '';
			if ( is_singular() ) {
				$selected_user_data = get_post_meta( get_the_ID(), 'wp_contributors_data', true );
				if ( ! empty( $selected_user_data ) ) {
					$contributor_data .= '<div class="wp-contributor-front-wrapper">';
					$contributor_data .= '<div class="wp-contributor-title"><h6>';
					$contributor_data .= esc_html__( 'WP Contributor :', 'wp-contributor' );
					$contributor_data .= '</h6></div>';
					$contributor_data .= '<div class="wp-contributor-front-wrap">';
					foreach ( $selected_user_data as $selected_user ) {
						$author_name = get_the_author_meta( 'display_name', $selected_user );
						$author_id   = get_the_author_meta( 'ID', $selected_user );

						$contributor_data .= '<div class="wp-contributor-data">';
						$contributor_data .= '<a href="' . esc_url( get_author_posts_url( $author_id ) ) . '">';
						$contributor_data .= get_avatar( $author_id, 50 );
						$contributor_data .= '<div class="wp-contributor-author-name">' . $author_name . '</div>';
						$contributor_data .= '</a>';
						$contributor_data .= '</div>';
					}
					$contributor_data .= '</div>';
					$contributor_data .= '</div>';
				}
				return $content . $contributor_data;
			}

			return $content;
		}

		/**
		 * Register script/style.
		 */
		public function wp_contributor_custom_enqueue() {
			wp_register_style( 'wp-contributor-custom', WP_CONTRIBUTORS_PLUGIN_URL . '/assets/css/custom.css', '', WP_CONTRIBUTORS_PLUGIN_VER );
			wp_enqueue_style( 'wp-contributor-custom' );
		}

	}
	$wp_contributors_post_content = new WP_Contributors_Post_Content();
}
