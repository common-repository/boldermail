<?php
/**
 * Editor setup.
 *
 * @link       https://www.boldermail.com/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Editor class.
 *
 * @since 1.0.0
 */
class Boldermail_Editor {

	/**
	 * Editor ID.
	 *
	 * @since 1.0.0
	 * @var   string $editor_id Editor ID.
	 */
	private $editor_id;

	/**
	 * Preview data.
	 *
	 * @since 1.0.0
	 * @var   array $preview_meta Preview meta data.
	 */
	private $preview_meta;

	/**
	 * Use block editor?
	 *
	 * @since 1.0.0
	 * @var   boolean $use_block_editor Whether to use the Block Editor or the Classic Editor..
	 */
	private $use_block_editor;

	/**
	 * Is readonly?
	 *
	 * @since 1.0.0
	 * @var   boolean $readonly Whether the user can edit the editor, or just has read access.
	 */
	private $readonly;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param string $content   Initial content for the editor.
	 * @param string $editor_id HTML id attribute value for the textarea and TinyMCE.
	 *                          May only contain lowercase letters and underscores.
	 *                          Hyphens will cause the editor to display improperly.
	 * @param array  $settings  An array of arguments.
	 */
	public function __construct( $content, $editor_id, $settings = array() ) {

		$this->editor_id = $editor_id;

		// Get preview and test email information.
		$this->preview_meta = isset( $settings['preview_meta'] ) ? $settings['preview_meta'] : array();

		// Check if we are using the Classic or Block editor.
		$this->use_block_editor = isset( $settings['block_template'] ) ? $settings['block_template'] : false;

		// Save readonly property.
		$this->readonly = isset( $settings['readonly'] ) ? $settings['readonly'] : false;

		// Customize TinyMCE editor.
		if ( ! $this->use_block_editor ) {
			add_filter( 'tiny_mce_plugins', array( $this, 'mce_internal_plugins' ) );
			add_filter( 'tiny_mce_before_init', array( $this, 'tiny_mce' ) );
			add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ) );
			add_filter( 'mce_css', array( $this, 'mce_css' ) );
			add_filter( 'tiny_mce_before_init', array( $this, 'tinymce_settings' ) );
			add_filter( 'mce_buttons', array( $this, 'mce_buttons' ), PHP_INT_MAX );
			add_filter( 'mce_buttons_2', array( $this, 'mce_buttons_2' ), PHP_INT_MAX );

			if ( $this->readonly ) {
				add_filter( 'wp_editor_settings', array( $this, 'wp_editor_settings' ), PHP_INT_MAX );
			}
		}

		/**
		 * Remove all buttons from the top of the editor,
		 * and add our custom ones.
		 *
		 * @since   1.7.0
		 */
		$media_buttons = serialize( $GLOBALS['wp_filter']['media_buttons'] ); /* phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize */
		if ( ! $this->use_block_editor ) {
			remove_all_actions( 'media_buttons' );
			add_action( 'media_buttons', array( $this, 'media_buttons' ) );
		}

		?>
		<div id="boldermail-<?php echo esc_attr( $this->editor_id ); ?>-wrap" class="boldermail-editor-wrap clearfix" data-editor="<?php echo esc_attr( $this->editor_id ); ?>" data-preview="<?php echo esc_attr( wp_json_encode( $this->preview_meta ) ); ?>">
		<?php

		if ( ! $this->use_block_editor ) {

			wp_editor( $content, $this->editor_id, $settings );
			$this->output_templates();

		} else {

			$bm_block_template = boldermail_get_block_template( $this->use_block_editor );
			?>
			<button type="submit" <?php echo esc_attr( $this->readonly ? 'disabled' : '' ); ?> data-name="redirect_to_block_template" data-value="<?php echo esc_attr( $this->use_block_editor ); ?>" class="button button-primary boldermail-media-button">
				<span class="wp-media-buttons-icon boldermail-editor-buttons-icon dashicons-layout"></span>
				<?php echo $bm_block_template->get_content() ? esc_html__( 'Update Email', 'boldermail' ) : esc_html__( 'Design Email', 'boldermail' ); ?>
			</button>
			<button type="submit" <?php echo esc_attr( $this->readonly ? 'disabled' : '' ); ?> data-name="switch_to_classic_editor" data-value="<?php echo esc_attr( $this->use_block_editor ); ?>" class="button boldermail-media-button">
				<span class="wp-media-buttons-icon boldermail-editor-buttons-icon dashicons-editor-code"></span>
				<?php esc_html_e( 'Switch to Classic Editor', 'boldermail' ); ?>
				<?php echo boldermail_help_tip( __( 'Use the Classic Editor to select a template and make changes to the content, but not the layout of the template. Or use this editor to code your own HTML template!', 'boldermail' ) ); ?>
			</button>
			<?php
			if ( $bm_block_template->get_content() ) {
				$this->button_html_preview();
				$this->button_plain_text_preview();
				$this->button_test_send();
			}

		}

		$this->output_test_send();
		do_action( 'boldermail_editor_panels', $this->editor_id );
		?>
		</div>
		<?php

		if ( ! $this->use_block_editor ) {
			remove_filter( 'tiny_mce_plugins', array( $this, 'mce_internal_plugins' ) );
			remove_filter( 'tiny_mce_before_init', array( $this, 'tiny_mce' ) );
			remove_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ) );
			remove_filter( 'mce_css', array( $this, 'mce_css' ) );
			remove_filter( 'tiny_mce_before_init', array( $this, 'tinymce_settings' ) );
			remove_filter( 'mce_buttons', array( $this, 'mce_buttons' ), 5 );
			remove_filter( 'mce_buttons_2', array( $this, 'mce_buttons_2' ), 5 );

			if ( $this->readonly ) {
				remove_filter( 'wp_editor_settings', array( $this, 'wp_editor_settings' ), PHP_INT_MAX );
			}
		}

		if ( ! $this->use_block_editor ) {
			$GLOBALS['wp_filter']['media_buttons'] = unserialize( $media_buttons ); /* phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize */
		}
	}

	/**
	 * Modify the internal TinyMCE plugins.
	 *
	 * @since  1.0.0
	 * @param  array $plugins An array of all plugins.
	 * @return array
	 */
	public function mce_internal_plugins( $plugins ) {

		// Remove WordPress from the list of internal plugins.
		$key = array_search( 'wordpress', $plugins, true ); /* phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled */
		if ( false !== $key ) {
			unset( $plugins[ $key ] );
		}

		return $plugins;

	}

	/**
	 * Modify the TinyMCE editor settings.
	 *
	 * @since  1.0.0
	 * @param  array $settings An array of editor settings.
	 * @return array
	 */
	public function tiny_mce( $settings ) {

		$plugins   = explode( ',', $settings['plugins'] );
		$plugins[] = 'fullpage';
		$plugins[] = 'table';

		// Add WordPress to the list of external plugins (@see mce_external_plugins).
		$plugins[] = 'bm_wordpress';

		$settings['plugins']        = implode( ',', $plugins );
		$settings['valid_elements'] = '*[*]';

		// Keep the kitchen sink always on.
		$settings['wordpress_adv_hidden'] = false;

		// Set readonly property.
		$settings['readonly'] = $this->readonly ? true : false;

		return $settings;

	}

	/**
	 * Load external TinyMCE plugins.
	 *
	 * @since  1.0.0
	 * @param  array $plugins An array of all plugins.
	 * @return array
	 */
	public function mce_external_plugins( $plugins ) {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$plugins['fullpage']     = BOLDERMAIL_PLUGIN_URL . "includes/plugins/tinymce/plugins/fullpage/plugin$suffix.js";
		$plugins['table']        = BOLDERMAIL_PLUGIN_URL . "includes/plugins/tinymce/plugins/table/plugin$suffix.js";
		$plugins['bm_wordpress'] = BOLDERMAIL_PLUGIN_URL . "assets/js/tinymce/plugins/wordpress/plugin$suffix.js";

		return $plugins;

	}

	/**
	 * Remove all default TinyMCE styles, and add custom TinyMCE styles for our email templates.
	 *
	 * @since  1.0.0
	 * @see    https://wordpress.stackexchange.com/questions/220471/is-there-a-way-to-remove-the-default-css-from-tinymce
	 * @return string
	 */
	public function mce_css() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		return BOLDERMAIL_PLUGIN_URL . "assets/css/boldermail-editor-styles-tinymce$suffix.css";

	}

	/**
	 * Configure TinyMCE settings.
	 *
	 * @since  1.0.0
	 * @param  array $settings An array of editor settings.
	 * @return array
	 */
	public function tinymce_settings( $settings ) {

		/**
		 * Remove TinyMCE skin styles.
		 *
		 * The filter `mce_css` does not delete this stylesheet. This stylesheet,
		 * `<link rel="stylesheet" type="text/css" href="http://localhost/develop/wp-includes/js/tinymce/skins/lightgray/content.min.css">`,
		 * is a skin. There might be another way to remove or replace this skin,
		 * but this jQuery function does the trick.
		 *
		 * @since   1.0.0
		 * @see     https://wordpress.stackexchange.com/a/286047/85404
		 */
		$settings['init_instance_callback'] = "function(){jQuery('#{$this->editor_id}_ifr').contents().find('link[href*=\'content.min.css\']').remove();}";

		return $settings;

	}

	/**
	 * Add buttons after the default media button(s) are displayed.
	 *
	 * @since  1.0.0
	 * @param  string $editor_id HTML id attribute value for the textarea and TinyMCE.
	 * @return void
	 */
	public function media_buttons( $editor_id ) {

		if ( ! $this->readonly ) {

			if ( function_exists( 'media_buttons' ) ) {
				media_buttons( $editor_id );
			}

			?>
			<a type="button" class="button boldermail-media-button select-template" href="#template-browser-<?php echo esc_attr( $this->editor_id ); ?>">
				<span class="wp-media-buttons-icon boldermail-editor-buttons-icon dashicons-layout"></span>
				<?php esc_html_e( 'Select Template', 'boldermail' ); ?>
			</a>
			<?php

		}

		$this->button_html_preview();
		$this->button_plain_text_preview();

		if ( ! $this->readonly ) {
			$this->button_test_send();
		}

	}

	/**
	 * Display the HTML preview button.
	 *
	 * @since 2.0.0
	 */
	public function button_html_preview() {

		?>
		<button type="button" class="button boldermail-media-button boldermail-html-preview">
			<span class="wp-media-buttons-icon boldermail-editor-buttons-icon dashicons-visibility"></span>
			<?php esc_html_e( 'HTML Preview', 'boldermail' ); ?>
			<?php echo boldermail_help_tip( __( 'Preview how your email will look to your subscribers.', 'boldermail' ) ); ?>
		</button>
		<?php

	}

	/**
	 * Display the plain-text preview button.
	 *
	 * @since 2.0.0
	 */
	public function button_plain_text_preview() {

		?>
		<button type="button" class="button boldermail-media-button boldermail-plain-text-preview">
			<span class="wp-media-buttons-icon boldermail-editor-buttons-icon dashicons-editor-justify"></span>
			<?php esc_html_e( 'Text Preview', 'boldermail' ); ?>
			<?php echo boldermail_help_tip( __( 'All emails must include a text-only version of the message for accessibility reasons. This version of the email is generated automatically for you by Boldermail. See how it would look here.', 'boldermail' ) ); ?>
		</button>
		<?php

	}

	/**
	 * Display the send test email button.
	 *
	 * @since 2.0.0
	 */
	public function button_test_send() {

		?>
		<a type="button" class="button boldermail-media-button test-send" href="#test-send-email-<?php echo esc_attr( $this->editor_id ); ?>">
			<span class="wp-media-buttons-icon boldermail-editor-buttons-icon dashicons-email"></span>
			<?php esc_html_e( 'Send Test Email', 'boldermail' ); ?>
		</a>
		<?php

	}

	/**
	 * Select TinyMCE table control buttons.
	 *
	 * @since  1.0.0
	 * @return array Buttons for the first row.
	 */
	public function mce_buttons() {

		$buttons = array();

		$buttons[] = 'formatselect';
		$buttons[] = 'fontselect';
		$buttons[] = 'fontsizeselect';
		$buttons[] = 'bold';
		$buttons[] = 'italic';
		$buttons[] = 'underline';
		$buttons[] = 'strikethrough';
		$buttons[] = 'superscript';
		$buttons[] = 'subscript';
		$buttons[] = 'wp_code';
		$buttons[] = 'cut';
		$buttons[] = 'copy';
		$buttons[] = 'paste';
		$buttons[] = 'pastetext';
		$buttons[] = 'link';
		$buttons[] = 'fullscreen';
		$buttons[] = 'wp_adv';

		return $buttons;

	}

	/**
	 * Select TinyMCE table control buttons.
	 *
	 * @see    https://plugins.svn.wordpress.org/mce-table-buttons/trunk/mce_table_buttons.php
	 * @since  1.0.0
	 * @return array Buttons for the second row.
	 */
	public function mce_buttons_2() {

		$buttons = array();

		$buttons[] = 'bullist';
		$buttons[] = 'numlist';
		$buttons[] = 'blockquote';
		$buttons[] = 'alignleft';
		$buttons[] = 'aligncenter';
		$buttons[] = 'alignright';
		$buttons[] = 'spellchecker';
		$buttons[] = 'hr';
		$buttons[] = 'forecolor';
		$buttons[] = 'backcolor';
		$buttons[] = 'removeformat';
		$buttons[] = 'charmap';
		$buttons[] = 'outdent';
		$buttons[] = 'indent';
		$buttons[] = 'undo';
		$buttons[] = 'redo';
		$buttons[] = 'table';
		$buttons[] = 'boldermail_preview';
		$buttons[] = 'wp_help';

		return $buttons;

	}

	/**
	 * Filter the editor settings.
	 *
	 * @since  2.0.0
	 * @param  array $settings Editor settings.
	 * @return array           Editor settings.
	 */
	public function wp_editor_settings( $settings ) {

		if ( $this->readonly ) {
			$settings['quicktags'] = false;
		}

		return $settings;

	}

	/**
	 * Display all available Templates similar to Themes display.
	 *
	 * @since   1.0.0
	 */
	public function output_templates() {

		$templates = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => 'bm_template',
				'post_status'    => 'publish',
				'meta_query'     => array( /* phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query */
					'relation' => 'OR',
					array(
						'key'     => '_use_block_editor',
						'value'   => '1',
						'compare' => '!=',
					),
					array(
						'key'     => '_use_block_editor',
						'compare' => 'NOT EXISTS',
					),
				),
			)
		);

		include BOLDERMAIL_PLUGIN_DIR . 'partials/editor/html-boldermail-template-browser.php';

	}

	/**
	 * Display input to send test email.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function output_test_send() {

		include BOLDERMAIL_PLUGIN_DIR . 'partials/editor/html-boldermail-test-send.php';

	}

}

/**
 * This function renders a custom editor in a page
 * in the typical fashion used in Posts and Pages.
 *
 * @since  1.0.0
 * @param  string $content   Initial content for the editor.
 * @param  string $editor_id HTML id attribute value for the textarea and TinyMCE.
 *                           May only contain lowercase letters and underscores.
 *                           Hyphens will cause the editor to display improperly.
 * @param  array  $settings  An array of arguments.
 * @return void
 */
function boldermail_editor( $content, $editor_id, $settings = array() ) {

	if ( ! isset( $settings['textarea_name'] ) ) {
		$settings['textarea_name'] = "_{$editor_id}";
	}

	if ( ! isset( $settings['textarea_rows'] ) ) {
		$settings['textarea_rows'] = 20;
	}

	if ( ! isset( $settings['preview_meta'] ) ) {
		$settings['preview_meta'] = array();
	}

	if ( ! isset( $settings['preview_meta']['filter'] ) ) {
		$settings['preview_meta']['filter'] = 'display';
	}

	if ( ! isset( $settings['readonly'] ) ) {
		$settings['readonly'] = false;
	}

	new Boldermail_Editor( $content, $editor_id, $settings );

}
