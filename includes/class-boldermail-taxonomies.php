<?php
/**
 * Register taxonomies.
 *
 * @link       https://thepostmansknock.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernán Villanueva <chvillanuevap@gmail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Taxonomies class.
 *
 * @since 1.0.0
 */
class Boldermail_Taxonomies {

	/**
	 * Initialize the hooks.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function init() {

		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 10 );

	}

	/**
	 * Register newsletter taxonomies.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function register_taxonomies() {

		if ( ! is_blog_installed() ) {
			return;
		}

		if ( taxonomy_exists( 'bm_template_tag' ) ) {
			return;
		}

		do_action( 'boldermail_register_taxonomy' );

		register_taxonomy(
			'bm_template_tag',
			apply_filters( 'boldermail_taxonomy_objects_template_tag', array( 'bm_template' ) ),
			apply_filters(
				'boldermail_taxonomy_args_template_tag',
				array(
					'public'            => false,
					'show_ui'           => true,
					'show_in_menu'      => false,
					'show_tagcloud'     => false,
					'show_admin_column' => true,
					'show_in_rest'      => true,
					'hierarchical'      => false,
					'rewrite'           => false,
					'query_var'         => is_admin(),
					'label'             => __( 'Tags', 'boldermail' ),
					'labels'            => array(
						'name'                       => __( 'Tags', 'boldermail' ),
						'singular_name'              => __( 'Tag', 'boldermail' ),
						'menu_name'                  => _x( 'Tags', 'Admin menu name', 'boldermail' ),
						'search_items'               => __( 'Search tags', 'boldermail' ),
						'all_items'                  => __( 'All tags', 'boldermail' ),
						'edit_item'                  => __( 'Edit tag', 'boldermail' ),
						'update_item'                => __( 'Update tag', 'boldermail' ),
						'add_new_item'               => __( 'Add new tag', 'boldermail' ),
						'new_item_name'              => __( 'New tag name', 'boldermail' ),
						'popular_items'              => __( 'Popular tags', 'boldermail' ),
						'separate_items_with_commas' => __( 'Separate tags with commas', 'boldermail' ),
						'add_or_remove_items'        => __( 'Add or remove tags', 'boldermail' ),
						'choose_from_most_used'      => __( 'Choose from the most used tags', 'boldermail' ),
						'not_found'                  => __( 'No tags found', 'boldermail' ),
					),
				)
			)
		);

		do_action( 'boldermail_after_register_taxonomy' );

	}

}

Boldermail_Taxonomies::init();
