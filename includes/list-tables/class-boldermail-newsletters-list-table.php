<?php
/**
 * List tables: Newsletters.
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
 * Boldermail_Newsletters_List_Table class.
 *
 * @since 1.7.0
 */
abstract class Boldermail_Newsletters_List_Table extends Boldermail_List_Table {

	/**
	 * Pre-fetch any data for the row each column has access to it.
	 *
	 * @since  1.7.0
	 * @param  int $post_id Post ID being shown.
	 * @return void
	 */
	protected function prepare_row_data( $post_id ) {

		if ( empty( $this->object ) || $this->object->get_post_id() !== $post_id ) {
			$this->object = boldermail_get_newsletter( $post_id );
		}

	}

	/**
	 * Define bulk actions.
	 *
	 * We cannot have `trash` as a bulk action for newsletters.
	 * `edit.php` loops over all posts selected and calls `wp_trash_post`.
	 * This, in turn, calls our transition hooks in `newsletter_regular_transition`.
	 * Those hooks redirect to either the newsletter `post.php` page or
	 * `edit.php` page after each transition. There is no way to redirect
	 * only after all transitions have occurred. For that reason, we
	 * can only delete one newsletter at a time.
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
	 * Modify the items per page option.
	 *
	 * @since  1.3.0
	 * @param  mixed   $result Value for the user's option.
	 * @param  string  $option Name of the option being retrieved.
	 * @param  WP_User $user   WP_User object of the user whose option is being retrieved.
	 * @return int
	 */
	public function edit_posts_per_page( $result, $option, $user ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed */

		if ( $result > 10 ) {
			return 10;
		}

		return $result;

	}

	/**
	 * Sorting by filters on the table.
	 *
	 * @see    Boldermail_List_Table::render_filters()
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_filters() {

		$lists   = boldermail_get_lists();
		$list_id = isset( $_GET['list'] ) ? boldermail_sanitize_key( $_GET['list'] ) : 0; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */

		?>
		<label for="filter-by-list" class="screen-reader-text"><?php esc_html_e( 'Filter by list', 'boldermail' ); ?></label>
		<select name="list" id="filter-by-list">
			<option <?php selected( $list_id, 0 ); ?> value="0"><?php esc_html_e( 'All lists', 'boldermail' ); ?></option>
			<?php
			foreach ( $lists as $list ) {
				printf(
					"<option %s value='%s'>%s</option>\n",
					selected( $list_id, $list->get_list_id(), false ),
					esc_attr( $list->get_list_id() ),
					esc_html( $list->get_name() )
				);
			}
			?>
		</select>
		<?php

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

		if ( $list_id = isset( $_GET['list'] ) ? boldermail_sanitize_key( $_GET['list'] ) : '' ) { /* phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.CodeAnalysis.AssignmentInCondition.Found */

			// @see https://wordpress.stackexchange.com/a/55359
			$list_query = array(
				'relation' => 'AND',
				array(
					'key'     => '_list_id',
					'value'   => $list_id,
					'compare' => 'LIKE',
				),
			);

			$query->query_vars['meta_query'] = isset( $query->query_vars['meta_query'] ) ? array_merge( $list_query, $query->query_vars['meta_query'] ) : $list_query; /* phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query */

		}

		if ( $newsletter_post_id = isset( $_GET['post_parent'] ) ? boldermail_sanitize_int( $_GET['post_parent'] ) : '' ) { /* phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.CodeAnalysis.AssignmentInCondition.Found */
			$query->query_vars['post_parent'] = $newsletter_post_id;
		}

		$orderby = $query->get( 'orderby' );

		if ( 'recipients' === $orderby ) {

			$meta_query = array(
				'relation' => 'OR',
				array(
					'key'     => '_recipients',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => '_recipients',
				),
			);

			$query->set( 'meta_query', $meta_query );
			$query->set( 'orderby', 'meta_value_num' );

		}

		if ( 'opens' === $orderby ) {

			$meta_query = array(
				'relation' => 'OR',
				array(
					'key'     => '_unique_opens',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => '_unique_opens',
				),
			);

			$query->set( 'meta_query', $meta_query );
			$query->set( 'orderby', 'meta_value_num' );

		}

		if ( 'clicks' === $orderby ) {

			$meta_query = array(
				'relation' => 'OR',
				array(
					'key'     => '_unique_clicks',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => '_unique_clicks',
				),
			);

			$query->set( 'meta_query', $meta_query );
			$query->set( 'orderby', 'meta_value_num' );

		}

		return $query;

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

		$columns['recipients'] = 'recipients';
		$columns['opens']      = 'opens';
		$columns['clicks']     = 'clicks';

		return $columns;

	}

	/**
	 * Render column: lists.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_lists_column() {

		echo wp_kses_post( $this->get_lists_column_html( $this->object ) );

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
	 * Render column: recipients.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_recipients_column() {

		echo wp_kses_post( $this->get_recipients_column_html( $this->object ) );

	}

	/**
	 * Render column: opens.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_opens_column() {

		echo wp_kses_post( $this->get_opens_column_html( $this->object ) );

	}

	/**
	 * Render column: clicks.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_clicks_column() {

		echo wp_kses_post( $this->get_clicks_column_html( $this->object ) );

	}

	/**
	 * Get row actions to show in the list table.
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
		$view  = $actions['view'];

		unset( $actions );

		$actions['edit'] = $edit;

		if ( $this->object->is_editable() && $this->object->use_block_editor() ) {
			$actions['edit-block-template'] = sprintf(
				'<a href="%1$s" aria-label="%2$s">%3$s</a>',
				esc_url( $this->object->get_edit_block_template_link() ),
				/* translators: %s: Newsletter title. */
				esc_attr( sprintf( __( 'Edit "%s"', 'boldermail' ), get_the_title( $this->object->get_post_id() ) ) ),
				esc_attr( __( 'Edit Block Template', 'boldermail' ) )
			);
		}

		$actions['trash'] = $trash;
		$actions['view']  = $view;

		return $actions;

	}

	/**
	 * Render column content: lists.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_lists_column_html( $newsletter ) {

		$list_ids = $newsletter->get_list_id();

		$lists = array();
		foreach ( $list_ids as $list_id ) {
			if ( $list = boldermail_get_list_from_id( $list_id ) ) { /* phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found */
				$lists[] = $list;
			}
		}

		// If the newsletter is a draft, the user may not have picked a list yet.
		// Or if the list was deleted, if may not exist anymore.
		if ( empty( $lists ) ) {

			$lists_html = '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No lists', 'boldermail' ) . '</span>';

		} else {

			$list_links = array();

			foreach ( $lists as $list ) {
				$list_links[] = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_edit_post_link( $list->get_post_id() ) ), esc_html( $list->get_name() ) );
			}

			$lists_html = implode( ', ', $list_links );

		}

		return $lists_html;

	}

	/**
	 * Get link to duplicate a newsletter.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_duplicate_url( $newsletter ) {

		$newsletter_edit_link = get_edit_post_link( $newsletter->get_post_id() );

		$duplicate_link = add_query_arg( array( 'action' => 'duplicate' ), $newsletter_edit_link );
		$duplicate_link = wp_nonce_url( $duplicate_link, "duplicate_{$newsletter->get_post_id()}" );

		return $duplicate_link;

	}

	/**
	 * Get link to pause a newsletter.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter $newsletter Newsletter object.
	 * @return string
	 */
	public static function get_pause_url( $newsletter ) {

		$newsletter_edit_link = get_edit_post_link( $newsletter->get_post_id() );

		$pause_link = add_query_arg( array( 'action' => 'pause' ), $newsletter_edit_link );
		$pause_link = wp_nonce_url( $pause_link, "pause_{$newsletter->get_post_id()}" );

		return $pause_link;

	}

}
