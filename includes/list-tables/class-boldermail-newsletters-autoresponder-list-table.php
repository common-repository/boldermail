<?php
/**
 * List tables: Automated Newsletters.
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
 * Boldermail_Newsletters_Autoresponder_List_Table class.
 *
 * @since 1.7.0
 */
final class Boldermail_Newsletters_Autoresponder_List_Table extends Boldermail_Newsletters_List_Table {

	/**
	 * Post type.
	 *
	 * @since 1.7.0
	 * @var   string
	 */
	protected $list_table_type = 'bm_newsletter_ares';

	/**
	 * Render blank state.
	 *
	 * @see    Boldermail_List_Table::render_blank_state()
	 * @since  1.7.0
	 */
	protected function render_blank_state() {}

	/**
	 * Sorting by filters on the table.
	 *
	 * @see    Boldermail_List_Table::render_filters()
	 * @since  1.7.0
	 */
	protected function render_filters() {

		global $wpdb;

		$autoresponders = array_map(
			'boldermail_get_autoresponder',
			$wpdb->get_col( /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching */
				"SELECT DISTINCT {$wpdb->posts}.post_parent FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_type = 'bm_newsletter_ares' AND ({$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'enabled' OR {$wpdb->posts}.post_status = 'paused') ORDER BY {$wpdb->posts}.post_date DESC"
			)
		);

		$autoresponder_query = isset( $_GET['autoresponder'] ) ? boldermail_sanitize_int( $_GET['autoresponder'] ) : ''; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */

		if ( $autoresponders ) {
			?>
		<label for="filter-by-autoresponder" class="screen-reader-text"><?php esc_html_e( 'Filter by autoresponder', 'boldermail' ); ?></label>
		<select name="autoresponder" id="filter-by-autoresponder">
			<option <?php selected( $autoresponder_query, 0 ); ?> value="0"><?php esc_html_e( 'All autoresponders', 'boldermail' ); ?></option>
			<?php
			foreach ( $autoresponders as $autoresponder ) {
				if ( $autoresponder ) {
					printf(
						"<option %s value='%s'>%s</option>\n",
						selected( $autoresponder_query, $autoresponder->get_post_id(), false ),
						esc_attr( $autoresponder->get_post_id() ),
						esc_html( $autoresponder->get_name() )
					);
				}
			}
			?>
		</select>
			<?php
		}

	}

	/**
	 * Filter the results for the `render_filters` function.
	 *
	 * @see    Boldermail_Newsletters_List_Table::query_filters()
	 * @since  1.2.3
	 * @param  WP_Query $query The WP_Query instance (passed by reference).
	 * @return WP_Query        Modified WP_Query instance.
	 */
	protected function query_filters( $query ) {

		$query = parent::query_filters( $query );

		if ( $autoresponder_post_id = isset( $_GET['autoresponder'] ) ? boldermail_sanitize_int( $_GET['autoresponder'] ) : '' ) { /* phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found, WordPress.Security.NonceVerification.Recommended */
			$query->query_vars['post_parent'] = $autoresponder_post_id;
		}

		return $query;

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

		unset( $columns );

		$columns['title']         = $title;
		$columns['author']        = $author;
		$columns['autoresponder'] = __( 'Automation', 'boldermail' );
		$columns['lists']         = __( 'List', 'boldermail' );
		$columns['status']        = __( 'Status', 'boldermail' );
		$columns['recipients']    = __( 'Recipients', 'boldermail' );
		$columns['opens']         = __( 'Unique Opens', 'boldermail' );
		$columns['clicks']        = __( 'Unique Clicks', 'boldermail' );
		$columns['trigger']       = __( 'Sends', 'boldermail' );

		return $columns;

	}

	/**
	 * Render column: autoresponder.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_autoresponder_column() {

		echo wp_kses_post( $this->get_autoresponder_column_html( $this->object ) );

	}

	/**
	 * Render column: trigger.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_trigger_column() {

		echo wp_kses_post( $this->get_trigger_column_html( $this->object ) );

	}

	/**
	 * Get row actions to show in the automated email list table.
	 *
	 * @see    Boldermail_Newsletters_List_Table::get_row_actions()
	 * @since  1.7.0
	 * @param  array   $actions An array of row action links.
	 * @param  WP_Post $post    Post object.
	 * @return array
	 */
	protected function get_row_actions( $actions, $post ) {

		$actions = parent::get_row_actions( $actions, $post );

		$actions['duplicate'] = sprintf(
			'<a href="%1$s" aria-label="%2$s">%3$s</a>',
			esc_url( $this->get_duplicate_url( $this->object ) ),
			/* translators: %s: Newsletter title. */
			esc_attr( sprintf( __( 'Duplicate "%s"', 'boldermail' ), get_the_title( $this->object->get_post_id() ) ) ),
			esc_html( __( 'Duplicate', 'boldermail' ) )
		);

		if ( $this->object->is_enabled() ) {
			$actions['pause'] = sprintf(
				'<a href="%1$s" aria-label="%2$s">%3$s</a>',
				esc_url( $this->get_pause_url( $this->object ) ),
				/* translators: %s: Newsletter title. */
				esc_attr( sprintf( __( 'Pause "%s"', 'boldermail' ), get_the_title( $this->object->get_post_id() ) ) ),
				esc_html( __( 'Pause', 'boldermail' ) )
			);
		}

		if ( $this->object->is_published() ) {
			$actions['report'] = sprintf(
				'<a href="%1$s" target="_blank" aria-label="%2$s">%3$s</a>',
				esc_url( $this->get_view_report_url( $this->object ) ),
				/* translators: %s: Newsletter title. */
				esc_attr( sprintf( __( 'View Report for "%s"', 'boldermail' ), get_the_title( $this->object->get_post_id() ) ) ),
				esc_html( __( 'View Report', 'boldermail' ) )
			);
		}

		return $actions;

	}

	/**
	 * Render column content: autoresponder.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Autoresponder $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_autoresponder_column_html( $newsletter ) {

		$autoresponder = $newsletter->get_autoresponder();

		if ( $autoresponder ) {
			return sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_edit_post_link( $autoresponder->get_post_id() ) ), esc_html( $autoresponder->get_name() ) );
		} else {
			return '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No autoresponder', 'boldermail' ) . '</span>';
		}

	}

	/**
	 * Render column content: lists.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Autoresponder $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_lists_column_html( $newsletter ) {

		$autoresponder = $newsletter->get_autoresponder();

		if ( $autoresponder ) {

			$list = boldermail_get_list( $autoresponder->get_list_post_id() );

			if ( $list ) {
				return sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_edit_post_link( $list->get_post_id() ) ), esc_html( $list->get_name() ) );
			} else {
				return '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No list', 'boldermail' ) . '</span>';
			}
		} else {
			return '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No autoresponder', 'boldermail' ) . '</span>';
		}

	}

	/**
	 * Render column content: status.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Autoresponder $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_status_column_html( $newsletter ) {

		if ( ! $newsletter->is_published() ) {
			return '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No status', 'boldermail' ) . '</span>';
		}

		$status = $newsletter->get_status();

		$tip = '';

		if ( 'enabled' === $status ) {
			$tip = __( 'Automated email enabled and sending.', 'boldermail' );
		} elseif ( 'paused' === $status ) {
			$tip = __( 'Automated email paused and not sending.', 'boldermail' );
		}

		return '<mark class="bm-list-table-mark status status-' . esc_attr( $status ) . ' boldermail-tips" data-tip="' . boldermail_sanitize_tooltip( $tip ) . '"><span>' . esc_html( boldermail_get_newsletter_status_name( $status ) ) . '</span></mark>';

	}

	/**
	 * Render column content: recipients.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Autoresponder $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_recipients_column_html( $newsletter ) {

		if ( ! $newsletter->is_published() ) {
			return '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No recipients', 'boldermail' ) . '</span>';
		}

		return number_format( $newsletter->get_recipients() );

	}

	/**
	 * Render column content: opens.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Autoresponder $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_opens_column_html( $newsletter ) {

		if ( ! $newsletter->is_published() ) {

			$opens_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No opens', 'boldermail' ) . '</span>';

		} else {

			$recipients = $newsletter->get_recipients();

			$opens_unique     = $newsletter->get_unique_opens();
			$opens_percentage = ( $recipients ) ? $opens_unique / $recipients * 100 : 0;

			$opens_html = number_format( $opens_unique ) . ' <mark class="bm-list-table-mark percentage"><span>' . number_format( $opens_percentage, 2 ) . '%</span></mark>';

		}

		return $opens_html;

	}

	/**
	 * Render column content: clicks.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Autoresponder $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_clicks_column_html( $newsletter ) {

		if ( ! $newsletter->is_published() ) {

			$clicks_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No clicks', 'boldermail' ) . '</span>';

		} else {

			/**
			 * Sendy does not compute the links in the automated email until
			 * the Cron job fires up on the minute after the email is published.
			 * Until then, even if the automated email contains links, no links
			 * show up in the `links` tables.
			 *
			 * @since   1.4.0
			 */
			if ( time() - get_post_time( 'U', true, $newsletter->get_post_id() ) < MINUTE_IN_SECONDS ) {

				$clicks_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No clicks', 'boldermail' ) . '</span>';

			} else {

				$links = $newsletter->get_clicks_data();

				if ( empty( $links ) ) {

					$clicks_html = '<mark class="bm-list-table-mark percentage"><span>No links</span></mark>';

				} else {

					$recipients = $newsletter->get_recipients();

					$clicks_unique     = $newsletter->get_unique_clicks();
					$clicks_percentage = ( $recipients ) ? $clicks_unique / $recipients * 100 : 0;

					$clicks_html = number_format( $clicks_unique ) . ' <mark class="bm-list-table-mark percentage"><span>' . number_format( $clicks_percentage, 2 ) . '&#37;</span></mark>';

				}

			}

		}

		return $clicks_html;

	}

	/**
	 * Render column content: trigger.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Autoresponder $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_trigger_column_html( $newsletter ) {

		if ( ! $newsletter->is_published() ) {
			return '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No status', 'boldermail' ) . '</span>';
		}

		$number      = $newsletter->get_trigger_number();
		$interval    = $newsletter->get_trigger_interval();
		$beforeafter = $newsletter->get_trigger_beforeafter();

		$trigger_column_html = '';

		if ( 'immediately' === $interval ) {
			$trigger_column_html .= '<span>' . esc_html( $interval ) . ' ' . esc_html( $beforeafter ) . ' ';
		} else {
			$trigger_column_html .= '<span>' . $number . ' ' . esc_html( $interval ) . ' ' . esc_html( $beforeafter ) . ' ';
		}

		$trigger_column_html .= '<br><strong>signup</strong></span>';

		return $trigger_column_html;

	}

	/**
	 * Get link to view the report for an automated newsletter.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Autoresponder $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_view_report_url( $newsletter ) {

		$url = esc_url( boldermail_get_option( 'boldermail_url' ) . '/autoresponders-report.php' );
		$app = absint( boldermail_get_option( 'boldermail_app' ) );

		$url = add_query_arg( array( 'i' => $app ), $url );
		$url = add_query_arg( array( 'a' => $newsletter->get_autoresponder_id() ), $url );
		$url = add_query_arg( array( 'ae' => $newsletter->get_ares_email_id() ), $url );

		return $url;

	}

}
