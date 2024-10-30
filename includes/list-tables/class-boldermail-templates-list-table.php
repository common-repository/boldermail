<?php
/**
 * List tables: Templates.
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
 * Boldermail_Templates_List_Table class.
 *
 * @since 1.7.0
 */
class Boldermail_Templates_List_Table extends Boldermail_List_Table {

	/**
	 * Post type.
	 *
	 * @since 1.7.0
	 * @var   string
	 */
	protected $list_table_type = 'bm_template';

	/**
	 * Constructor.
	 *
	 * @since 2.3.0
	 */
	public function __construct() {

		parent::__construct();

		/**
		 * Remove Classic Editor plugin post states.
		 * We will add our own.
		 *
		 * @since 2.3.0
		 */
		remove_filter( 'display_post_states', array( 'Classic_Editor', 'add_post_state' ), 10 );
		add_filter( 'display_post_states', array( $this, 'display_post_states' ), 10, 2 );

	}

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
			$this->object = boldermail_get_template( $post_id );
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

		echo '<h2 class="boldermail-BlankState-message">' . esc_html__( 'Design your first email template.', 'boldermail' ) . '</h2>';

		echo '<div class="boldermail-BlankState-buttons">';

		echo '<a class="boldermail-BlankState-cta button-primary button" href="' . esc_url( admin_url( 'post-new.php?post_type=bm_template' ) ) . '">' . esc_html__( 'Create Template', 'boldermail' ) . '</a>';
		echo '<a class="boldermail-BlankState-cta button" href="https://www.boldermail.com/knowledge-base/customize-your-template/" target="_blank">' . esc_html__( 'Learn More', 'boldermail' ) . '</a>';

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

		$cb     = $columns['cb'];
		$title  = $columns['title'];
		$author = $columns['author'];
		$tags   = $columns['taxonomy-bm_template_tag'];
		$date   = $columns['date'];

		unset( $columns );

		$columns['cb']                       = $cb;
		$columns['thumbnail']                = '<span class="vers boldermail-grey-bubble dashicons-format-image" title="' . esc_attr__( 'Image', 'boldermail' ) . '"><span class="screen-reader-text">' . __( 'Image', 'boldermail' ) . '</span></span>';
		$columns['title']                    = $title;
		$columns['author']                   = $author;
		$columns['taxonomy-bm_template_tag'] = $tags;
		$columns['date']                     = $date;

		return $columns;

	}

	/**
	 * Filters the default post display states used in the posts list table.
	 *
	 * @since  1.7.0
	 * @param  string[] $post_states An array of post display states.
	 * @param  WP_Post  $post        The current post object.
	 * @return string[]
	 */
	public function display_post_states( $post_states, $post ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed */

		if ( $this->object->use_block_editor() ) {
			$post_states['use_block_editor'] = '<span class="boldermail-post-state">' . esc_html__( 'Block Editor', 'boldermail' ) . '</span>';
		} else {
			$post_states['use_block_editor'] = '<span class="boldermail-post-state">' . esc_html__( 'Classic Editor', 'boldermail' ) . '</span>';
		}

		return $post_states;

	}

	/**
	 * Render column: thumbnail.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	protected function render_thumbnail_column() {

		$post_id = $this->object->get_post_id();

		echo has_post_thumbnail( $post_id ) ? '<a href="' . esc_url( get_edit_post_link( $post_id ) ) . '">' . get_the_post_thumbnail( $post_id, 'thumbnail' ) . '</a>' : '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAALElEQVQYGWO8d+/efwYkoKioiMRjYGBC4WHhUK6A8T8QIJt8//59ZC493AAAQssKpBK4F5AAAAAASUVORK5CYII=" />';

	}

}
