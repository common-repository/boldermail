<?php
/**
 * List tables: Lists.
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
 * Boldermail_Lists_List_Table class.
 *
 * @since 1.7.0
 */
class Boldermail_Lists_List_Table extends Boldermail_List_Table {

	/**
	 * Post type.
	 *
	 * @since 1.7.0
	 * @var   string
	 */
	protected $list_table_type = 'bm_list';

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
			$this->object = boldermail_get_list( $post_id );
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

		echo '<h2 class="boldermail-BlankState-message">' . esc_html__( 'Ready to create a list and grow leads?', 'boldermail' ) . '</h2>';

		echo '<div class="boldermail-BlankState-buttons">';

		echo '<a class="boldermail-BlankState-cta button-primary button" href="' . esc_url( admin_url( 'post-new.php?post_type=bm_list' ) ) . '">' . esc_html__( 'Create List', 'boldermail' ) . '</a>';
		echo '<a class="boldermail-BlankState-cta button" href="https://www.boldermail.com/knowledge-base/add-new-list/" target="_blank">' . esc_html__( 'Learn More', 'boldermail' ) . '</a>';

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
	 * Filter the results for the search filters and sortable columns.
	 *
	 * @see    Boldermail_List_Table::query_filters()
	 * @note   If you don't check meta_query for empty (non-existent) values,
	 *         your column will show only the posts having (non-empty) meta value
	 *         until it will be sorted by another by default or another column.
	 * @since  1.7.0
	 * @param  WP_Query $query The WP_Query instance (passed by reference).
	 */
	protected function query_filters( $query ) {

		$orderby = $query->get( 'orderby' );

		$statuses = array( 'subscribed', 'unsubscribed', 'unconfirmed', 'bounced', 'complained' );

		$key = '';

		if ( 'subscribed' === $orderby ) {
			$key = '_subscribed';
		} elseif ( 'unsubscribed' === $orderby ) {
			$key = '_unsubscribed';
		} elseif ( 'unconfirmed' === $orderby ) {
			$key = '_unconfirmed';
		} elseif ( 'bounced' === $orderby ) {
			$key = '_bounced';
		} elseif ( 'complained' === $orderby ) {
			$key = '_complained';
		}

		if ( in_array( $orderby, $statuses, true ) && '' !== $key ) {

			$meta_query = array(
				'relation' => 'OR',
				array(
					'key'     => $key,
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => $key,
				),
			);

			$query->set( 'meta_query', $meta_query );
			$query->set( 'orderby', 'meta_value_num' );

		}

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

		$date  = $columns['date'];
		$title = $columns['title'];

		unset( $columns );

		$columns['title']          = $title;
		$columns['all']            = __( 'All', 'boldermail' );
		$columns['subscribed']     = __( 'Subscribed', 'boldermail' );
		$columns['unconfirmed']    = __( 'Unconfirmed', 'boldermail' );
		$columns['unsubscribed']   = __( 'Unsubscribed', 'boldermail' );
		$columns['bounced']        = __( 'Bounced', 'boldermail' );
		$columns['complained']     = __( 'Marked as Spam', 'boldermail' );
		$columns['autoresponders'] = '<span class="vers boldermail-grey-bubble dashicons-update" title="' . esc_attr__( 'Automations', 'boldermail' ) . '"><span class="screen-reader-text">' . __( 'Autoresponders', 'boldermail' ) . '</span></span>';
		$columns['date']           = $date;

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

		$columns['subscribed']   = 'subscribed';
		$columns['unconfirmed']  = 'unconfirmed';
		$columns['unsubscribed'] = 'unsubscribed';
		$columns['bounced']      = 'bounced';
		$columns['complained']   = 'complained';

		return $columns;

	}

	/**
	 * Render column: all.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_all_column() {

		$all = $this->object->get_subscribers_count( 'all' );

		$all_link = $this->get_view_subscribers_url( $this->object );

		printf(
			'<a href="%s" class="boldermail-tips" data-tip="%s">%s</a>',
			esc_url( $all_link ),
			boldermail_sanitize_tooltip( __( 'The total number of email addresses in this list.', 'boldermail' ) ),
			number_format( $all )
		);

	}

	/**
	 * Render column: subscribed.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_subscribed_column() {

		$all = $this->object->get_subscribers_count( 'all' );

		$subscribed            = $this->object->get_subscribers_count( 'subscribed' );
		$subscribed_percentage = ( $all ) ? $subscribed / $all * 100 : 0;
		$subscribed_link       = $this->get_view_subscribers_url( $this->object, 'subscribed' );

		printf(
			'<a href="%s" class="boldermail-tips" data-tip="%s">%s <mark class="bm-list-table-mark percentage"><span>%s&#37;</span></mark></a>',
			esc_url( $subscribed_link ),
			boldermail_sanitize_tooltip( __( 'The number of contacts actively subscribed and receiving your emails.', 'boldermail' ) ),
			number_format( $subscribed ),
			number_format( $subscribed_percentage, 2 )
		);

	}

	/**
	 * Render column: unconfirmed.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_unconfirmed_column() {

		$all = $this->object->get_subscribers_count( 'all' );

		$unconfirmed            = $this->object->get_subscribers_count( 'unconfirmed' );
		$unconfirmed_percentage = ( $all ) ? $unconfirmed / $all * 100 : 0;
		$unconfirmed_link       = $this->get_view_subscribers_url( $this->object, 'unconfirmed' );

		printf(
			'<a href="%s" class="boldermail-tips" data-tip="%s">%s <mark class="bm-list-table-mark percentage"><span>%s&#37;</span></mark></a>',
			esc_url( $unconfirmed_link ),
			boldermail_sanitize_tooltip( __( 'The number of contacts who have not yet confirmed their subscription (only applies to double opt-in lists).', 'boldermail' ) ),
			number_format( $unconfirmed ),
			number_format( $unconfirmed_percentage, 2 )
		);

	}

	/**
	 * Render column: unsubscribed.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_unsubscribed_column() {

		$all = $this->object->get_subscribers_count( 'all' );

		$unsubscribed            = $this->object->get_subscribers_count( 'unsubscribed' );
		$unsubscribed_percentage = ( $all ) ? $unsubscribed / $all * 100 : 0;
		$unsubscribed_link       = $this->get_view_subscribers_url( $this->object, 'unsubscribed' );

		printf(
			'<a href="%s" class="boldermail-tips" data-tip="%s">%s <mark class="bm-list-table-mark percentage"><span>%s&#37;</span></mark></a>',
			esc_url( $unsubscribed_link ),
			boldermail_sanitize_tooltip( __( 'The number of contacts who unsubscribed from your list.', 'boldermail' ) ),
			number_format( $unsubscribed ),
			number_format( $unsubscribed_percentage, 2 )
		);

	}

	/**
	 * Render column: bounced.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_bounced_column() {

		$all = $this->object->get_subscribers_count( 'all' );

		$bounced            = $this->object->get_subscribers_count( 'bounced' );
		$bounced_percentage = ( $all ) ? $bounced / $all * 100 : 0;
		$bounced_link       = $this->get_view_subscribers_url( $this->object, 'bounced' );

		printf(
			'<a href="%s" class="boldermail-tips" data-tip="%s">%s <mark class="bm-list-table-mark percentage"><span>%s&#37;</span></mark></a>',
			esc_url( $bounced_link ),
			boldermail_sanitize_tooltip( __( 'The number of contacts that bounced (i.e. email address does not exist anymore).', 'boldermail' ) ),
			number_format( $bounced ),
			number_format( $bounced_percentage, 2 )
		);

	}

	/**
	 * Render column: complained.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_complained_column() {

		$all = $this->object->get_subscribers_count( 'all' );

		$complained            = $this->object->get_subscribers_count( 'complained' );
		$complained_percentage = ( $all ) ? $complained / $all * 100 : 0;
		$complained_link       = $this->get_view_subscribers_url( $this->object, 'complained' );

		printf(
			'<a href="%s" class="boldermail-tips" data-tip="%s">%s <mark class="bm-list-table-mark percentage"><span>%s&#37;</span></mark></a>',
			esc_url( $complained_link ),
			boldermail_sanitize_tooltip( __( 'The number of contacts who marked your email as spam.', 'boldermail' ) ),
			number_format( $complained ),
			number_format( $complained_percentage, 2 )
		);

	}

	/**
	 * Render column: autoresponders.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_autoresponders_column() {

		$autoresponders = $this->object->get_autoresponders_count();

		$view_autoresponders_link = $this->get_view_autoresponders_url( $this->object );

		printf(
			'<a href="%s" class="boldermail-tips" data-tip="%s">%s</a>',
			esc_url( $view_autoresponders_link ),
			boldermail_sanitize_tooltip( __( 'The total number of autoresponders in this list.', 'boldermail' ) ),
			number_format( $autoresponders )
		);

	}

	/**
	 * Get row actions to show in the lists list table.
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

		/* translators: %s: List name. */
		$actions['add-subscriber'] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>', esc_url( $this->get_add_subscriber_url( $this->object ) ), esc_attr( sprintf( __( 'Add Subscriber to "%s"', 'boldermail' ), $this->object->get_name() ) ), esc_attr( __( 'Add Subscriber', 'boldermail' ) ) );
		$actions['edit']           = $edit;

		return $actions;

	}

	/**
	 * Get a link to add subscribers to a list.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_List $list List object.
	 * @return string
	 */
	public static function get_add_subscriber_url( $list ) {

		$list_post_id = $list->get_post_id();

		$add_subscriber_url = admin_url( 'post-new.php?post_type=bm_subscriber' );
		$add_subscriber_url = add_query_arg( array( 'list' => $list_post_id ), $add_subscriber_url );

		return $add_subscriber_url;

	}

	/**
	 * Get a link to import subscribers to a list.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_List $list List object.
	 * @return string
	 */
	public static function get_import_subscribers_url( $list ) {

		$list_post_id = $list->get_post_id();

		$import_subscribers_url = add_query_arg( array( 'action' => 'import_subscribers' ), get_edit_post_link() );
		$import_subscribers_url = wp_nonce_url( $import_subscribers_url, "import_subscribers-list_{$list_post_id}" );

		return $import_subscribers_url;

	}

	/**
	 * Get a link to add autoresponders to a list.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_List $list List object.
	 * @return string
	 */
	public static function get_add_autoresponder_url( $list ) {

		$list_post_id = $list->get_post_id();

		$add_autoresponder_url = admin_url( 'post-new.php?post_type=bm_autoresponder' );
		$add_autoresponder_url = add_query_arg( array( 'list' => $list_post_id ), $add_autoresponder_url );

		return $add_autoresponder_url;

	}

	/**
	 * Get a link to view the autoresponders of a list.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_List $list List object.
	 * @return string
	 */
	public static function get_view_autoresponders_url( $list ) {

		$list_post_id = $list->get_post_id();

		$view_autoresponders_url = admin_url( 'edit.php?post_type=bm_autoresponder' );
		$view_autoresponders_url = add_query_arg( array( 'list' => $list_post_id ), $view_autoresponders_url );

		return $view_autoresponders_url;

	}

	/**
	 * Get a link to view the subscribers of a list.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_List $list   List object.
	 * @param  string          $status Subscriber status.
	 * @return string
	 */
	public static function get_view_subscribers_url( $list, $status = '' ) {

		$list_post_id = $list->get_post_id();

		$view_subscribers_url = admin_url( 'edit.php?post_type=bm_subscriber' );
		$view_subscribers_url = add_query_arg( array( 'list' => $list_post_id ), $view_subscribers_url );

		if ( $status ) {
			$view_subscribers_url = add_query_arg( array( 'post_status' => $status ), $view_subscribers_url );
		}

		return $view_subscribers_url;

	}

}
