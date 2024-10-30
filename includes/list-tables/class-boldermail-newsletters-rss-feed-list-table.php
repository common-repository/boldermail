<?php
/**
 * List tables: RSS Feed Newsletters.
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
 * Boldermail_Newsletters_RSS_Feed_List_Table class.
 *
 * @since 1.7.0
 */
final class Boldermail_Newsletters_RSS_Feed_List_Table extends Boldermail_Newsletters_List_Table {

	/**
	 * Post type.
	 *
	 * @since 1.7.0
	 * @var   string
	 */
	protected $list_table_type = 'bm_newsletter_rss';

	/**
	 * Render blank state.
	 *
	 * @see    Boldermail_List_Table::render_blank_state()
	 * @since  1.7.0
	 */
	protected function render_blank_state() {

		echo '<div class="boldermail-BlankState clearfix">';

		echo '<h2 class="boldermail-BlankState-message">' . esc_html__( 'Help your subscribers keep up with your blog by sending new posts straight to their inboxes.', 'boldermail' ) . '</h2>';

		echo '<div class="boldermail-BlankState-buttons">';

		echo '<a class="boldermail-BlankState-cta button-primary button" href="' . esc_url( admin_url( 'post-new.php?post_type=bm_newsletter_rss' ) ) . '">' . esc_html__( 'Create an RSS Campaign', 'boldermail' ) . '</a>';

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

		$columns['title']     = $title;
		$columns['author']    = $author;
		$columns['lists']     = __( 'List', 'boldermail' );
		$columns['status']    = __( 'Status', 'boldermail' );
		$columns['emails']    = __( 'Emails', 'boldermail' );
		$columns['opens']     = __( 'Unique Opens', 'boldermail' );
		$columns['clicks']    = __( 'Unique Clicks', 'boldermail' );
		$columns['timestamp'] = __( 'Next Campaign', 'boldermail' );
		$columns['date']      = $date;

		return $columns;

	}

	/**
	 * Define custom sortable columns.
	 *
	 * @see    Boldermail_List_Table::define_columns()
	 * @since  1.7.0
	 * @param  array $columns Columns data.
	 * @return array
	 */
	public function define_sortable_columns( $columns ) {

		$columns['timestamp'] = 'timestamp';

		return $columns;

	}

	/**
	 * Define hidden columns.
	 *
	 * @since  1.7.0
	 * @return array
	 */
	protected function define_hidden_columns() {

		return array( 'date' );

	}

	/**
	 * Render column: emails.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_emails_column() {

		echo wp_kses_post( $this->get_emails_column_html( $this->object ) );

	}

	/**
	 * Render column: timestamp.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_timestamp_column() {

		echo wp_kses_post( $this->get_timestamp_column_html( $this->object ) );

	}

	/**
	 * Render column content: status.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_status_column_html( $newsletter ) {

		$status = $newsletter->get_status();

		if ( 'enabled' === $status ) {
			$tip = __( 'RSS campaign enabled and sending.', 'boldermail' );
		} elseif ( 'paused' === $status ) {
			$tip = __( 'RSS campaign paused and not sending.', 'boldermail' );
		} else {
			return '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No status', 'boldermail' ) . '</span>';
		}

		return '<mark class="bm-list-table-mark status status-' . esc_attr( $status ) . ' boldermail-tips" data-tip="' . boldermail_sanitize_tooltip( $tip ) . '"><span>' . esc_html( boldermail_get_newsletter_status_name( $status ) ) . '</span></mark>';

	}

	/**
	 * Render column content: emails.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_RSS_Feed $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_emails_column_html( $newsletter ) {

		if ( in_array( $newsletter->get_status(), array( 'publish', 'enabled', 'paused' ), true ) ) {

			$newsletters = $newsletter->get_newsletters();

			$email_html = sprintf(
				'<a href="%s" class="boldermail-tips" data-tip="%s">%s</a>',
				esc_url( self::get_view_emails_url( $newsletter ) ),
				boldermail_sanitize_tooltip( __( 'The total number of automated emails sent with this RSS campaign.', 'boldermail' ) ),
				number_format( count( $newsletters ) )
			);

		} else {
			$email_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No emails', 'boldermail' ) . '</span>';
		}

		return $email_html;

	}

	/**
	 * Render column content: recipients.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_RSS_Feed $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_recipients_column_html( $newsletter ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass */

		return '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'Not implemented', 'boldermail' ) . '</span>';

	}

	/**
	 * Render column content: opens.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_RSS_Feed $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_opens_column_html( $newsletter ) {

		if ( $newsletter->is_published() ) {

			$rss_newsletters = $newsletter->get_newsletters();

			$no_newsletters = true;

			$opens_unique_total = 0;
			$recipients_total   = 0;

			foreach ( $rss_newsletters as $rss_newsletter ) {

				if ( $rss_newsletter->is_published() ) {

					$no_newsletters = false;

					$opens_unique_total = $opens_unique_total + $rss_newsletter->get_unique_opens();
					$recipients_total   = $recipients_total + $rss_newsletter->get_recipients();

				}

			}

			if ( $no_newsletters ) {
				$opens_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No RSS campaign emails', 'boldermail' ) . '</span>';
			} else {
				$opens_html = number_format( $opens_unique_total ) . ' <mark class="bm-list-table-mark percentage"><span>' . number_format( ( $recipients_total ) ? $opens_unique_total / $recipients_total * 100 : 0, 2 ) . '&#37;</span></mark>';
			}

		} else {
			$opens_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No opens', 'boldermail' ) . '</span>';
		}

		return $opens_html;

	}

	/**
	 * Render column content: clicks.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_RSS_Feed $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_clicks_column_html( $newsletter ) {

		if ( $newsletter->is_published() ) {

			$rss_newsletters = $newsletter->get_newsletters();

			$no_newsletters = true;
			$empty_links    = true;

			$clicks_unique_total = 0;
			$recipients_total    = 0;

			foreach ( $rss_newsletters as $rss_newsletter ) {

				if ( $rss_newsletter->is_published() && ( time() - get_post_time( 'U', true, $rss_newsletter->get_post_id() ) > MINUTE_IN_SECONDS ) ) {

					$no_newsletters = false;

					if ( ! empty( $rss_newsletter->get_clicks_data() ) ) {

						$empty_links = false;

						$clicks_unique_total = $clicks_unique_total + $rss_newsletter->get_unique_clicks();
						$recipients_total    = $recipients_total + $rss_newsletter->get_recipients();

					}

				}

			}

			if ( $no_newsletters ) {

				$clicks_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No RSS campaign emails', 'boldermail' ) . '</span>';

			} else {

				if ( $empty_links ) {
					$clicks_html = '<mark class="bm-list-table-mark percentage"><span>' . esc_html__( 'No links', 'boldermail' ) . '</span></mark>';
				} else {
					$clicks_html = number_format( $clicks_unique_total ) . ' <mark class="bm-list-table-mark percentage"><span>' . number_format( ( $recipients_total ) ? $clicks_unique_total / $recipients_total * 100 : 0, 2 ) . '&#37;</span></mark>';
				}

			}

		} else {
			$clicks_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No clicks', 'boldermail' ) . '</span>';
		}

		return $clicks_html;

	}

	/**
	 * Render column content: timestamp.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_RSS_Feed $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_timestamp_column_html( $newsletter ) {

		if ( $timestamp = wp_next_scheduled( 'boldermail_scheduled_newsletter_rss_feed', array( $newsletter->get_post_id() ) ) ) { /* phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found */

			try {

				$dt = new DateTime( '@' . $timestamp );

				if ( $timezone = get_option( 'timezone_string' ) ) { /* phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found */
					$dt->setTimeZone( new DateTimeZone( $timezone ) );
				}

				$t_time    = $dt->format( 'Y/m/d g:i:s a' );
				$time_diff = time() - $timestamp;

				if ( $timestamp && $time_diff > 0 && $time_diff < DAY_IN_SECONDS ) {
					/* translators: %s: Human-readable time difference. */
					$h_time = sprintf( __( '%s ago', 'boldermail' ), human_time_diff( $timestamp ) );
				} else {
					$h_time = $dt->format( 'Y/m/d' );
				}

				$timestamp_html = __( 'Scheduled', 'boldermail' ) . '<br /><span title="' . $t_time . '">' . $h_time . '</span>';

			} catch ( Exception $e ) {
				$timestamp_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'Unable to check for next scheduled campaign', 'boldermail' ) . '</span>';
			}

		} else {
			$timestamp_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No scheduled campaigns', 'boldermail' ) . '</span>';
		}

		return $timestamp_html;

	}

	/**
	 * Get a link to view the automated emails sent with an RSS campaign.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_RSS_Feed $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_view_emails_url( $newsletter ) {

		$newsletter_post_id = $newsletter->get_post_id();

		$view_newsletter_rss_url = admin_url( 'edit.php?post_type=bm_newsletter' );
		$view_newsletter_rss_url = add_query_arg( array( 'post_parent' => $newsletter_post_id ), $view_newsletter_rss_url );

		return $view_newsletter_rss_url;

	}

}
