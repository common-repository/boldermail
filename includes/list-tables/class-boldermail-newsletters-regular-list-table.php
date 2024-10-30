<?php
/**
 * List tables: Regular Newsletters.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.7.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

class_exists( 'Boldermail_Newsletters_List_Table' ) || require_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-newsletters-list-table.php';

/**
 * Boldermail_Newsletters_Regular_List_Table class.
 *
 * @since 1.7.0
 */
final class Boldermail_Newsletters_Regular_List_Table extends Boldermail_Newsletters_List_Table {

	/**
	 * Post type.
	 *
	 * @since 1.7.0
	 * @var   string
	 */
	protected $list_table_type = 'bm_newsletter';

	/**
	 * Constructor.
	 *
	 * @since 1.7.0
	 */
	public function __construct() {

		parent::__construct();

		add_filter( 'post_date_column_status', array( $this, 'post_date_column_status' ), 10, 4 );
		add_filter( 'display_post_states', array( $this, 'display_post_states' ), 10, 2 );

	}

	/**
	 * Render blank state.
	 *
	 * @see    Boldermail_List_Table::render_blank_state()
	 * @since  1.7.0
	 */
	protected function render_blank_state() {

		echo '<div class="boldermail-BlankState clearfix">';

		echo '<h2 class="boldermail-BlankState-message">' . esc_html__( 'Keep your subscribers engaged by sharing your latest news, promoting a line of products, or announcing an event.', 'boldermail' ) . '</h2>';

		echo '<div class="boldermail-BlankState-buttons">';

		echo '<a class="boldermail-BlankState-cta button-primary button" href="' . esc_url( admin_url( 'post-new.php?post_type=bm_newsletter' ) ) . '">' . esc_html__( 'Create a Newsletter', 'boldermail' ) . '</a>';
		echo '<a class="boldermail-BlankState-cta button" href="https://www.boldermail.com/knowledge-base/create-a-newsletter/" target="_blank">' . esc_html__( 'Learn More', 'boldermail' ) . '</a>';

		echo '</div>';

		echo '</div>';

	}

	/**
	 * Define custom columns.
	 *
	 * @see    Boldermail_List_Table::define_columns()
	 * @since  1.7.0
	 * @param  array $columns Columns data.
	 * @return array
	 */
	public function define_columns( $columns ) {

		$title  = $columns['title'];
		$author = $columns['author'];
		$date   = $columns['date'];

		unset( $columns );

		$columns['title']      = $title;
		$columns['author']     = $author;
		$columns['lists']      = __( 'List', 'boldermail' );
		$columns['status']     = __( 'Status', 'boldermail' );
		$columns['recipients'] = __( 'Recipients', 'boldermail' );
		$columns['opens']      = __( 'Unique Opens', 'boldermail' );
		$columns['clicks']     = __( 'Unique Clicks', 'boldermail' );
		$columns['date']       = $date;

		return $columns;

	}

	/**
	 * Filters the status text of the post.
	 *
	 * @since  1.7.0
	 * @param  string  $status      The status text.
	 * @param  WP_Post $post        Post object.
	 * @param  string  $column_name The column name.
	 * @param  string  $mode        The list display mode ('excerpt' or 'list').
	 * @return string
	 */
	public function post_date_column_status( $status, $post, $column_name, $mode ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed */

		if ( 'date' === $column_name && $post->post_type === $this->list_table_type ) {

			if ( in_array( $post->post_status, array( 'publish', 'sent' ), true ) ) {
				$status = __( 'Newsletter sent', 'boldermail' );
			} elseif ( in_array( $post->post_status, array( 'preparing', 'sending' ), true ) ) {
				$status = __( 'Newsletter created', 'boldermail' );
			}

		}

		return $status;

	}

	/**
	 * Filters the default post display states used in the posts list table.
	 *
	 * @since  1.7.0
	 * @param  string[] $post_states An array of post display states.
	 * @param  WP_Post  $post        The current post object.
	 * @return string[]
	 */
	public function display_post_states( $post_states, $post ) {

		if ( $parent_id = wp_get_post_parent_id( $post ) ) { /* phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found */
			$parent_post      = get_post( $parent_id );
			$parent_permalink = get_edit_post_link( $parent_post->ID );
			$parent_title     = $parent_post->post_title;

			$post_states['parent_post'] = '<span class="boldermail-post-state"><a href="' . esc_url( $parent_permalink ) . '">' . esc_html( $parent_title ) . '<span class="dashicons dashicons-rss"></span></a></span>';
		}

		return $post_states;

	}

	/**
	 * Get row actions to show in the newsletter list table.
	 *
	 * @see    Boldermail_Newsletters_List_Table::get_row_actions()
	 * @since  1.7.0
	 * @param  array   $actions An array of row action links.
	 * @param  WP_Post $post    Post object.
	 * @return array
	 */
	protected function get_row_actions( $actions, $post ) {

		$actions = parent::get_row_actions( $actions, $post );

		/* translators: %s: Newsletter post ID. */
		$actions['duplicate'] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>', esc_url( $this->get_duplicate_url( $this->object ) ), esc_attr( sprintf( __( 'Duplicate "%s"', 'boldermail' ), get_the_title( $this->object->get_post_id() ) ) ), esc_html( __( 'Duplicate', 'boldermail' ) ) );

		if ( $this->object->is_published() ) {
			/* translators: %s: Newsletter post ID. */
			$actions['report'] = sprintf( '<a href="%1$s" target="_blank" aria-label="%2$s">%3$s</a>', esc_url( $this->get_view_report_url( $this->object ) ), esc_attr( sprintf( __( 'View Report for "%s"', 'boldermail' ), get_the_title( $this->object->get_post_id() ) ) ), esc_html( __( 'View Report', 'boldermail' ) ) );
		}

		return $actions;

	}

	/**
	 * Render column content: status.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Regular $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_status_column_html( $newsletter ) {

		if ( ! $newsletter->is_published() ) {
			return '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No status', 'boldermail' ) . '</span>';
		}

		$status  = $newsletter->get_status();
		$to_send = $newsletter->get_to_send();

		$tip = '';

		if ( 'preparing' === $status ) {
			/* translators: %s: Number of recipients. */
			$tip = sprintf( __( 'Preparing to send your campaign to %s recipients.', 'boldermail' ), $to_send );
		} elseif ( 'sending' === $status ) {
			/* translators: %s: Number of recipients. */
			$tip = sprintf( __( 'Sending your campaign to %s recipients.', 'boldermail' ), $to_send );
		} elseif ( 'sent' === $status ) {
			/* translators: %s: Number of recipients. */
			$tip = sprintf( __( 'Your campaign was sent to %s recipients.', 'boldermail' ), $to_send );
		}

		return '<mark class="bm-list-table-mark status status-' . esc_attr( $status ) . ' boldermail-tips" data-tip="' . boldermail_sanitize_tooltip( $tip ) . '"><span>' . esc_html( boldermail_get_newsletter_status_name( $status ) ) . '</span></mark>';

	}

	/**
	 * Render column content: recipients.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Regular $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_recipients_column_html( $newsletter ) {

		if ( ! $newsletter->is_published() ) {
			return '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No recipients', 'boldermail' ) . '</span>';
		}

		$to_send    = $newsletter->get_to_send();
		$recipients = $newsletter->get_recipients();

		$percentage = ( $to_send ) ? $recipients / $to_send * 100 : 0;

		if ( $to_send === $recipients ) {
			return number_format( $recipients );
		} else {
			return number_format( $recipients ) . ' <mark class="bm-list-table-mark percentage"><span>' . number_format( $percentage, 2 ) . '%</span></mark><span class="bm-loading"></span>';
		}

	}

	/**
	 * Render column content: opens.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Regular $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_opens_column_html( $newsletter ) {

		$recipients = $newsletter->get_recipients();
		$status     = $newsletter->get_status();

		$opens_html = '';

		if ( ! $newsletter->is_published() || 'preparing' === $status ) {

			$opens_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No opens', 'boldermail' ) . '</span>';

		} elseif ( in_array( $status, array( 'sending', 'sent' ), true ) ) {

			$opens_unique     = $newsletter->get_unique_opens();
			$opens_percentage = ( $recipients ) ? $opens_unique / $recipients * 100 : 0;
			$opens_html       = number_format( $opens_unique ) . ' <mark class="bm-list-table-mark percentage"><span>' . number_format( $opens_percentage, 2 ) . '%</span></mark>';

		}

		return $opens_html;

	}

	/**
	 * Render column content: clicks.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Regular $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_clicks_column_html( $newsletter ) {

		$links      = $newsletter->get_clicks_data();
		$status     = $newsletter->get_status();
		$recipients = $newsletter->get_recipients();

		$clicks_html = '';

		if ( ! $newsletter->is_published() || 'preparing' === $status ) {

			$clicks_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No clicks', 'boldermail' ) . '</span>';

		} elseif ( in_array( $status, array( 'sending', 'sent' ), true ) ) {

			if ( empty( $links ) ) {

				$clicks_html = '<mark class="bm-list-table-mark percentage"><span>' . esc_html__( 'No links', 'boldermail' ) . '</span></mark>';

			} else {

				$clicks_unique     = $newsletter->get_unique_clicks();
				$clicks_percentage = ( $recipients ) ? $clicks_unique / $recipients * 100 : 0;
				$clicks_html       = number_format( $clicks_unique ) . ' <mark class="bm-list-table-mark percentage"><span>' . number_format( $clicks_percentage, 2 ) . '&#37;</span></mark>';

			}

		}

		return $clicks_html;

	}

	/**
	 * Get link to view the report for a regular newsletter.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Regular $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_view_report_url( $newsletter ) {

		$url = esc_url( boldermail_get_option( 'boldermail_url' ) . '/report' );
		$app = absint( boldermail_get_option( 'boldermail_app' ) );

		$url = add_query_arg( array( 'i' => $app ), $url );
		$url = add_query_arg( array( 'c' => $newsletter->get_campaign_id() ), $url );

		return $url;

	}

}
