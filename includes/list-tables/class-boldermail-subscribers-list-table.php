<?php
/**
 * List tables: Subscribers.
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
 * Boldermail_Subscribers_List_Table class.
 *
 * @since 1.7.0
 */
class Boldermail_Subscribers_List_Table extends Boldermail_List_Table {

	/**
	 * Post type.
	 *
	 * @since 1.7.0
	 * @var   string
	 */
	protected $list_table_type = 'bm_subscriber';

	/**
	 * Pre-fetch any data for the row each column has access to it.
	 *
	 * @see    Boldermail_List_Table::prepare_row_data()
	 * @since  1.7.0
	 * @param  int $post_id Post ID being shown.
	 * @return void
	 */
	protected function prepare_row_data( $post_id ) {

		if ( empty( $this->object ) || $this->object->get_post_id() !== $post_id ) {
			$this->object = boldermail_get_subscriber( $post_id );
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

		echo '<h2 class="boldermail-BlankState-message">' . esc_html__( 'Add your first subscriber to your newsletter list.', 'boldermail' ) . '</h2>';

		echo '<div class="boldermail-BlankState-buttons">';

		echo '<a class="boldermail-BlankState-cta button-primary button" href="' . esc_url( admin_url( 'post-new.php?post_type=bm_subscriber' ) ) . '">' . esc_html__( 'Add Subscriber', 'boldermail' ) . '</a>';
		echo '<a class="boldermail-BlankState-cta button" href="https://www.boldermail.com/knowledge-base/add-a-subscriber/" target="_blank">' . esc_html__( 'Learn More', 'boldermail' ) . '</a>';

		echo '</div>';

		echo '</div>';

	}

	/**
	 * Change the "Add New" URL when the list ID is provided.
	 *
	 * @see    Boldermail_List_Table::get_post_new_admin_url()
	 * @since  1.7.0
	 * @param  string   $url     The complete admin area URL including scheme and path.
	 * @param  string   $path    Path relative to the admin area URL. Blank string if no path is specified.
	 * @param  int|null $blog_id Site ID, or null for the current site.
	 * @return string            Admin area URL.
	 */
	protected function get_post_new_admin_url( $url, $path, $blog_id ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed */

		$list_post_id = isset( $_GET['list'] ) ? boldermail_sanitize_int( $_GET['list'] ) : ''; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */

		if ( $list_post_id && $list = boldermail_get_list( $list_post_id ) ) { /* phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found */
			include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-lists-list-table.php';
			return Boldermail_Lists_List_Table::get_add_subscriber_url( $list );
		}

		return $url;

	}

	/**
	 * Remove "mine" as long as the subscriber post type does not support author.
	 *
	 * @see    Boldermail_List_Table::define_views()
	 * @since  1.7.0
	 * @param  array $views Views data.
	 * @return array
	 */
	public function define_views( $views ) {

		if ( ! post_type_supports( $this->list_table_type, 'author' ) ) {
			unset( $views['mine'] );
		}

		return $views;

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

		/**
		 * This query:
		 * `SELECT * FROM wp_posts  WHERE 1=1  AND post_type = 'bm_list' AND post_status = 'publish' ORDER BY post_date DESC`
		 * to get all lists is a lot cheaper than using:
		 * `SELECT DISTINCT pm.meta_value FROM wp_postmeta pm LEFT JOIN wp_posts p ON p.ID = pm.post_id WHERE pm.meta_key = "_list" AND p.post_status = "publish" AND p.post_type = "bm_subscriber" ORDER BY "_list"`
		 * or the query that WP uses for dates:
		 * `SELECT DISTINCT post_parent FROM wp_posts WHERE post_type = 'bm_subscriber' AND ((post_status = 'publish' OR post_status = 'subscribed' OR post_status = 'unsubscribed' OR post_status = 'unconfirmed' OR post_status = 'bounced' OR post_status = 'complained')) ORDER BY post_date DESC`
		 * The first one takes approximate 0.01-0.02 seconds, while the second
		 * one takes 0.6-1.4 seconds, and the third one takes 1.3-1.7 seconds.
		 * These numbers were computed assuming a list of ~100,000 subscribers.
		 *
		 * The only disadvantage to using the first query is that the filter
		 * will show even if there are no subscribers.
		 *
		 * @since 1.3.0
		 */
		$lists = boldermail_get_lists();

		$list_query = isset( $_GET['list'] ) ? boldermail_sanitize_text( $_GET['list'] ) : ''; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */

		if ( $lists ) {
			?>
		<label for="filter-by-list" class="screen-reader-text"><?php esc_html_e( 'Filter by list', 'boldermail' ); ?></label>
		<select name="list" id="filter-by-list">
			<option<?php selected( $list_query, 0 ); ?> value="0"><?php esc_html_e( 'All lists', 'boldermail' ); ?></option>
			<?php foreach ( $lists as $list ) : ?>

				<?php
				printf(
					'<option %s value="%s">%s</option>\n',
					selected( $list_query, $list->get_post_id(), false ),
					esc_attr( $list->get_post_id() ),
					esc_html( $list->get_name() )
				);
				?>

			<?php endforeach; ?>
		</select>
			<?php
		}

	}

	/**
	 * Filter the results for the search filters and sortable columns.
	 *
	 * @see    Boldermail_List_Table::query_filters()
	 * @note   If you don't check meta_query for empty (non-existent) values,
	 *         your column will show only the posts having (non-empty) meta value
	 *         until it will be sorted by another by default or another column.
	 * @since  1.7.0
	 * @param  WP_Query $query The WP_Query instance (passed by reference).
	 * @return WP_Query
	 */
	protected function query_filters( $query ) {

		if ( $list_post_id = isset( $_GET['list'] ) ? boldermail_sanitize_int( $_GET['list'] ) : '' ) { /* phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.CodeAnalysis.AssignmentInCondition.Found */
			$query->query_vars['post_parent'] = $list_post_id;
		}

		$orderby = $query->get( 'orderby' );

		if ( 'email' === $orderby ) {
			$query->set( 'orderby', 'title' );
		}

		if ( 'last_activity' === $orderby ) {

			$meta_query = array(
				'relation' => 'OR',
				array(
					'key'     => '_last_activity',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => '_last_activity',
				),
			);

			$query->set( 'meta_query', $meta_query );
			$query->set( 'orderby', 'meta_value_num' );

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

		$date = $columns['date'];

		unset( $columns );

		$columns['email']     = __( 'Email', 'boldermail' );
		$columns['name']      = __( 'Name', 'boldermail' );
		$columns['last_name'] = __( 'Last Name', 'boldermail' );
		$columns['list']      = __( 'List', 'boldermail' );
		$columns['status']    = __( 'Status', 'boldermail' );
		$columns['date']      = $date;
		$columns['timestamp'] = __( 'Last Activity', 'boldermail' );

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

		$columns['email']     = 'email';
		$columns['timestamp'] = 'last_activity';

		return $columns;

	}

	/**
	 * Define the name of the primary column for the subscribers list table.
	 *
	 * @see    Boldermail_List_Table::get_primary_column()
	 * @since  1.7.0
	 * @return string
	 */
	protected function get_primary_column() {

		return 'email';

	}

	/**
	 * Render column: email.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_email_column() {

		echo get_avatar( $this->object->get_email(), 32 );
		printf( '<strong><a href="%1$s">%2$s</a></strong>', esc_url( get_edit_post_link( $this->object->get_post_id() ) ), esc_html( $this->object->get_email() ) );

	}

	/**
	 * Render column: name.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_name_column() {

		echo esc_html( $this->object->get_name() );

	}

	/**
	 * Render column: last name.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_last_name_column() {

		echo esc_html( $this->object->get_last_name() );

	}

	/**
	 * Render column: list.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_list_column() {

		$list_post_id = absint( $this->object->get_list_post_id() );

		$list = boldermail_get_list( $list_post_id );

		echo ( $list ) ? sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_edit_post_link( $list_post_id ) ), esc_html( $list->get_name() ) ) : '';

	}

	/**
	 * Render column: status.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_status_column() {

		echo wp_kses_post( $this->get_status_column_html( $this->object ) );

	}

	/**
	 * Render column: timestamp.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_timestamp_column() {

		$timestamp = absint( $this->object->get_last_activity() );

		try {
			$dt = new DateTime( '@' . $timestamp );

			if ( $timezone = get_option( 'timezone_string' ) ) { /* phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found */
				$dt->setTimeZone( new DateTimeZone( $timezone ) );
			}

			$t_time = $dt->format( 'Y/m/d g:i:s a' );

			$time_diff = time() - $timestamp;

			if ( $timestamp && $time_diff > 0 && $time_diff < DAY_IN_SECONDS ) {
				/* translators: %s: Human-readable time difference. */
				$h_time = sprintf( __( '%s ago', 'boldermail' ), human_time_diff( $timestamp ) );
			} else {
				$h_time = $dt->format( 'Y/m/d' );
			}

			echo wp_kses_post( __( 'Updated', 'boldermail' ) . '<br /><span title="' . $t_time . '">' . $h_time . '</span>' );
		} catch ( Exception $e ) {
			echo '';
		}

	}

	/**
	 * Get row actions to show in the subscribers list table.
	 *
	 * @see    Boldermail_List_Table::get_row_actions()
	 * @since  1.7.0
	 * @param  array   $actions An array of row action links.
	 * @param  WP_Post $post    Post object.
	 * @return array
	 */
	protected function get_row_actions( $actions, $post ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed */

		$edit  = $actions['edit'];
		$trash = $actions['trash'];

		unset( $actions );

		$actions['edit'] = $edit;

		// If subscribed, show unsubscribe link.
		if ( $this->object->get_status() === 'subscribed' ) {
			$actions['unsubscribe'] = sprintf(
				'<a href="%1$s" aria-label="%2$s">%3$s</a>',
				esc_url( $this->get_unsubscribe_link( $this->object ) ),
				/* translators: %s: Email address. */
				esc_attr( sprintf( _x( 'Unsubscribe &ldquo;%s&rdquo;', 'email address', 'boldermail' ), $this->object->get_email() ) ),
				esc_html__( 'Unsubscribe', 'boldermail' )
			);
		}

		// If unsubscribed, show resubscribe link.
		if ( $this->object->get_status() === 'unsubscribed' ) {
			$actions['resubscribe'] = sprintf(
				'<a href="%1$s" aria-label="%2$s">%3$s</a>',
				esc_url( $this->get_resubscribe_link( $this->object ) ),
				/* translators: %s: Email address. */
				esc_attr( sprintf( _x( 'Resubscribe &ldquo;%s&rdquo;', 'email address', 'boldermail' ), $this->object->get_email() ) ),
				esc_html__( 'Resubscribe', 'boldermail' )
			);
		}

		$actions['trash'] = $trash;

		return $actions;

	}

	/**
	 * Get the HTML for the status column in the admin table.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Subscriber $subscriber Subscriber object.
	 * @return string
	 */
	public static function get_status_column_html( $subscriber ) {

		$status = $subscriber->get_status();

		$status_name = boldermail_get_subscriber_status_name( $status );

		if ( $status_name ) {
			return '<mark class="bm-list-table-mark status status-' . esc_attr( $status ) . '"><span>' . esc_html( $status_name ) . '</span></mark>';
		} else {
			return '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No status', 'boldermail' ) . '</span>';
		}

	}

	/**
	 * Get link to unsubscribe a contact.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Subscriber $subscriber Subscriber object.
	 * @return string
	 */
	public static function get_unsubscribe_link( $subscriber ) {

		$subscribe_edit_link = get_edit_post_link( $subscriber->get_post_id() );

		$unsubscribe_link = add_query_arg( array( 'action' => 'unsubscribe' ), $subscribe_edit_link );
		$unsubscribe_link = wp_nonce_url( $unsubscribe_link, "unsubscribe_{$subscriber->get_post_id()}" );

		return $unsubscribe_link;

	}

	/**
	 * Get link to resubscribe a contact.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Subscriber $subscriber Subscriber object.
	 * @return string
	 */
	public static function get_resubscribe_link( $subscriber ) {

		$subscribe_edit_link = get_edit_post_link( $subscriber->get_post_id() );

		$resubscribe_link = add_query_arg( array( 'action' => 'resubscribe' ), $subscribe_edit_link );
		$resubscribe_link = wp_nonce_url( $resubscribe_link, "resubscribe_{$subscriber->get_post_id()}" );

		return $resubscribe_link;

	}

}
