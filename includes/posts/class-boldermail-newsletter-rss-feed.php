<?php
/**
 * RSS Feed newsletter class.
 *
 * The Boldermail newsletter class for RSS Feed newsletters.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/abstract-class-boldermail-newsletter.php';

/**
 * Boldermail_Newsletter_RSS_Feed class.
 *
 * @since   1.0.0
 */
class Boldermail_Newsletter_RSS_Feed extends Boldermail_Newsletter {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param int $post_id The post ID.
	 */
	public function __construct( $post_id ) {
		parent::__construct( $post_id, 'rss-feed' );
	}

	/**
	 * Get the posts.
	 *
	 * @since  1.0.0
	 * @param  string $filter Type of filter to apply. Accepts 'raw', 'preview', 'display', 'api'.
	 * @return array
	 */
	public function get_the_posts( $filter ) {

		$query_args = array(
			'posts_per_page'      => 1,
			'post_type'           => $this->get_rss_post_type(),
			'post_status'         => array( 'publish' ),
			/**
			 * Fix issues with post stickiness.
			 *
			 * @see https://wordpress.stackexchange.com/questions/260941/why-ignore-sticky-posts-in-sticky-post-query
			 *
			 * If the `ignore_sticky_posts` argument is not set
			 * or it's set to false or 0 (value by default):
			 *   + If there are posts within the query result that are part of
			 *     stick posts, WordPress will push them to the top of the query result.
			 *   + If any sticky post is not present within the query result,
			 *     WordPress will get all those sticky posts from the database
			 *     again and set them to the top of the query result.
			 *     This will cause our plugin to send a
			 *     campaign with the sticky post if no new content is found.
			 * So when the argument is set as `ignore_sticky_posts` => 1,
			 * WordPress simply ignores the above procedure.
			 * It doesn't exclude sticky posts specifically though.
			 * For that you need to set post__not_in argument.
			 *
			 * @since   1.0.0
			 */
			'ignore_sticky_posts' => 1,

			/**
			 * Exclude password protected posts, as we assume they behave as private.
			 *
			 * @since   2.0.0
			 */
			'has_password'        => false,
		);

		$taxonomy = $this->get_rss_taxonomy();

		$term__includes = $this->get_rss_term__includes();
		$term__excludes = $this->get_rss_term__excludes();

		if ( $taxonomy && ( $term__includes || $term__excludes ) ) {

			$query_args['tax_query'] = []; /* phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query */

			if ( $term__includes ) {
				$query_args['tax_query'][] = [ /* phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query */
					'taxonomy'         => $taxonomy,
					'field'            => 'term_id',
					'terms'            => $this->get_rss_term__includes(),
					'include_children' => false,
					'operator'         => 'IN',
				];
			}

			if ( $term__excludes ) {
				$query_args['tax_query'][] = [ /* phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query */
					'taxonomy'         => $taxonomy,
					'field'            => 'term_id',
					'terms'            => $this->get_rss_term__excludes(),
					'include_children' => false,
					'operator'         => 'NOT IN',
				];
			}

			if ( $term__includes && $term__excludes ) {
				$query_args['tax_query']['relation'] = 'AND';
			}

		}

		if ( 'display' === $filter ) {
			$query_args['date_query'] = [
				[
					'after'     => $this->get_last_rss_check_time(),
					'inclusive' => true,
				],
			];
		}

		return get_posts( $query_args );

	}

	/**
	 * Get the RSS campaign newsletter objects.
	 *
	 * @since  1.4.0
	 * @return Boldermail_Newsletter[]
	 */
	public function get_newsletters() {

		return array_map( 'boldermail_get_newsletter', $this->get_children() );

	}

	/**
	 * Get the last time the CRON job checked for new blog posts.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_last_rss_check_time() {

		return boldermail_sanitize_date( $this->get_meta( 'last_rss_check_time' ) );

	}

	/**
	 * Get the last time the CRON job created a new email campaign.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_last_rss_email_time() {

		return boldermail_sanitize_date( $this->get_meta( 'last_rss_email_time' ) );

	}

	/**
	 * Get how often to send the newsletter.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_rss_when_to_send() {

		return $this->get_meta( 'when_to_send' );

	}

	/**
	 * Get which days to send newsletter if every day.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_rss_which_days() {

		return (array) $this->get_meta( 'which_days' );

	}

	/**
	 * Get what day to send newsletter if every week.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_rss_what_day() {

		return $this->get_meta( 'what_day' );

	}

	/**
	 * Get which date to send newsletter if every month.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_rss_which_date() {

		return $this->get_meta( 'which_date' );

	}

	/**
	 * Get at what time to send the newsletter.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_rss_what_time() {

		return $this->get_meta( 'what_time' );

	}

	/**
	 * Get the post type for the feed.
	 *
	 * @since  2.3.0
	 * @return string
	 */
	public function get_rss_post_type() : string {

		return $this->get_meta( 'post_type' ) ?: 'post';

	}

	/**
	 * Get the taxonomy for the feed.
	 *
	 * @since  2.3.0
	 * @return string
	 */
	public function get_rss_taxonomy() : string {

		return $this->get_meta( 'taxonomy' );

	}

	/**
	 * Get the terms to include in the feed.
	 *
	 * @since  2.3.0
	 * @return array
	 */
	public function get_rss_term__includes() : array {

		return $this->get_meta( 'term__includes' ) ? (array) $this->get_meta( 'term__includes' ) : [];

	}

	/**
	 * Get the terms to exclude from the feed.
	 *
	 * @since  2.3.0
	 * @return array
	 */
	public function get_rss_term__excludes() : array {

		return $this->get_meta( 'term__excludes' ) ? (array) $this->get_meta( 'term__excludes' ) : [];

	}

	/**
	 * Check if RSS campaign has been published in Boldermail.
	 *
	 * @see    Boldermail_Post::is_published()
	 * @since  1.7.0
	 * @return bool
	 */
	public function is_published() {

		return in_array( $this->get_status(), array( 'publish', 'enabled', 'paused' ), true );

	}

	/**
	 * Are input fields read-only? Prevent editing if newsletter is enabled.
	 *
	 * @see    Boldermail_Newsletter::is_editable()
	 * @since  2.1.0
	 * @return bool
	 */
	public function is_editable() {

		return ! in_array( $this->get_status(), array( 'publish', 'enabled' ), true );

	}

}
