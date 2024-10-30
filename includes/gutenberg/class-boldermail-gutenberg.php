<?php
/**
 * Gutenberg blocks and templates.
 *
 * @link       https://www.boldermail.com/about/
 * @since      2.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Gutenberg class.
 *
 * @since 2.0.0
 */
class Boldermail_Gutenberg {

	/**
	 * Initialize the hooks.
	 *
	 * @since  2.0.0
	 * @return void
	 */
	public static function init() {

		self::includes();

		add_filter( 'block_categories', array( __CLASS__, 'block_categories' ), 10, 2 );
		add_action( 'init', array( __CLASS__, 'register_post_meta' ), PHP_INT_MAX );
		add_action( 'init', array( __CLASS__, 'editor_styles' ), PHP_INT_MAX );
		add_action( 'init', array( __CLASS__, 'allowed_block_patterns' ), PHP_INT_MAX, 2 );
		add_filter( 'block_editor_settings', array( __CLASS__, 'block_editor_settings' ), PHP_INT_MAX, 2 );
		add_filter( 'use_block_editor_for_post', array( __CLASS__, 'use_block_editor_for_post' ), PHP_INT_MAX, 2 );
		add_filter( 'use_block_editor_for_post_type', array( __CLASS__, 'use_block_editor_for_post_type' ), PHP_INT_MAX, 2 );
		add_filter( 'gutenberg_can_edit_post_type', array( __CLASS__, 'use_block_editor_for_post_type' ), 10, 2 );
		add_filter( 'image_size_names_choose', array( __CLASS__, 'image_size_names_choose' ), PHP_INT_MAX );

	}

	/**
	 * Register the block render callback functions.
	 *
	 * @since  2.3.0
	 * @return void
	 */
	private static function includes() {

		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/gutenberg/src/blocks/embed/index.php';

	}

	/**
	 * Filter the settings to pass to the block editor.
	 *
	 * @see    https://stackoverflow.com/a/58783057/1991500
	 * @since  2.0.0
	 * @param  array   $settings Default editor settings.
	 * @param  WP_Post $post     Post object.
	 * @return array             Modified editor settings.
	 */
	public static function block_editor_settings( $settings, $post ) {

		if ( ! in_array( $post->post_type, [ 'bm_block_template', 'bm_template' ], true ) ) {
			return $settings;
		}

		// Allow `wide` block alignments.
		$settings['alignWide'] = true;

		// Remove default styles.
		if ( isset( $settings['styles'][0]['css'] ) ) {
			$settings['styles'][0]['css'] = '';
		}

		return $settings;

	}

	/**
	 * Remove editor styles enqueued by the main theme, and then enqueue our
	 * block assets.
	 *
	 * @since  2.0.0
	 * @return void
	 */
	public static function editor_styles() {

		if ( in_array( boldermail_get_current_screen_post_type(), [ 'bm_block_template', 'bm_template' ], true ) ) {

			// Clear all styles and customizations made by other plugins or themes.
			remove_editor_styles();
			remove_all_actions( 'enqueue_block_editor_assets' );
			remove_theme_support( 'editor-color-palette' );
			remove_theme_support( 'editor-font-sizes' );

			// Add our own customizations.
			add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_block_editor_assets' ), PHP_INT_MAX );

		}

	}

	/**
	 * Enqueue block assets for the backend editor.
	 *
	 * @since  2.0.0
	 * @return void
	 */
	public static function enqueue_block_editor_assets() {

		if ( ! in_array( get_post_type(), [ 'bm_block_template', 'bm_template' ], true ) ) {
			return;
		}

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$path = 'includes/gutenberg/build/boldermail-block-editor.js';
		wp_enqueue_script( 'boldermail-gutenberg-blocks', BOLDERMAIL_PLUGIN_URL . $path, array( 'boldermail-editor', 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor', 'wp-plugins', 'wp-edit-post', 'wp-format-library' ), filemtime( BOLDERMAIL_PLUGIN_DIR . $path ), false );

		$path = 'includes/gutenberg/build/boldermail-block-styles.css';
		wp_enqueue_style( 'boldermail-editor-css', BOLDERMAIL_PLUGIN_URL . $path, array( 'wp-edit-blocks' ), filemtime( BOLDERMAIL_PLUGIN_DIR . $path ) );

		$path = "assets/css/boldermail-editor-styles-gutenberg$suffix.css";
		wp_enqueue_style( 'boldermail-editor-styles-gutenberg-css', BOLDERMAIL_PLUGIN_URL . $path, array( 'wp-edit-blocks' ), filemtime( BOLDERMAIL_PLUGIN_DIR . $path ) );

	}

	/**
	 * Register all Boldermail post meta for Gutenberg.
	 *
	 * @since  2.0.0
	 * @return void
	 */
	public static function register_post_meta() {

		if ( ! is_blog_installed() || ! post_type_exists( 'bm_block_template' ) || ! post_type_exists( 'bm_template' ) ) {
			return;
		}

		do_action( 'boldermail_before_register_post_meta' );

		$block_editor_meta_args = apply_filters(
			'bm_block_editor_post_meta_args',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		register_post_meta( 'bm_block_template', '_template_style', $block_editor_meta_args );
		register_post_meta( 'bm_block_template', '_preheader_style', $block_editor_meta_args );
		register_post_meta( 'bm_block_template', '_header_style', $block_editor_meta_args );
		register_post_meta( 'bm_block_template', '_body_style', $block_editor_meta_args );
		register_post_meta( 'bm_block_template', '_footer_style', $block_editor_meta_args );
		register_post_meta( 'bm_template', '_template_style', $block_editor_meta_args );
		register_post_meta( 'bm_template', '_preheader_style', $block_editor_meta_args );
		register_post_meta( 'bm_template', '_header_style', $block_editor_meta_args );
		register_post_meta( 'bm_template', '_body_style', $block_editor_meta_args );
		register_post_meta( 'bm_template', '_footer_style', $block_editor_meta_args );

		do_action( 'boldermail_after_register_post_meta' );

	}

	/**
	 * Add block categories for the editor.
	 *
	 * @see    https://loomo.ca/gutenberg-creating-custom-block-categories/
	 * @since  2.0.0
	 * @param  array   $categories Array of block categories.
	 * @param  WP_Post $post       Post object.
	 * @return array
	 */
	public static function block_categories( $categories, $post ) {

		if ( ! in_array( $post->post_type, [ 'bm_block_template', 'bm_template' ], true ) ) {
			return $categories;
		}

		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'boldermail-text',
					'title' => __( 'Text', 'boldermail' ),
				),
			),
			array(
				array(
					'slug'  => 'boldermail-media',
					'title' => __( 'Media', 'boldermail' ),
				),
			),
			array(
				array(
					'slug'  => 'boldermail-design',
					'title' => __( 'Design', 'boldermail' ),
				),
			),
			array(
				array(
					'slug'  => 'boldermail-embed',
					'title' => __( 'Embed', 'boldermail' ),
				),
			)
		);

	}

	/**
	 * Disable the WordPress default block patterns.
	 *
	 * @see   https://github.com/WordPress/gutenberg/issues/24505
	 * @since 2.2.0
	 */
	public static function allowed_block_patterns() {

		if ( in_array( boldermail_get_current_screen_post_type(), [ 'bm_block_template', 'bm_template' ], true ) ) {

			if ( ! class_exists( 'WP_Block_Patterns_Registry' ) ) {
				return;
			}

			$default_block_patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();

			foreach ( $default_block_patterns as $block_pattern ) {
				unregister_block_pattern( $block_pattern['name'] );
			}

//			register_block_pattern_category(
//				'gallery',
//				array(
//					'label' => __( 'Gallery', 'boldermail' ),
//				)
//			);
//
//			register_block_pattern_category(
//				'button',
//				array(
//					'label' => __( 'Button', 'boldermail' ),
//				)
//			);
//
//			register_block_pattern_category(
//				'header',
//				array(
//					'label' => __( 'Header', 'boldermail' ),
//				)
//			);
//
//			register_block_pattern_category(
//				'footer',
//				array(
//					'label' => __( 'Footer', 'boldermail' ),
//				)
//			);
//
//			register_block_pattern_category(
//				'social',
//				array(
//					'label' => __( 'Social', 'boldermail' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/gallery-1',
//				array(
//					'title'      => __( 'Gallery 1', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/columns --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmColumnsBlock" style="min-width:100%"><tbody class="bmColumnsBlockOuter"><tr><td valign="top" class="bmColumnsBlockInner"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/column {"width":300} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="300" style="width:300px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"id":3261,"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-portrait.jpg" alt="" class="wp-image-3261" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><!-- wp:boldermail/column {"width":300} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="300" style="width:300px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"id":3255,"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-landscape-1.jpg" alt="" class="wp-image-3255" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --><!-- wp:boldermail/image {"id":3256,"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-landscape-2.jpeg" alt="" class="wp-image-3256" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table><!-- /wp:boldermail/columns -->',
//					'categories' => array( 'gallery' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/gallery-2',
//				array(
//					'title'      => __( 'Gallery 2', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/columns --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmColumnsBlock" style="min-width:100%"><tbody class="bmColumnsBlockOuter"><tr><td valign="top" class="bmColumnsBlockInner"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/column {"width":300} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="300" style="width:300px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"id":3255,"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-landscape-1.jpg" alt="" class="wp-image-3255" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --><!-- wp:boldermail/image {"id":3256,"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-landscape-2.jpeg" alt="" class="wp-image-3256" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><!-- wp:boldermail/column {"width":300} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="300" style="width:300px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"id":3261,"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-portrait.jpg" alt="" class="wp-image-3261" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table><!-- /wp:boldermail/columns -->',
//					'categories' => array( 'gallery' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/gallery-3',
//				array(
//					'title'      => __( 'Gallery 3', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/columns --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmColumnsBlock" style="min-width:100%"><tbody class="bmColumnsBlockOuter"><tr><td valign="top" class="bmColumnsBlockInner"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/column {"width":200} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="200" style="width:200px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:200px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-square-3.jpeg" alt="" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><!-- wp:boldermail/column {"width":200} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="200" style="width:200px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:200px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-square-3.jpeg" alt="" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><!-- wp:boldermail/column {"width":200} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="200" style="width:200px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:200px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-square-3.jpeg" alt="" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table><!-- /wp:boldermail/columns -->',
//					'categories' => array( 'gallery' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/gallery-4',
//				array(
//					'title'      => __( 'Gallery 4', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/columns --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmColumnsBlock" style="min-width:100%"><tbody class="bmColumnsBlockOuter"><tr><td valign="top" class="bmColumnsBlockInner"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/column {"width":300} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="300" style="width:300px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-square-3.jpeg" alt="" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><!-- wp:boldermail/column {"width":300} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="300" style="width:300px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-square-3.jpeg" alt="" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table><!-- /wp:boldermail/columns -->',
//					'categories' => array( 'gallery' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/gallery-5',
//				array(
//					'title'      => __( 'Gallery 5', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/columns --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmColumnsBlock" style="min-width:100%"><tbody class="bmColumnsBlockOuter"><tr><td valign="top" class="bmColumnsBlockInner"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/column {"width":400} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="400" style="width:400px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:400px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-square-3.jpeg" alt="" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><!-- wp:boldermail/column {"width":200} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="200" style="width:200px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:200px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-square-3.jpeg" alt="" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --><!-- wp:boldermail/image {"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-square-3.jpeg" alt="" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table><!-- /wp:boldermail/columns -->',
//					'categories' => array( 'gallery' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/gallery-6',
//				array(
//					'title'      => __( 'Gallery 6', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/columns --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmColumnsBlock" style="min-width:100%"><tbody class="bmColumnsBlockOuter"><tr><td valign="top" class="bmColumnsBlockInner"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/column {"width":200} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="200" style="width:200px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:200px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-square-3.jpeg" alt="" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --><!-- wp:boldermail/image {"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-square-3.jpeg" alt="" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><!-- wp:boldermail/column {"width":400} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="400" style="width:400px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:400px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/image {"sizeSlug":"boldermail_newsletter"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="wp-block-boldermail-image size-boldermail_newsletter bmCaptionBlock"><tbody class="bmCaptionBlockOuter"><tr><td class="bmCaptionBlockInner" valign="top" style="padding:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="bmCaptionBottomContent" style="min-width:100%"><tbody><tr><td class="bmCaptionBottomImageContent" align="center" valign="top" style="padding:0 9px 9px 9px"><img src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/09/gallery-square-3.jpeg" alt="" style="border-width:2px;border-style:none;border-color:#202020"/></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/image --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table><!-- /wp:boldermail/columns -->',
//					'categories' => array( 'gallery' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/button',
//				array(
//					'title'      => __( 'Button', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/button --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmButtonBlock" style="min-width:100%"><tbody class="bmButtonBlockOuter"><tr><td align="center" style="padding-top:9px;padding-right:18px;padding-bottom:9px;padding-left:18px" valign="top" class="bmButtonBlockInner"><table border="0" cellpadding="0" cellspacing="0" class="bmButtonContentContainer" style="border-collapse:separate;border-width:2px;border-style:none;border-color:#000;border-radius:3px;background-color:#079bc4"><tbody><tr><td align="center" valign="middle" class="bmButtonContent" style="font-family:arial;font-size:16px;padding:18px"><a href="#" style="letter-spacing:0px;line-height:100%;text-align:center;text-decoration:none;color:#fff" class="bmButton" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Learn More', 'boldermail' ) . '</strong></a></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/button -->',
//					'categories' => array( 'buttons' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/fullwidth-button',
//				array(
//					'title'      => __( 'Fullwidth Button', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/button {"blockAlignment":"wide"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmButtonBlock" style="min-width:100%"><tbody class="bmButtonBlockOuter"><tr><td align="center" style="padding-top:9px;padding-right:18px;padding-bottom:9px;padding-left:18px" valign="top" class="bmButtonBlockInner"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmButtonContentContainer" style="border-collapse:separate;border-width:2px;border-style:none;border-color:#000;border-radius:3px;background-color:#079bc4"><tbody><tr><td align="center" valign="middle" class="bmButtonContent" style="font-family:arial;font-size:16px;padding:18px"><a href="#" style="letter-spacing:0px;line-height:100%;text-align:center;text-decoration:none;color:#fff" class="bmButton" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Learn More', 'boldermail' ) . '</strong></a></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/button -->',
//					'categories' => array( 'buttons' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/2-buttons',
//				array(
//					'title'      => __( '2 Buttons', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/columns --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmColumnsBlock" style="min-width:100%"><tbody class="bmColumnsBlockOuter"><tr><td valign="top" class="bmColumnsBlockInner"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/column {"width":300} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="300" style="width:300px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/button --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmButtonBlock" style="min-width:100%"><tbody class="bmButtonBlockOuter"><tr><td align="center" style="padding-top:9px;padding-right:18px;padding-bottom:9px;padding-left:18px" valign="top" class="bmButtonBlockInner"><table border="0" cellpadding="0" cellspacing="0" class="bmButtonContentContainer" style="border-collapse:separate;border-width:2px;border-style:none;border-color:#000;border-radius:3px;background-color:#079bc4"><tbody><tr><td align="center" valign="middle" class="bmButtonContent" style="font-family:arial;font-size:16px;padding:18px"><a href="#" style="letter-spacing:0px;line-height:100%;text-align:center;text-decoration:none;color:#fff" class="bmButton" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Learn More', 'boldermail' ) . '</strong></a></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/button --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><!-- wp:boldermail/column {"width":300} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="300" style="width:300px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/button --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmButtonBlock" style="min-width:100%"><tbody class="bmButtonBlockOuter"><tr><td align="center" style="padding-top:9px;padding-right:18px;padding-bottom:9px;padding-left:18px" valign="top" class="bmButtonBlockInner"><table border="0" cellpadding="0" cellspacing="0" class="bmButtonContentContainer" style="border-collapse:separate;border-width:2px;border-style:none;border-color:#000;border-radius:3px;background-color:#079bc4"><tbody><tr><td align="center" valign="middle" class="bmButtonContent" style="font-family:arial;font-size:16px;padding:18px"><a href="#" style="letter-spacing:0px;line-height:100%;text-align:center;text-decoration:none;color:#fff" class="bmButton" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Learn More', 'boldermail' ) . '</strong></a></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/button --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table><!-- /wp:boldermail/columns -->',
//					'categories' => array( 'buttons' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/3-buttons',
//				array(
//					'title'      => __( '3 Buttons', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/columns --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmColumnsBlock" style="min-width:100%"><tbody class="bmColumnsBlockOuter"><tr><td valign="top" class="bmColumnsBlockInner"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/column {"width":200} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="200" style="width:200px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:200px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/button --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmButtonBlock" style="min-width:100%"><tbody class="bmButtonBlockOuter"><tr><td align="center" style="padding-top:9px;padding-right:18px;padding-bottom:9px;padding-left:18px" valign="top" class="bmButtonBlockInner"><table border="0" cellpadding="0" cellspacing="0" class="bmButtonContentContainer" style="border-collapse:separate;border-width:2px;border-style:none;border-color:#000;border-radius:3px;background-color:#079bc4"><tbody><tr><td align="center" valign="middle" class="bmButtonContent" style="font-family:arial;font-size:16px;padding:18px"><a href="#" style="letter-spacing:0px;line-height:100%;text-align:center;text-decoration:none;color:#fff" class="bmButton" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Learn More', 'boldermail' ) . '</strong></a></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/button --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><!-- wp:boldermail/column {"width":200} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="200" style="width:200px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:200px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/button --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmButtonBlock" style="min-width:100%"><tbody class="bmButtonBlockOuter"><tr><td align="center" style="padding-top:9px;padding-right:18px;padding-bottom:9px;padding-left:18px" valign="top" class="bmButtonBlockInner"><table border="0" cellpadding="0" cellspacing="0" class="bmButtonContentContainer" style="border-collapse:separate;border-width:2px;border-style:none;border-color:#000;border-radius:3px;background-color:#079bc4"><tbody><tr><td align="center" valign="middle" class="bmButtonContent" style="font-family:arial;font-size:16px;padding:18px"><a href="#" style="letter-spacing:0px;line-height:100%;text-align:center;text-decoration:none;color:#fff" class="bmButton" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Learn More', 'boldermail' ) . '</strong></a></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/button --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><!-- wp:boldermail/column {"width":200} --><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;td valign="top" width="200" style="width:200px;">&lt;![endif][/boldermail_html_comment]</div><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:200px" width="100%" class="bmColumnContentContainer"><tbody><tr><td valign="top" class="bmColumnContent"><!-- wp:boldermail/button --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmButtonBlock" style="min-width:100%"><tbody class="bmButtonBlockOuter"><tr><td align="center" style="padding-top:9px;padding-right:18px;padding-bottom:9px;padding-left:18px" valign="top" class="bmButtonBlockInner"><table border="0" cellpadding="0" cellspacing="0" class="bmButtonContentContainer" style="border-collapse:separate;border-width:2px;border-style:none;border-color:#000;border-radius:3px;background-color:#079bc4"><tbody><tr><td align="center" valign="middle" class="bmButtonContent" style="font-family:arial;font-size:16px;padding:18px"><a href="#" style="letter-spacing:0px;line-height:100%;text-align:center;text-decoration:none;color:#fff" class="bmButton" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Learn More', 'boldermail' ) . '</strong></a></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/button --></td></tr></tbody></table><div class="boldermail-html-comment">[boldermail_html_comment][if gte mso 9]>&lt;/td>&lt;![endif][/boldermail_html_comment]</div><!-- /wp:boldermail/column --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table><!-- /wp:boldermail/columns -->',
//					'categories' => array( 'buttons' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/call-to-action',
//				array(
//					'title'      => __( 'Call to Action', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/paragraph {"textAlign":"center","color":"#4caad8"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmTextBlock" style="min-width:100%"><tbody class="bmTextBlockOuter"><tr><td valign="top" class="bmTextBlockInner" style="padding-top:9px">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;td valign="top" width="600" style="width:600px;">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%;min-width:100%" width="100%" class="bmTextContentContainer"><tbody><tr><td valign="top" class="bmTextContent" style="padding-top:0px;padding-right:18px;padding-bottom:9px;padding-left:18px"><p style="text-align:center;color:#4caad8">' . esc_html__( 'An Email Marketing and Automation Plugin for WordPress', 'boldermail' ) . '</p></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</td></tr></tbody></table><!-- /wp:boldermail/paragraph --><!-- wp:boldermail/heading {"level":1,"textAlign":"center"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmTextBlock" style="min-width:100%"><tbody class="bmTextBlockOuter"><tr><td valign="top" class="bmTextBlockInner" style="padding-top:9px">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;td valign="top" width="600" style="width:600px;">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%;min-width:100%" width="100%" class="bmTextContentContainer"><tbody><tr><td valign="top" class="bmTextContent" style="padding-top:0px;padding-right:18px;padding-bottom:9px;padding-left:18px"><h1 style="text-align:center">' . esc_html__( 'Introducing Boldermail', 'boldermail' ) . '</h1></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</td></tr></tbody></table><!-- /wp:boldermail/heading --><!-- wp:boldermail/paragraph {"textAlign":"center"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmTextBlock" style="min-width:100%"><tbody class="bmTextBlockOuter"><tr><td valign="top" class="bmTextBlockInner" style="padding-top:9px">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;td valign="top" width="600" style="width:600px;">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%;min-width:100%" width="100%" class="bmTextContentContainer"><tbody><tr><td valign="top" class="bmTextContent" style="padding-top:0px;padding-right:18px;padding-bottom:9px;padding-left:18px"><p style="text-align:center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eget convallis mi, in aliquam quam. Aliquam tincidunt at urna eu sodales. Aliquam luctus erat felis, non lobortis neque tristique quis. </p></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</td></tr></tbody></table><!-- /wp:boldermail/paragraph --><!-- wp:boldermail/button --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmButtonBlock" style="min-width:100%"><tbody class="bmButtonBlockOuter"><tr><td align="center" style="padding-top:9px;padding-right:18px;padding-bottom:9px;padding-left:18px" valign="top" class="bmButtonBlockInner"><table border="0" cellpadding="0" cellspacing="0" class="bmButtonContentContainer" style="border-collapse:separate;border-width:2px;border-style:none;border-color:#000;border-radius:3px;background-color:#079bc4"><tbody><tr><td align="center" valign="middle" class="bmButtonContent" style="font-family:arial;font-size:16px;padding:18px"><a href="#" style="letter-spacing:0px;line-height:100%;text-align:center;text-decoration:none;color:#fff" class="bmButton" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Learn More', 'boldermail' ) . '</strong></a></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/button -->',
//					'categories' => array( 'buttons' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/boxed-call-to-action',
//				array(
//					'title'      => __( 'Boxed Call to Action', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/boxed-text {"backgroundColor":"#eaeaea"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmBoxedTextBlock" style="min-width:100%"><tbody class="bmBoxedTextBlockOuter"><tr><td valign="top" class="bmBoxedTextBlockInner">[boldermail_html_comment][if gte mso 9]>&lt;table align="center" border="0" cellSpacing="0" cellPadding="0" width="100%">&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%" class="bmBoxedTextContentContainer"><tbody><tr><td style="padding:9px 18px"><table border="0" cellspacing="0" class="bmTextContentContainer" width="100%" style="min-width:100%;background-color:#eaeaea;border-width:2px;border-style:none;border-color:#000"><tbody><tr><td valign="top" class="bmTextContent" style="padding-top:9px;padding-bottom:9px"><!-- wp:boldermail/paragraph {"textAlign":"center","color":"#4caad8"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmTextBlock" style="min-width:100%"><tbody class="bmTextBlockOuter"><tr><td valign="top" class="bmTextBlockInner" style="padding-top:9px">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;td valign="top" width="600" style="width:600px;">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%;min-width:100%" width="100%" class="bmTextContentContainer"><tbody><tr><td valign="top" class="bmTextContent" style="padding-top:0px;padding-right:18px;padding-bottom:9px;padding-left:18px"><p style="text-align:center;color:#4caad8">' . esc_html__( 'An Email Marketing and Automation Plugin for WordPress', 'boldermail' ) . '</p></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</td></tr></tbody></table><!-- /wp:boldermail/paragraph --><!-- wp:boldermail/heading {"level":1,"textAlign":"center"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmTextBlock" style="min-width:100%"><tbody class="bmTextBlockOuter"><tr><td valign="top" class="bmTextBlockInner" style="padding-top:9px">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;td valign="top" width="600" style="width:600px;">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%;min-width:100%" width="100%" class="bmTextContentContainer"><tbody><tr><td valign="top" class="bmTextContent" style="padding-top:0px;padding-right:18px;padding-bottom:9px;padding-left:18px"><h1 style="text-align:center">' . esc_html__( 'Introducing Boldermail', 'boldermail' ) . '</h1></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</td></tr></tbody></table><!-- /wp:boldermail/heading --><!-- wp:boldermail/paragraph {"textAlign":"center"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmTextBlock" style="min-width:100%"><tbody class="bmTextBlockOuter"><tr><td valign="top" class="bmTextBlockInner" style="padding-top:9px">[boldermail_html_comment][if mso]>&lt;table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">&lt;tr>&lt;td valign="top" width="600" style="width:600px;">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%;min-width:100%" width="100%" class="bmTextContentContainer"><tbody><tr><td valign="top" class="bmTextContent" style="padding-top:0px;padding-right:18px;padding-bottom:9px;padding-left:18px"><p style="text-align:center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eget convallis mi, in aliquam quam. Aliquam tincidunt at urna eu sodales. Aliquam luctus erat felis, non lobortis neque tristique quis.&nbsp;</p></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</td></tr></tbody></table><!-- /wp:boldermail/paragraph --><!-- wp:boldermail/button {"borderColor":"","backgroundColor":"#ea5b3a"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmButtonBlock" style="min-width:100%"><tbody class="bmButtonBlockOuter"><tr><td align="center" style="padding-top:9px;padding-right:18px;padding-bottom:9px;padding-left:18px" valign="top" class="bmButtonBlockInner"><table border="0" cellpadding="0" cellspacing="0" class="bmButtonContentContainer" style="border-collapse:separate;border-radius:3px;background-color:#ea5b3a"><tbody><tr><td align="center" valign="middle" class="bmButtonContent" style="font-family:arial;font-size:16px;padding:18px"><a href="#" style="letter-spacing:0px;line-height:100%;text-align:center;text-decoration:none;color:#fff" class="bmButton" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Learn More', 'boldermail' ) . '</strong></a></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/button --></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if gte mso 9]>&lt;/table>&lt;/td>&lt;![endif][/boldermail_html_comment]</td></tr></tbody></table><!-- /wp:boldermail/boxed-text -->',
//					'categories' => array( 'buttons' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/social-follow-solid-small-color',
//				array(
//					'title'      => __( 'Social Follow Small Color Icons', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/social-links --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowBlock" style="min-width:100%"><tbody class="bmFollowBlockOuter"><tr><td align="center" valign="top" style="padding:9px" class="bmFollowBlockInner"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentContainer" style="min-width:100%"><tbody><tr><td align="center" style="padding-left:9px;padding-right:9px"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;background-color:;border-width:2px;border-style:none;border-color:#202020" class="bmFollowContent"><tbody><tr><td align="center" valign="top" style="padding-top:9px;padding-right:9px;padding-left:9px"><table align="center" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center" valign="top"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="center" border="0" cellspacing="0" cellpadding="0">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/social-link {"url":"#","service":"facebook","label":"Facebook"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="24" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/color-facebook-48.png" alt="Facebook" style="display:block" height="24" width="24" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"#","service":"twitter","label":"Twitter"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="24" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/color-twitter-48.png" alt="Twitter" style="display:block" height="24" width="24" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"#","service":"instagram","label":"Instagram"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="24" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/color-instagram-48.png" alt="Instagram" style="display:block" height="24" width="24" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"mailto:","service":"forwardtofriend","label":"Email"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="24" class="bmFollowIconContent"><a href="mailto:mailto:" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/color-forwardtofriend-48.png" alt="Email" style="display:block" height="24" width="24" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/social-links -->',
//					'categories' => array( 'social' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/social-follow-solid-small-light-green',
//				array(
//					'title'      => __( 'Social Follow Small Light Icons + Background', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/social-links {"backgroundColor":"#89d085","iconColor":"light"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowBlock" style="min-width:100%"><tbody class="bmFollowBlockOuter"><tr><td align="center" valign="top" style="padding:9px" class="bmFollowBlockInner"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentContainer" style="min-width:100%"><tbody><tr><td align="center" style="padding-left:9px;padding-right:9px"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;background-color:#89d085;border-width:2px;border-style:none;border-color:#202020" class="bmFollowContent"><tbody><tr><td align="center" valign="top" style="padding-top:9px;padding-right:9px;padding-left:9px"><table align="center" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center" valign="top"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="center" border="0" cellspacing="0" cellpadding="0">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/social-link {"url":"#","service":"facebook","label":"Facebook","iconColor":"light"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="24" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/light-facebook-48.png" alt="Facebook" style="display:block" height="24" width="24" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"#","service":"twitter","label":"Twitter","iconColor":"light"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="24" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/light-twitter-48.png" alt="Twitter" style="display:block" height="24" width="24" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"#","service":"instagram","label":"Instagram","iconColor":"light"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="24" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/light-instagram-48.png" alt="Instagram" style="display:block" height="24" width="24" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"mailto:","service":"forwardtofriend","label":"Email","iconColor":"light"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="24" class="bmFollowIconContent"><a href="mailto:mailto:" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/light-forwardtofriend-48.png" alt="Email" style="display:block" height="24" width="24" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/social-links -->',
//					'categories' => array( 'social' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/social-follow-outlined-large-light-black',
//				array(
//					'title'      => __( 'Social Follow Outlined Large Light Icons + Background', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/social-links {"backgroundColor":"#333333","iconStyle":"outline","iconSize":96,"iconColor":"light"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowBlock" style="min-width:100%"><tbody class="bmFollowBlockOuter"><tr><td align="center" valign="top" style="padding:9px" class="bmFollowBlockInner"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentContainer" style="min-width:100%"><tbody><tr><td align="center" style="padding-left:9px;padding-right:9px"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;background-color:#333333;border-width:2px;border-style:none;border-color:#202020" class="bmFollowContent"><tbody><tr><td align="center" valign="top" style="padding-top:9px;padding-right:9px;padding-left:9px"><table align="center" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center" valign="top"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="center" border="0" cellspacing="0" cellpadding="0">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/social-link {"url":"#","service":"facebook","label":"Facebook","iconStyle":"outline","iconSize":96,"iconColor":"light"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="48" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/outline-light-facebook-96.png" alt="Facebook" style="display:block" height="48" width="48" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"#","service":"twitter","label":"Twitter","iconStyle":"outline","iconSize":96,"iconColor":"light"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="48" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/outline-light-twitter-96.png" alt="Twitter" style="display:block" height="48" width="48" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"#","service":"instagram","label":"Instagram","iconStyle":"outline","iconSize":96,"iconColor":"light"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="48" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/outline-light-instagram-96.png" alt="Instagram" style="display:block" height="48" width="48" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"mailto:","service":"forwardtofriend","label":"Email","iconStyle":"outline","iconSize":96,"iconColor":"light"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="48" class="bmFollowIconContent"><a href="mailto:mailto:" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/outline-light-forwardtofriend-96.png" alt="Email" style="display:block" height="48" width="48" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/social-links -->',
//					'categories' => array( 'social' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/social-follow-solid-large-color-yellow',
//				array(
//					'title'      => __( 'Social Follow Large Color Icons + Background', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/social-links {"backgroundColor":"#ffd249","iconSize":96} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowBlock" style="min-width:100%"><tbody class="bmFollowBlockOuter"><tr><td align="center" valign="top" style="padding:9px" class="bmFollowBlockInner"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentContainer" style="min-width:100%"><tbody><tr><td align="center" style="padding-left:9px;padding-right:9px"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;background-color:#ffd249;border-width:2px;border-style:none;border-color:#202020" class="bmFollowContent"><tbody><tr><td align="center" valign="top" style="padding-top:9px;padding-right:9px;padding-left:9px"><table align="center" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center" valign="top"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="center" border="0" cellspacing="0" cellpadding="0">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/social-link {"url":"#","service":"facebook","label":"Facebook","iconSize":96} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="48" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/color-facebook-96.png" alt="Facebook" style="display:block" height="48" width="48" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"#","service":"twitter","label":"Twitter","iconSize":96} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="48" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/color-twitter-96.png" alt="Twitter" style="display:block" height="48" width="48" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"#","service":"instagram","label":"Instagram","iconSize":96} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="48" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/color-instagram-96.png" alt="Instagram" style="display:block" height="48" width="48" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"mailto:","service":"forwardtofriend","label":"Email","iconSize":96} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="48" class="bmFollowIconContent"><a href="mailto:mailto:" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/color-forwardtofriend-96.png" alt="Email" style="display:block" height="48" width="48" class=""/></a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/social-links -->',
//					'categories' => array( 'social' ),
//				)
//			);
//
//			register_block_pattern(
//				'boldermail/social-follow-text-solid-small-color',
//				array(
//					'title'      => __( 'Social Follow Small Color Icons + Text', 'boldermail' ),
//					'content'    => '<!-- wp:boldermail/social-links {"display":"both"} --><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowBlock" style="min-width:100%"><tbody class="bmFollowBlockOuter"><tr><td align="center" valign="top" style="padding:9px" class="bmFollowBlockInner"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentContainer" style="min-width:100%"><tbody><tr><td align="center" style="padding-left:9px;padding-right:9px"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;background-color:;border-width:2px;border-style:none;border-color:#202020" class="bmFollowContent"><tbody><tr><td align="center" valign="top" style="padding-top:9px;padding-right:9px;padding-left:9px"><table align="center" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center" valign="top"><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;table align="center" border="0" cellspacing="0" cellpadding="0">&lt;tr>&lt;![endif][/boldermail_html_comment]</div><!-- wp:boldermail/social-link {"url":"#","service":"facebook","label":"Facebook","display":"both"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="24" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/color-facebook-48.png" alt="Facebook" style="display:block" height="24" width="24" class=""/></a></td><td align="left" valign="middle" class="bmFollowTextContent" style="padding-left:5px"><a href="#" target="_blank" rel="noopener noreferrer" style="font-family:arial;font-size:11px;color:#202020">Facebook</a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"#","service":"twitter","label":"Twitter","display":"both"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="24" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/color-twitter-48.png" alt="Twitter" style="display:block" height="24" width="24" class=""/></a></td><td align="left" valign="middle" class="bmFollowTextContent" style="padding-left:5px"><a href="#" target="_blank" rel="noopener noreferrer" style="font-family:arial;font-size:11px;color:#202020">Twitter</a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"#","service":"instagram","label":"Instagram","display":"both"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="24" class="bmFollowIconContent"><a href="#" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/color-instagram-48.png" alt="Instagram" style="display:block" height="24" width="24" class=""/></a></td><td align="left" valign="middle" class="bmFollowTextContent" style="padding-left:5px"><a href="#" target="_blank" rel="noopener noreferrer" style="font-family:arial;font-size:11px;color:#202020">Instagram</a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><!-- wp:boldermail/social-link {"url":"mailto:","service":"forwardtofriend","label":"Email","display":"both"} -->[boldermail_html_comment][if mso]>&lt;td align="center" valign="top">&lt;![endif][/boldermail_html_comment]<table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline"><tbody><tr><td valign="top" style="padding-right:10px;padding-bottom:9px" class="bmFollowContentItemContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmFollowContentItem"><tbody><tr><td align="left" valign="middle" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:9px"><table align="left" border="0" cellpadding="0" cellspacing="0" width=""><tbody><tr><td align="center" valign="middle" width="24" class="bmFollowIconContent"><a href="mailto:mailto:" target="_blank" rel="noopener noreferrer"> <img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/color-forwardtofriend-48.png" alt="Email" style="display:block" height="24" width="24" class=""/></a></td><td align="left" valign="middle" class="bmFollowTextContent" style="padding-left:5px"><a href="mailto:mailto:" target="_blank" rel="noopener noreferrer" style="font-family:arial;font-size:11px;color:#202020">Email</a></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>[boldermail_html_comment][if mso]>&lt;/td>&lt;![endif][/boldermail_html_comment]<!-- /wp:boldermail/social-link --><div class="boldermail-html-comment">[boldermail_html_comment][if mso]>&lt;/tr>&lt;/table>&lt;![endif][/boldermail_html_comment]</div></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><!-- /wp:boldermail/social-links -->',
//					'categories' => array( 'social' ),
//				)
//			);

		}

	}

	/**
	 * Filter whether a post is able to be edited in the block editor.
	 *
	 * Activate the Gutenberg editor for the block templates and templates.
	 * This filter is used in the Classic Editor plugin if the administrator allows
	 * users to select the Classic or Block Editor.
	 *
	 * @since  2.0.0
	 * @param  bool    $use_block_editor Whether the post can be edited or not.
	 * @param  WP_Post $post             The post being checked.
	 * @return bool
	 */
	public static function use_block_editor_for_post( $use_block_editor, $post ) {

		if ( 'bm_template' === $post->post_type ) {

			$template = boldermail_get_template( $post );

			if ( ! $template ) {
				return $use_block_editor;
			}

			if ( $template->use_block_editor() ) {
				return true;
			} else {
				remove_post_type_support( 'bm_template', 'editor' );
				return false;
			}

		}

		if ( 'bm_block_template' === $post->post_type ) {
			return true;
		}

		return $use_block_editor;

	}

	/**
	 * Filter whether a post type is able to be edited in the block editor.
	 *
	 * Activate the Gutenberg editor for the block templates, and deactivate for newsletters.
	 * This filter is used in the Classic Editor plugin if the administrator does
	 * not allow users to select an editor.
	 *
	 * @since  2.0.0
	 * @param  bool   $use_block_editor Whether the post type can be edited or not. Default true.
	 * @param  string $post_type        The post type being checked.
	 * @return bool
	 */
	public static function use_block_editor_for_post_type( $use_block_editor, $post_type ) {

		if ( in_array( $post_type, [ 'bm_newsletter', 'bm_newsletter_ares', 'bm_template' ], true ) ) {
			return false;
		}

		if ( 'bm_block_template' === $post_type ) {
			return true;
		}

		return $use_block_editor;

	}

	/**
	 * Filter the names and labels of the default image sizes in the block editor.
	 * Even though the Image block uses its own setting `bmImageSizes` to select
	 * which image sizes to show in the Inspector Controls, we still need to include
	 * the slug in this function which gets called from `wp_prepare_attachment_for_js`.
	 *
	 * @since  2.0.0
	 * @param  string[] $sizes Array of image size labels keyed by their name.
	 * @return string[]
	 */
	public static function image_size_names_choose( $sizes ) {

		return array_merge(
			$sizes,
			array(
				'boldermail_newsletter' => __( 'Boldermail Newsletter', 'boldermail' ),
			)
		);

	}

}

Boldermail_Gutenberg::init();
