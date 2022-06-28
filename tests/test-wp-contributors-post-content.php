<?php
/**
 * Class WP_Contributors_Post_Content_Test
 *
 * @package WP Contributors Plugin
 * @version 1.0
 */

/**
 * Initalise function of Test WP Contributors.
 *
 * @package WP Contributors
 */
class WP_Contributors_Post_Content_Test extends WP_UnitTestCase {

	/**
	 * Test function for Constructor Function.
	 */
	public function test_constructor() {
		$wp_contributors_post_content = new WP_Contributors_Post_Content();

		// Check if both actions are registered.
		$the_content_hooked       = has_filter( 'the_content', array( $wp_contributors_post_content, 'wp_contributor_filter_the_content' ) );
		$wp_enqueue_script_hooked = has_action( 'wp_enqueue_scripts', array( $wp_contributors_post_content, 'wp_contributor_custom_enqueue' ) );

		$actions_registered = ( 10 === $the_content_hooked && 10 === $wp_enqueue_script_hooked ) ? 'registered' : 'not registered';
		$this->assertTrue( 'registered' === $actions_registered );
	}

	/**
	 * Test function for Post Content.
	 */
	public function test_wp_contributor_filter_the_content() {
		global $wp_query;
		$display_contributors = new WP_Contributors_Post_Content();

		// Create a dummy post using the 'WP_UnitTest_Factory_For_Post' class.
		$post_id = $this->factory->post->create(
			array(
				'post_status'  => 'publish',
				'post_title'   => 'Test 1',
				'post_content' => 'Test Content',
			)
		);

		// Create two Dummy user ids.
		$user_ids = $this->factory->user->create_many( 2 );

		// Call the update_post_meta to store the array of two user ids created above into 'wp_contributors_data' post meta key.
		update_post_meta( $post_id, 'wp_contributors_data', $user_ids );

		// Reset the $wp_query global post variable and create a new WP Query.
		$wp_query = new WP_Query(
			array(
				'post__in'       => array( $post_id ),
				'posts_per_page' => 1,
			)
		);

		// Run the WordPress loop through this query and call our wpco_display_contributors() to add the $content to each post content.
		if ( $wp_query->have_posts() ) {
			while ( $wp_query->have_posts() ) {
				$wp_query->the_post();

				$wp_query->is_singular = true;

				$content                = get_the_content();
				$filtered_output        = $display_contributors->wp_contributor_filter_the_content( $content );
				$string_found           = strpos( $filtered_output, 'wp-contributor-author-name' );
				$custom_box_html_output = ( false !== $string_found ) ? true : false;
				$this->assertTrue( $custom_box_html_output );
			}
		}
	}

	/**
	 * Test function for CSS files.
	 */
	public function test_wp_contributor_custom_enqueue() {
		$enqueue_style = new WP_Contributors_Post_Content();
		$enqueue_style->wp_contributor_custom_enqueue();

		// Check if the stylesheet is enqueued, wp_style_is will return true if its enqueued.
		$enqueued_post_meta_css = wp_style_is( 'wp-contributor-custom', 'registered' );

		$this->assertTrue( $enqueued_post_meta_css );
	}

}
