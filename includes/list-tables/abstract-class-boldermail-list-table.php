<?php /* phpcs:ignore WordPress.Files.FileName.InvalidClassFileName */
/**
 * List tables.
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
 * Boldermail_List_Table class.
 *
 * @since 1.7.0
 */
abstract class Boldermail_List_Table {

	/**
	 * Post type.
	 *
	 * @since 1.7.0
	 * @var   string
	 */
	protected $list_table_type = '';

	/**
	 * Object being shown on the row.
	 *
	 * @since 1.7.0
	 * @var   Boldermail_Autoresponder|Boldermail_Block_Template|Boldermail_List|Boldermail_Newsletter_Autoresponder|Boldermail_Newsletter_Regular|Boldermail_Newsletter_RSS_Feed|Boldermail_Subscriber|Boldermail_Template|null $object Newsletter, Template, List, Subscriber, or Autoresponder instance.
	 */
	protected $object = null;

	/**
	 * Constructor.
	 *
	 * @since 1.7.0
	 */
	public function __construct() {

		if ( $this->list_table_type ) {

			add_action( 'manage_posts_extra_tablenav', array( $this, 'maybe_render_blank_state' ) );
			add_filter( 'view_mode_post_types', array( $this, 'disable_view_mode' ) );
			add_filter( 'admin_url', array( $this, 'post_new_admin_url' ), 10, 3 );
			add_filter( 'views_edit' . $this->list_table_type, array( $this, 'define_views' ) );
			add_filter( 'bulk_actions-edit-' . $this->list_table_type, array( $this, 'define_bulk_actions' ) );
			add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ) );
			add_filter( 'parse_query', array( $this, 'request_query' ) );
			add_filter( 'manage_edit-' . $this->list_table_type . '_sortable_columns', array( $this, 'define_sortable_columns' ) );
			add_filter( 'manage_' . $this->list_table_type . '_posts_columns', array( $this, 'define_columns' ) );
			add_filter( 'default_hidden_columns', array( $this, 'default_hidden_columns' ), 10, 2 );
			add_filter( 'list_table_primary_column', array( $this, 'list_table_primary_column' ), 10, 2 );
			add_action( 'manage_' . $this->list_table_type . '_posts_custom_column', array( $this, 'render_columns' ), 10, 2 );
			add_filter( 'post_row_actions', array( $this, 'row_actions' ), 100, 2 );

		}

	}

	/**
	 * Pre-fetch any data for the row each column has access to it.
	 *
	 * @since  1.7.0
	 * @param  int $post_id Post ID being shown.
	 * @return void
	 */
	protected function prepare_row_data( $post_id ) {}

	/**
	 * Show blank slate.
	 *
	 * @since  1.7.0
	 * @param  string $which String which tablenav is being shown.
	 * @return void
	 */
	public function maybe_render_blank_state( $which ) {

		global $post_type;

		if ( $post_type === $this->list_table_type && 'bottom' === $which ) {

			$counts = (array) wp_count_posts( $post_type );
			unset( $counts['auto-draft'] );
			$count = array_sum( $counts );

			if ( 0 < $count ) {
				return;
			}

			$this->render_blank_state();

			echo '<style type="text/css">#posts-filter .wp-list-table, #posts-filter .tablenav.top, .tablenav.bottom .actions, .wrap .subsubsub  { display: none; } #posts-filter .tablenav.bottom { height: auto; } </style>';

		}

	}

	/**
	 * Render blank state. Extend to add content.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	abstract protected function render_blank_state();

	/**
	 * Removes this type from list of post types that support "View Mode" switching.
	 * View mode is seen on posts where you can switch between list or excerpt. Our post types don't support
	 * it, so we want to hide the useless UI from the screen options tab.
	 *
	 * @since  1.7.0
	 * @param  array $post_types Array of post types supporting view mode.
	 * @return array             Array of post types supporting view mode, without this type.
	 */
	public function disable_view_mode( $post_types ) {

		unset( $post_types[ $this->list_table_type ] );

		return $post_types;

	}

	/**
	 * Change the "Add New" URL in the list tables.
	 *
	 * @since  1.7.0
	 * @param  string   $url     The complete admin area URL including scheme and path.
	 * @param  string   $path    Path relative to the admin area URL. Blank string if no path is specified.
	 * @param  int|null $blog_id Site ID, or null for the current site.
	 * @return string            Admin area URL.
	 */
	public function post_new_admin_url( $url, $path, $blog_id ) {

		remove_filter( 'admin_url', array( $this, 'post_new_admin_url' ), 10 );

		if ( "post-new.php?post_type={$this->list_table_type}" === $path ) {
			$url = esc_url( $this->get_post_new_admin_url( $url, $path, $blog_id ) );
		}

		add_filter( 'admin_url', array( $this, 'post_new_admin_url' ), 10, 3 );

		return $url;

	}

	/**
	 * Get the "Add New" URL for each list table.
	 *
	 * @since  1.7.0
	 * @param  string   $url     The complete admin area URL including scheme and path.
	 * @param  string   $path    Path relative to the admin area URL. Blank string if no path is specified.
	 * @param  int|null $blog_id Site ID, or null for the current site.
	 * @return string            Admin area URL.
	 */
	protected function get_post_new_admin_url( $url, $path, $blog_id ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed */

		return $url;

	}

	/**
	 * Filter the list of available list table views (All, Mine, Published, Draft, Trash, etc.).
	 *
	 * @since  1.7.0
	 * @param  array $views Views data.
	 * @return array
	 */
	public function define_views( $views ) {

		return $views;

	}

	/**
	 * Define bulk actions.
	 *
	 * @since  1.0.0
	 * @param  array $actions An array of bulk action options.
	 * @return array
	 */
	public function define_bulk_actions( $actions ) {

		return $actions;

	}

	/**
	 * See if we should render search filters or not.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public function restrict_manage_posts() {

		global $typenow;

		if ( $this->list_table_type === $typenow ) {
			$this->render_filters();
		}

	}

	/**
	 * Filter the results for the search filters and sortable columns.
	 *
	 * @since  1.7.0
	 * @param  WP_Query $query The WP_Query instance (passed by reference).
	 * @return WP_Query        Modified WP_Query instance.
	 */
	public function request_query( $query ) {

		global $typenow;

		if ( is_admin() && $query->is_main_query() && $this->list_table_type === $typenow ) {
			return $this->query_filters( $query );
		}

		return $query;

	}

	/**
	 * Render any custom filters and search inputs for the list table.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_filters() {}

	/**
	 * Filter the results for the search filters and sortable columns.
	 *
	 * @since  1.2.3
	 * @param  WP_Query $query The WP_Query instance (passed by reference).
	 * @return WP_Query        Modified WP_Query instance.
	 */
	protected function query_filters( $query ) {

		return $query;

	}

	/**
	 * Define custom columns.
	 *
	 * @see    https://make.wordpress.org/docs/plugin-developer-handbook/10-plugin-components/custom-list-table-columns/
	 * @since  1.3.0
	 * @param  array $columns Columns data.
	 * @return array
	 */
	public function define_columns( $columns ) {

		return $columns;

	}

	/**
	 * Define custom sortable columns.
	 *
	 * @see    https://make.wordpress.org/docs/plugin-developer-handbook/10-plugin-components/custom-list-table-columns/
	 * @since  1.7.0
	 * @param  array $columns Columns data.
	 * @return array
	 */
	public function define_sortable_columns( $columns ) {

		return $columns;

	}

	/**
	 * Adjust which columns are displayed by default.
	 *
	 * @since  1.7.0
	 * @param  array     $hidden Current hidden columns.
	 * @param  WP_Screen $screen Current screen.
	 * @return array
	 */
	public function default_hidden_columns( $hidden, $screen ) {

		if ( isset( $screen->id ) && 'edit-' . $this->list_table_type === $screen->id ) {
			$hidden = array_merge( $hidden, $this->define_hidden_columns() );
		}

		return $hidden;

	}

	/**
	 * Define hidden columns.
	 *
	 * @since  1.7.0
	 * @return array
	 */
	protected function define_hidden_columns() {

		return array();

	}

	/**
	 * Set list table primary column.
	 *
	 * @since  1.7.0
	 * @param  string $default   Column name default for the specific list table.
	 * @param  string $screen_id Screen ID for specific list table.
	 * @return string
	 */
	public function list_table_primary_column( $default, $screen_id ) {

		if ( 'edit-' . $this->list_table_type === $screen_id && $this->get_primary_column() ) {
			return $this->get_primary_column();
		}

		return $default;

	}

	/**
	 * Define primary column for the current list table.
	 *
	 * @since  1.7.0
	 * @return string
	 */
	protected function get_primary_column() {

		return '';

	}

	/**
	 * Render individual columns.
	 *
	 * @see   https://developer.wordpress.org/reference/hooks/manage_posts_custom_column/
	 * @since 1.7.0
	 * @param string $column  The name of the column to display.
	 * @param int    $post_id The current post ID.
	 */
	public function render_columns( $column, $post_id ) {

		$this->prepare_row_data( $post_id );

		if ( ! $this->object ) {
			return;
		}

		if ( is_callable( array( $this, 'render_' . $column . '_column' ) ) ) {
			$this->{"render_{$column}_column"}();
		}

	}

	/**
	 * Filters the array of row action links on the admin table.
	 *
	 * @since  1.7.0
	 * @param  array   $actions An array of row action links.
	 * @param  WP_Post $post    Post object.
	 * @return array
	 */
	public function row_actions( $actions, $post ) {

		$this->prepare_row_data( $post->ID );

		if ( ! $this->object ) {
			return $actions;
		}

		if ( $this->list_table_type === $post->post_type ) {
			return $this->get_row_actions( $actions, $post );
		}

		return $actions;

	}

	/**
	 * Get row actions to show in the list table.
	 *
	 * @since  1.7.0
	 * @param  array   $actions An array of row action links.
	 * @param  WP_Post $post    Post object.
	 * @return array
	 */
	protected function get_row_actions( $actions, $post ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed */

		return $actions;

	}

}
