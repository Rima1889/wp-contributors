<?php
/**
 * Class WP_Contributors_Init_Test
 *
 * @package WP Contributors Plugin
 * @version 1.0
 */

/**
 * Initalise function of Test WP Contributors.
 *
 * @package WP Contributors
 */
class WP_Contributors_Init_Test extends WP_UnitTestCase {

	/**
	 * Test function for Constructor Function.
	 */
	public function test_constructor() {
		$add_meta_box = new WP_Contributors_Init();

		// Check if both actions are registered.
		$meta_action_hooked = has_action( 'admin_init', array( $add_meta_box, 'wp_contributors_register_metabox' ) );
		$post_action_hooked = has_action( 'save_post', array( $add_meta_box, 'wp_contributors_meta_box_save' ) );

		$actions_registered = ( 10 === $meta_action_hooked && 10 === $post_action_hooked ) ? 'registered' : 'not registered';

		$this->assertTrue( 'registered' === $actions_registered );
	}

	/**
	 * Test function for adding meta boxes on add new post
	 */
	public function test_wp_contributors_register_metabox() {
		global $wp_meta_boxes;
		$add_meta_box = new WP_Contributors_Init();
		$add_meta_box->wp_contributors_register_metabox();

		$add_post_screen_id = $wp_meta_boxes['post']['normal']['default']['wp-contributors-data']['id'];

		$meta_boxes_added = ( 'wp-contributors-data' === $add_post_screen_id );

		$this->assertTrue( $meta_boxes_added );
	}

	/**
	 * Test function for adding custom meta box html.
	 */
	public function test_wp_contributors_meta_box_save() {
		global $wp_query;
		global $post;

		$add_meta_box = new WP_Contributors_Init();

		// Create two Dummy user ids.
		$user_ids = $this->factory->user->create_many( 2 );

		// Create a dummy post using the 'WP_UnitTest_Factory_For_Post' class and give the post author's user ud as 2.
		$post_id = $this->factory->post->create(
			array(
				'post_status'  => 'publish',
				'post_title'   => 'Test 1',
				'post_content' => 'Test Content',
				'post_author'  => 2,
				'post_type'    => 'post',
			)
		);

		// Create a custom query for the post with the above created post id.
		$wp_query = new WP_Query(
			array(
				'post__in'       => array( $post_id ),
				'posts_per_page' => 1,
			)
		);

		// Run the WordPress loop through this query to set the global $post.
		if ( $wp_query->have_posts() ) {
			while ( $wp_query->have_posts() ) {
				$wp_query->the_post();
			}
		}

		// Set the array of user ids to post meta with meta key 'wp_contributors_data', with the above created post id.
		update_post_meta( $post_id, 'wp_contributors_data', $user_ids );

		// Store the echoed value of the wp_contributors_register_metabox() into $wp_contributors_meta_callback using output buffering.
		$custom_box_html = ob_start();
		$add_meta_box->wp_contributors_meta_callback( $post );
		$custom_box_html = ob_get_clean();

		// Validate the output string contains the class names we are expecting.
		$author_string = strpos( $custom_box_html, 'wp-contributors' );

		$custom_box_html_output = ( false !== $author_string ) ? true : false;
		$this->assertTrue( $custom_box_html_output );

		wp_reset_postdata();
	}

}
