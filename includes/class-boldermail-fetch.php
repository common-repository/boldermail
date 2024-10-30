<?php
/**
 * Update the data in the WordPress website with the data
 * from the Boldermail server when the WP_Query filters the posts.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.7.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Fetch class.
 *
 * @since 1.7.0
 */
class Boldermail_Fetch {

	/**
	 * Initialize the hooks.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function init() {

		/**
		 * Update posts retrieved from the WordPress database after they have
		 * been fetched and internally processed.
		 *
		 * @since 1.3.0
		 */
		add_filter( 'the_posts', array( __CLASS__, 'update_campaigns' ), 10, 2 );
		add_filter( 'the_posts', array( __CLASS__, 'update_subscriber_statuses_count' ), 10, 2 );

	}

	/**
	 * Update "Preparing" and "Sending" campaigns before displaying the
	 * newsletters in admin list table.
	 *
	 * @since  1.7.0
	 * @param  WP_Post[] $posts Array of post objects.
	 * @param  WP_Query  $query Query instance.
	 * @return WP_Post[]
	 */
	public static function update_campaigns( $posts, $query ) {

		if ( ! ( is_admin() && $query->is_main_query() ) ) {
			return $posts;
		}

		foreach ( $posts as $post ) {

			$newsletter = boldermail_get_newsletter( $post );

			if ( ! $newsletter ) {
				return $posts;
			}

			if ( $newsletter->get_type() !== 'regular' ) {
				return $posts;
			}

			if ( ! in_array( $newsletter->get_status(), [ 'preparing', 'sending' ], true ) ) {
				return $posts;
			}

			$campaign_data = boldermail()->api->get_campaign_data(
				[
					'campaign_id' => $newsletter->get_campaign_id(),
				]
			);

			if ( ! is_wp_error( $campaign_data ) ) {
				$newsletter->save( $campaign_data );
			}

		}

		return $posts;

	}

	/**
	 * Update the list with the number of subscribers with each subscription status.
	 * This only updates the posts that are obtained through a WP_Query
	 * query (`edit.php`). Individual edit pages (`post.php`) do not use
	 * WP_Query nor get_posts, but rather use `get_post`, which uses
	 * $wpdb to directly get the post. There is no filter to modify `get_post`.
	 *
	 * @since  1.7.0
	 * @param  WP_Post[] $posts Array of post objects.
	 * @param  WP_Query  $query Query instance.
	 * @return WP_Post[]
	 */
	public static function update_subscriber_statuses_count( $posts, $query ) {

		if ( ! ( is_admin() && $query->is_main_query() ) ) {
			return $posts;
		}

		foreach ( $posts as $post ) {

			$list = boldermail_get_list( $post );

			if ( ! $list ) {
				return $posts;
			}

			$list->update_counts();

		}

		return $posts;

	}

}

Boldermail_Fetch::init();
