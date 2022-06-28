<?php
/**
 * Class WP Contributors Init
 *
 * @package WP Contributors Plugin
 * @version 1.0
 */

if ( ! class_exists( 'WP_Contributors_Init' ) ) {
	/**
	 * Initalise function of WP Contributors.
	 *
	 * @package WP Contributors Init
	 */
	class WP_Contributors_Init {

		/**
		 * Construct.
		 */
		public function __construct() {
			// Register Metabox for post.
			add_action( 'admin_init', array( $this, 'wp_contributors_register_metabox' ) );
			// Save Meta Box to post.
			add_action( 'save_post', array( $this, 'wp_contributors_meta_box_save' ) );
		}

		/**
		 * Create Meta Box for Post and Page.
		 */
		public function wp_contributors_register_metabox() {
			add_meta_box(
				'wp-contributors-data',
				'WP Contributors',
				array( $this, 'wp_contributors_meta_callback' ),
				array( 'post' ),
				'normal',
				'default'
			);
		}

		/**
		 * MetaBox for WP Contributors
		 *
		 * @param object $post post data.
		 */
		public function wp_contributors_meta_callback( $post ) {
			$selected_user_data    = get_post_meta( $post->ID, 'wp_contributors_data', true );
			$wp_contributors_nonce = wp_create_nonce( 'wp-contributors-data' );
			?>
			<input type="hidden" name="wp-contributors-data-nonce" value="<?php echo esc_attr( $wp_contributors_nonce ); ?>" />
			<div id="wp-contributors-wrapper" class="wp-contributors-wrapper">
				<div class="wp-contributors-wrap">
					<div class="wp-contributors-items">
						<?php
						$get_user_args  = array(
							'fields' => array(
								'ID',
								'display_name',
							),
						);
						$get_users_data = get_users( $get_user_args );
						foreach ( $get_users_data as $get_user ) {
							$user_id   = $get_user->ID;
							$user_name = $get_user->display_name;
							$checked   = '';
							if ( ! empty( $selected_user_data ) ) {
								$checked = in_array( $user_id, $selected_user_data, true ) ? 'checked' : '';
							}
							?>
								<div class="wp-contributors-disable">
									<input type="checkbox" name="wp_contributors_data[]" value="<?php echo esc_attr( $user_id ); ?>" <?php echo esc_attr( $checked ); ?>> <?php echo esc_attr( $user_name ); ?>
								</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Save WP Contributors meta box data.
		 *
		 * @param int $post_id post/page id.
		 */
		public function wp_contributors_meta_box_save( $post_id ) {
			// Check post type.
			if ( ( 'post' !== get_post_type( $post_id ) ) ) {
				return;
			}
			// Once verify for security.
			$nonce_verify = isset( $_POST['wp-contributors-data-nonce'] ) ? wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wp-contributors-data-nonce'] ) ), 'wp-contributors-data' ) : '';

			if ( ! isset( $_POST['wp-contributors-data-nonce'] ) && ! $nonce_verify ) {
				return;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			// phpcs:ignore
			$wp_contributors_data = isset( $_POST['wp_contributors_data'] ) ? $_POST['wp_contributors_data'] : '';
			update_post_meta( $post_id, 'wp_contributors_data', $wp_contributors_data );
		}
	}
	$wp_contributors_init = new WP_Contributors_Init();
}
