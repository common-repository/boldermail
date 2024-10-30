<?php
/**
 * List tables: Autoresponders.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.7.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

class_exists( 'Boldermail_List_Table' ) || require_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/abstract-class-boldermail-list-table.php';

/**
 * Boldermail_Autoresponders_List_Table class.
 *
 * @since 1.7.0
 */
class Boldermail_Autoresponders_List_Table extends Boldermail_List_Table {

	/**
	 * Post type.
	 *
	 * @since 1.7.0
	 * @var   string
	 */
	protected $list_table_type = 'bm_autoresponder';

	/**
	 * Pre-fetch any data for the row each column has access to it.
	 *
	 * @see    Boldermail_List_Table::prepare_row_data()
	 * @since  1.7.0
	 * @param  int $post_id Post ID being shown.
	 */
	protected function prepare_row_data( $post_id ) {

		if ( empty( $this->object ) || $this->object->get_post_id() !== $post_id ) {
			$this->object = boldermail_get_autoresponder( $post_id );
		}

	}

	/**
	 * Render blank state.
	 *
	 * @see    Boldermail_List_Table::render_blank_state()
	 * @since  1.7.0
	 */
	protected function render_blank_state() {

		echo '<div class="boldermail-BlankState clearfix">';

		echo '<h2 class="boldermail-BlankState-message">' . esc_html__( 'Automate your emails to keep your subscribers engaged.', 'boldermail' ) . '</h2>';

		echo '<div class="boldermail-BlankState-buttons">';

		echo '<a class="boldermail-BlankState-cta button-primary button" href="' . esc_url( admin_url( 'post-new.php?post_type=bm_autoresponder' ) ) . '">' . esc_html__( 'Create Automation', 'boldermail' ) . '</a>';

		echo '</div>';

		echo '</div>';

	}

	/**
	 * Define bulk actions.
	 *
	 * @see    Boldermail_List_Table::define_bulk_actions()
	 * @since  1.7.0
	 * @param  array $actions An array of bulk action options.
	 * @return array
	 */
	public function define_bulk_actions( $actions ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass */

		return array();

	}

	/**
	 * Sorting by filters on the table.
	 *
	 * @see    Boldermail_List_Table::render_filters()
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_filters() {

		global $wpdb;

		$lists = array_map(
			'boldermail_get_list',
			$wpdb->get_col( /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching */
				"SELECT DISTINCT {$wpdb->posts}.post_parent FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_type = 'bm_autoresponder' AND {$wpdb->posts}.post_status = 'publish' ORDER BY {$wpdb->posts}.post_date DESC"
			)
		);

		$list_query = isset( $_GET['list'] ) ? boldermail_sanitize_int( $_GET['list'] ) : ''; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */

		if ( $lists ) {
			?>
			<label for="filter-by-list" class="screen-reader-text"><?php esc_html_e( 'Filter by list', 'boldermail' ); ?></label>
			<select name="list" id="filter-by-list">
				<option<?php selected( $list_query, 0 ); ?> value="0"><?php esc_html_e( 'All lists', 'boldermail' ); ?></option>
				<?php
				foreach ( $lists as $list ) {
					printf(
						"<option %s value='%s'>%s</option>\n",
						selected( $list_query, $list->get_post_id(), false ),
						esc_attr( $list->get_post_id() ),
						esc_html( $list->get_name() )
					);
				}
				?>
			</select>
			<?php
		}

	}

	/**
	 * Filter the results for the search filters and sortable columns.
	 *
	 * @see    Boldermail_List_Table::query_filters()
	 * @since  1.7.0
	 * @param  WP_Query $query The WP_Query instance (passed by reference).
	 * @return WP_Query        Modified WP_Query instance.
	 */
	protected function query_filters( $query ) {

		if ( $list_post_id = isset( $_GET['list'] ) ? boldermail_sanitize_int( $_GET['list'] ) : '' ) { /* phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.CodeAnalysis.AssignmentInCondition.Found */
			$query->query_vars['post_parent'] = $list_post_id;
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

		$title = $columns['title'];
		$date  = $columns['date'];

		unset( $columns );

		$columns['title']  = $title;
		$columns['list']   = __( 'List', 'boldermail' );
		$columns['type']   = __( 'Type', 'boldermail' );
		$columns['emails'] = __( 'Emails', 'boldermail' );
		$columns['sent']   = __( 'Sent', 'boldermail' );
		$columns['opens']  = __( 'Unique Opens', 'boldermail' );
		$columns['clicks'] = __( 'Unique Clicks', 'boldermail' );
		$columns['date']   = $date;

		return $columns;

	}

	/**
	 * Render column: list.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_list_column() {

		$list_post_id = $this->object->get_list_post_id();

		$list = boldermail_get_list( $list_post_id );

		echo ( $list ) ? sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_edit_post_link( $list_post_id ) ), esc_html( $list->get_name() ) ) : '';

	}

	/**
	 * Render column: type.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_type_column() {

		echo wp_kses_post( boldermail_get_autoresponder_type_name( $this->object->get_type() ) );

	}

	/**
	 * Render column: emails.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_emails_column() {

		if ( 'publish' === $this->object->get_status() ) {

			$newsletters = $this->object->get_newsletters();

			echo sprintf(
				'<a href="%s" class="boldermail-tips" data-tip="%s">%s</a>',
				esc_url( $this->get_view_emails_url( $this->object ) ),
				boldermail_sanitize_tooltip( __( 'The total number of automated emails in this autoresponder.', 'boldermail' ) ),
				number_format( count( $newsletters ) )
			);

		} else {
			echo '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No emails', 'boldermail' ) . '</span>';
		}

	}

	/**
	 * Render column: sent.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_sent_column() {

		if ( 'publish' === $this->object->get_status() ) {

			$newsletters = $this->object->get_newsletters();

			$no_newsletters = true;

			$recipients_total = 0;

			foreach ( $newsletters as $newsletter ) {

				if ( $newsletter->is_published() ) {

					$no_newsletters = false;

					$recipients_total = $recipients_total + $newsletter->get_recipients();

				}

			}

			if ( $no_newsletters ) {
				$sent_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No automated emails', 'boldermail' ) . '</span>';
			} else {
				$sent_html = number_format( $recipients_total );
			}

			echo wp_kses_post( $sent_html );

		} else {
			echo '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No recipients', 'boldermail' ) . '</span>';
		}

	}

	/**
	 * Render column: opens.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_opens_column() {

		if ( 'publish' === $this->object->get_status() ) {

			$newsletters = $this->object->get_newsletters();

			$no_newsletters = true;

			$opens_unique_total = 0;
			$recipients_total   = 0;

			foreach ( $newsletters as $newsletter ) {

				if ( $newsletter->is_published() ) {

					$no_newsletters = false;

					$opens_unique_total = $opens_unique_total + $newsletter->get_unique_opens();
					$recipients_total   = $recipients_total + $newsletter->get_recipients();

				}

			}

			if ( $no_newsletters ) {
				$clicks_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No automated emails', 'boldermail' ) . '</span>';
			} else {
				$clicks_html = number_format( $opens_unique_total ) . ' <mark class="bm-list-table-mark percentage"><span>' . number_format( ( $recipients_total ) ? $opens_unique_total / $recipients_total * 100 : 0, 2 ) . '&#37;</span></mark>';
			}

			echo wp_kses_post( $clicks_html );

		} else {
			echo '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No opens', 'boldermail' ) . '</span>';
		}

	}

	/**
	 * Render column: clicks.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_clicks_column() {

		if ( 'publish' === $this->object->get_status() ) {

			$newsletters = $this->object->get_newsletters();

			$no_newsletters = true;
			$empty_links    = true;

			$clicks_unique_total = 0;
			$recipients_total    = 0;

			foreach ( $newsletters as $newsletter ) {

				if ( $newsletter->is_published() && ( time() - get_post_time( 'U', true, $newsletter->get_post_id() ) > MINUTE_IN_SECONDS ) ) {

					$no_newsletters = false;

					if ( ! empty( $newsletter->get_clicks_data() ) ) {

						$empty_links = false;

						$clicks_unique_total = $clicks_unique_total + $newsletter->get_unique_clicks();
						$recipients_total    = $recipients_total + $newsletter->get_recipients();

					}

				}

			}

			if ( $no_newsletters ) {

				$clicks_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No automated emails', 'boldermail' ) . '</span>';

			} else {

				if ( $empty_links ) {
					$clicks_html = '<mark class="bm-list-table-mark percentage"><span>' . esc_html__( 'No links', 'boldermail' ) . '</span></mark>';
				} else {
					$clicks_html = number_format( $clicks_unique_total ) . ' <mark class="bm-list-table-mark percentage"><span>' . number_format( ( $recipients_total ) ? $clicks_unique_total / $recipients_total * 100 : 0, 2 ) . '&#37;</span></mark>';
				}

			}

			echo wp_kses_post( $clicks_html );

		} else {
			echo '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No clicks', 'boldermail' ) . '</span>';
		}

	}

	/**
	 * Get row actions to show in the autoresponder list table.
	 *
	 * @see    Boldermail_List_Table::get_row_actions()
	 * @since  1.7.0
	 * @param  array   $actions An array of row action links.
	 * @param  WP_Post $post    Post object.
	 * @return array
	 */
	protected function get_row_actions( $actions, $post ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed */

		$edit = $actions['edit'];

		unset( $actions );

		$actions['add-email'] = sprintf(
			'<a href="%1$s" aria-label="%2$s">%3$s</a>',
			esc_url( $this->get_add_email_url( $this->object ) ),
			/* translators: %s: Subscriber's name. */
			esc_attr( sprintf( __( 'Add Email to "%s"', 'boldermail' ), $this->object->get_name() ) ),
			esc_attr( __( 'Add Email', 'boldermail' ) )
		);
		$actions['view-emails'] = sprintf(
			'<a href="%1$s" aria-label="%2$s">%3$s</a>',
			esc_url( $this->get_view_emails_url( $this->object ) ),
			/* translators: %s: Subscriber's name. */
			esc_attr( sprintf( __( 'View Emails from "%s"', 'boldermail' ), $this->object->get_name() ) ),
			esc_attr( __( 'View Emails', 'boldermail' ) )
		);
		$actions['edit'] = $edit;

		return $actions;

	}

	/**
	 * Get a link to add an Autoresponder Email to an Autoresponder.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Autoresponder $autoresponder Autoresponder object.
	 * @return string
	 */
	public static function get_add_email_url( $autoresponder ) {

		$autoresponder_post_id = $autoresponder->get_post_id();

		$add_email_url = admin_url( 'post-new.php?post_type=bm_newsletter_ares' );
		$add_email_url = add_query_arg( array( 'autoresponder' => $autoresponder_post_id ), $add_email_url );

		return $add_email_url;

	}

	/**
	 * Get a link to view the automated emails in an automation.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Autoresponder $autoresponder Autoresponder object.
	 * @return string
	 */
	public static function get_view_emails_url( $autoresponder ) {

		$autoresponder_post_id = $autoresponder->get_post_id();

		$view_newsletter_ares_url = admin_url( 'edit.php?post_type=bm_newsletter_ares' );
		$view_newsletter_ares_url = add_query_arg( array( 'autoresponder' => $autoresponder_post_id ), $view_newsletter_ares_url );

		return $view_newsletter_ares_url;

	}

}
