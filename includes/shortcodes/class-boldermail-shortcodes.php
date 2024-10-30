<?php
/**
 * Shortcodes.
 *
 * Boldermail shortcodes public static functions.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Shortcodes class.
 *
 * @since 1.0.0
 */
class Boldermail_Shortcodes {

	/**
	 * Singleton instance.
	 *
	 * @since 1.7.0
	 * @var   Boldermail_Shortcodes $instance
	 */
	private static $instance;

	/**
	 * Filter.
	 *
	 * @since 1.7.0
	 * @var   string
	 */
	public $filter;

	/**
	 * Get instance.
	 *
	 * @since  1.7.0
	 * @return Boldermail_Shortcodes
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Constructor.
	 *
	 * @since 1.7.0
	 */
	private function __construct() {

		$this->load_dependencies();
		$this->add_shortcodes();

		$this->filter = 'display';

	}

	/**
	 * Search content for shortcodes and filter shortcodes through their hooks.
	 *
	 * The shortcodes defined in this plugin use the global $post variable.
	 * If the variable is not set, return an empty string to avoid sending a
	 * newsletter with no content, or with the wrong content.
	 *
	 * @since  1.0.0
	 * @param  string $content Content to search for shortcodes.
	 * @param  string $filter  Type of filter to apply. Accepts 'raw', 'preview', 'display', 'api'.
	 *                         The 'raw' filter removes all of Boldermail shortcodes,
	 *                         but it still applies all other WordPress and plugin shortcodes.
	 *                         It is mainly used to display a preview of an HTML template.
	 *                         The 'display' filter is the default, and is used to preview
	 *                         the editor content and the web version of newsletters. It does
	 *                         not map shortcodes like [boldermail_name] to their Sendy
	 *                         equivalent yet, but does map local shortcodes [boldermail_permission].
	 *                         The 'api' filter maps shortcodes to their Sendy equivalent
	 *                         for insertion into the Sendy database.
	 * @return string
	 */
	public function apply_shortcodes( $content, $filter = 'display' ) {

		global $post;

		// Save filter.
		$this->filter = $filter;

		/**
		 * Always filter block template shortcodes.
		 *
		 * @since 2.0.0
		 */
		add_shortcode( 'boldermail_block_template_body', array( $this, 'block_template_body' ) );
		add_shortcode( 'boldermail_block_template_style', array( $this, 'block_template_style' ) );
		add_shortcode( 'boldermail_html_comment', array( $this, 'html_comment' ) );

		/**
		 * The 'raw' filter removes all of Boldermail shortcodes,
		 * but it still applies all other WordPress and plugin shortcodes.
		 * It is mainly used to display a preview of an HTML template.
		 *
		 * @since 1.7.0
		 */
		if ( 'raw' === $this->filter ) {
			boldermail()->shortcodes->remove_shortcodes();
		}

		if ( $post ) {
			$content = do_shortcode( $content );
		} else {
			$content = '';
		}

		// Reset shortcodes.
		if ( 'raw' === $this->filter ) {
			boldermail()->shortcodes->add_shortcodes();
		}

		return $content;

	}

	/**
	 * Add Boldermail shortcodes.
	 *
	 * @since 1.0.0
	 */
	public function add_shortcodes() {

		add_shortcode( 'boldermail_site_title', array( $this, 'site_title_shortcode' ) );
		add_shortcode( 'boldermail_rss_loop', array( $this, 'rss_loop_shortcode' ) );
		add_shortcode( 'boldermail_title', array( $this, 'title_shortcode' ) );
		add_shortcode( 'boldermail_subject', array( $this, 'subject_shortcode' ) );
		add_shortcode( 'boldermail_excerpt', array( $this, 'excerpt_shortcode' ) );
		add_shortcode( 'boldermail_thumbnail', array( $this, 'thumbnail_shortcode' ) );
		add_shortcode( 'boldermail_content', array( $this, 'content_shortcode' ) );
		add_shortcode( 'boldermail_permalink', array( $this, 'permalink_shortcode' ) );
		add_shortcode( 'boldermail_company_name', array( $this, 'company_name_shortcode' ) );
		add_shortcode( 'boldermail_company_address', array( $this, 'company_address_shortcode' ) );
		add_shortcode( 'boldermail_permission', array( $this, 'permission_shortcode' ) );
		add_shortcode( 'boldermail_confirm', array( $this, 'confirm_shortcode' ) );
		add_shortcode( 'boldermail_unsubscribe', array( $this, 'unsubscribe_shortcode' ) );
		add_shortcode( 'boldermail_resubscribe', array( $this, 'resubscribe_shortcode' ) );
		add_shortcode( 'boldermail_email', array( $this, 'email_shortcode' ) );
		add_shortcode( 'boldermail_name', array( $this, 'name_shortcode' ) );
		add_shortcode( 'boldermail_last_name', array( $this, 'last_name_shortcode' ) );
		add_shortcode( 'boldermail_company', array( $this, 'company_shortcode' ) );
		add_shortcode( 'boldermail_city', array( $this, 'city_shortcode' ) );
		add_shortcode( 'boldermail_state', array( $this, 'state_shortcode' ) );
		add_shortcode( 'boldermail_zip_code', array( $this, 'zip_code_shortcode' ) );
		add_shortcode( 'boldermail_country', array( $this, 'country_shortcode' ) );
		add_shortcode( 'boldermail_phone', array( $this, 'phone_shortcode' ) );
		add_shortcode( 'boldermail_custom_field', array( $this, 'custom_field_shortcode' ) );
		add_shortcode( 'boldermail_current_year', array( $this, 'current_year_shortcode' ) );
		add_shortcode( 'boldermail_preview_text', array( $this, 'preview_text' ) );

	}

	/**
	 * Remove Boldermail shortcodes for template previewing.
	 *
	 * @since 1.0.0
	 */
	public function remove_shortcodes() {

		remove_shortcode( 'boldermail_site_title' );
		remove_shortcode( 'boldermail_rss_loop' );
		remove_shortcode( 'boldermail_title' );
		remove_shortcode( 'boldermail_subject' );
		remove_shortcode( 'boldermail_excerpt' );
		remove_shortcode( 'boldermail_thumbnail' );
		remove_shortcode( 'boldermail_content' );
		remove_shortcode( 'boldermail_permalink' );
		remove_shortcode( 'boldermail_company_name' );
		remove_shortcode( 'boldermail_company_address' );
		remove_shortcode( 'boldermail_permission' );
		remove_shortcode( 'boldermail_confirm' );
		remove_shortcode( 'boldermail_unsubscribe' );
		remove_shortcode( 'boldermail_resubscribe' );
		remove_shortcode( 'boldermail_email' );
		remove_shortcode( 'boldermail_name' );
		remove_shortcode( 'boldermail_last_name' );
		remove_shortcode( 'boldermail_company' );
		remove_shortcode( 'boldermail_city' );
		remove_shortcode( 'boldermail_state' );
		remove_shortcode( 'boldermail_zip_code' );
		remove_shortcode( 'boldermail_country' );
		remove_shortcode( 'boldermail_phone' );
		remove_shortcode( 'boldermail_custom_field' );
		remove_shortcode( 'boldermail_social' );
		remove_shortcode( 'boldermail_current_year' );
		remove_shortcode( 'boldermail_preview_text' );

	}

	/**
	 * Load required files to process the shortcodes.
	 *
	 * @since 1.0.0
	 */
	private function load_dependencies() {

		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/shortcodes/class-boldermail-shortcode-autoembed.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/shortcodes/class-boldermail-shortcode-embed.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/shortcodes/class-boldermail-shortcode-gallery.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/shortcodes/class-boldermail-shortcode-instagram.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/shortcodes/class-boldermail-shortcode-tweet.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/shortcodes/class-boldermail-shortcode-vimeo.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/shortcodes/class-boldermail-shortcode-youtube.php';

	}

	/**
	 * Get the site title.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function site_title_shortcode() {
		return htmlspecialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}

	/**
	 * Get the RSS loop.
	 *
	 * @since  1.0.0
	 * @param  array  $atts    Shortcode attributes.
	 * @param  string $content Shortcode content (if any).
	 * @return string
	 */
	public function rss_loop_shortcode( $atts, $content = '' ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed */

		global $post;
		$old_post = $post;

		$newsletter = boldermail_get_newsletter( $post->ID );

		if ( ! $newsletter ) {
			return '';
		}

		if ( $newsletter->get_type() !== 'rss-feed' ) {
			return '';
		}

		$loop_posts = $newsletter->get_the_posts( $this->filter );

		$loop_content = '';

		if ( $loop_posts && count( $loop_posts ) > 0 ) {

			foreach ( $loop_posts as $loop_post ) {

				/**
				 * Setup the global $post variable.
				 *
				 * `setup_post_data()` does not set the global $post variable.
				 * It only sets the post information for loop functions like
				 * `the_content`. It will not display the correct
				 * information for outside-the-loop functions
				 * like `get_the_title` or `get_the_permalink`.
				 * The `global $post` variable is used for our shortcode functions.
				 * So we use this hack to properly set the $post variable
				 * and be able to use it in the shortcodes.
				 *
				 * @see https://wordpress.stackexchange.com/a/239175
				 * @see http://stephenharris.info/get-post-content-by-id/
				 * @see https://imbuzu.wordpress.com/2011/12/30/a-little-about-the-setup_postdata-wordpress-function/
				 * @see WP_Query::setup_postdata
				 * @see WP_Query::reset_postdata()
				 * @since 1.0.0
				 */
				boldermail_setup_postdata( $loop_post );

				$loop_content .= do_shortcode( $content );

			}

			// Reset the global $post variable.
			boldermail_setup_postdata( $old_post );

		}

		return $loop_content;

	}

	/**
	 * Get the title.
	 *
	 * Do not use get_title(), the_title(), etc. because WordPress
	 * converts single quotes into the HTML entity `&#8217;`, instead of `&#39;`.
	 * `html_specialchars_decode` does not convert `&#8217;` back into an
	 * apostrophe, which makes the emails subject look bad using `&#8217;`.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function title_shortcode() {

		global $post;
		$title = $post->post_title;

		return do_shortcode( $title );

	}

	/**
	 * Subject shortcode.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function subject_shortcode() {

		$newsletter = boldermail_get_newsletter( get_the_ID() );

		if ( ! $newsletter ) {
			return '';
		}

		return $newsletter->get_filtered_subject( 'raw', $this->filter );

	}

	/**
	 * Get the excerpt.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function excerpt_shortcode() {

		global $post;
		$excerpt = $post->post_excerpt;

		return ( $excerpt ) ? apply_filters( 'the_content', $excerpt ) : ''; /* phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound */

	}

	/**
	 * Get the thumbnail.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function thumbnail_shortcode() {
		return apply_filters( 'the_content', get_the_post_thumbnail() ); /* phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound */
	}

	/**
	 * Get the content.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function content_shortcode() {

		// Get unfiltered post content.
		global $post;
		$content = $post->post_content;

		/**
		 * Sanitize email content.
		 *
		 * @since 1.2.0
		 */
		$content = boldermail_kses_email( $content );

		/**
		 * Do custom video shortcodes.
		 *
		 * @since 1.2.0
		 */
		$content = $this->apply_video_shortcodes( $content );

		/**
		 * Apply regular filters once we have processed our shortcodes.
		 * Do not sanitize the content using `wp_kses_post`.
		 * The function will corrupt the output of any base64 images,
		 * and of our own shortcodes.
		 *
		 * @since 1.0.0
		 */
		$content = apply_filters( 'the_content', $content ); /* phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound */

		return $content;

	}

	/**
	 * Get the permalink.
	 *
	 * @since  1.0.0
	 * @param  array  $atts    Shortcode attributes.
	 * @param  string $content Shortcode content (if any).
	 * @return string
	 */
	public function permalink_shortcode( $atts, $content = '' ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed */

		global $post;

		$post_type = $post->post_type;

		$post_type_object = get_post_type_object( $post_type );

		if ( $post_type_object->publicly_queryable ) {

			if ( $content ) {

				/**
				 * Use Sendy's web version for newsletters to be able to show
				 * customizations based on shortcodes.
				 *
				 * Check whether this is an AJAX request (when visualizing in
				 * the editor), or if we are in the frontend of the website
				 * seeing a preview. Otherwise, assume we are in the state of
				 * sending the data to Sendy, and use the `webversion` tag.
				 *
				 * For RSS feeds, the campaign will convert the permalink to
				 * the link of the blog post prior to inserting it into
				 * Newsletters. Therefore, there is no need to add more logic.
				 *
				 * @since 1.6.0
				 */
				if ( 'api' === $this->filter ) {
					return '<a href="[webversion]">' . do_shortcode( $content ) . '</a>';
				} else {
					return '<a href="' . esc_url( get_the_permalink() ) . '" target="_blank">' . wp_kses_post( do_shortcode( $content ) ) . '</a>';
				}

			} else {

				if ( 'api' === $this->filter ) {
					return '[webversion]';
				} else {
					return esc_url( get_the_permalink() );
				}

			}

		}

		return '';

	}

	/**
	 * Company name shortcode.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function company_name_shortcode() {

		$object = boldermail_get_object( get_the_ID() );

		if ( ! $object ) {
			return '';
		}

		if ( ! method_exists( $object, 'get_company_name' ) ) {
			return '';
		}

		return $object->get_company_name();

	}

	/**
	 * Company address shortcode.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function company_address_shortcode() {

		$object = boldermail_get_object( get_the_ID() );

		if ( ! $object ) {
			return '';
		}

		if ( ! method_exists( $object, 'get_company_address' ) ) {
			return '';
		}

		return $object->get_company_address();

	}

	/**
	 * Permission reminder.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function permission_shortcode() {

		$object = boldermail_get_object( get_the_ID() );

		if ( ! $object ) {
			return '';
		}

		if ( ! method_exists( $object, 'get_permission' ) ) {
			return '';
		}

		return $object->get_permission();

	}

	/**
	 * Preview text.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function preview_text() {

		$object = boldermail_get_object( get_the_ID() );

		if ( ! $object ) {
			return '';
		}

		if ( ! method_exists( $object, 'get_preview_text' ) ) {
			return '';
		}

		return $object->get_preview_text();

	}

	/**
	 * Subscriber email.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function email_shortcode() {

		if ( 'api' !== $this->filter ) {
			return '[boldermail_email]';
		}

		return '[Email]';

	}

	/**
	 * Subscriber name.
	 *
	 * @since  1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public function name_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'fallback' => '',
			),
			$atts
		);

		if ( 'api' !== $this->filter ) {
			$fallback = ( $atts['fallback'] ) ? " fallback=&quot;{$atts['fallback']}&quot;" : '';
			return '[boldermail_name' . $fallback . ']';
		}

		return "[Name,fallback={$atts['fallback']}]";

	}

	/**
	 * Subscriber last name.
	 *
	 * @since  1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public function last_name_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'fallback' => '',
			),
			$atts
		);

		if ( 'api' !== $this->filter ) {
			$fallback = ( $atts['fallback'] ) ? " fallback=&quot;{$atts['fallback']}&quot;" : '';
			return '[boldermail_last_name' . $fallback . ']';
		}

		return "[LastName,fallback={$atts['fallback']}]";

	}

	/**
	 * Subscriber company.
	 *
	 * @since  1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public function company_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'fallback' => '',
			),
			$atts
		);

		if ( 'api' !== $this->filter ) {
			$fallback = ( $atts['fallback'] ) ? " fallback=&quot;{$atts['fallback']}&quot;" : '';
			return '[boldermail_company' . $fallback . ']';
		}

		return "[Company,fallback={$atts['fallback']}]";

	}

	/**
	 * Subscriber city.
	 *
	 * @since  1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public function city_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'fallback' => '',
			),
			$atts
		);

		if ( 'api' !== $this->filter ) {
			$fallback = ( $atts['fallback'] ) ? " fallback=&quot;{$atts['fallback']}&quot;" : '';
			return '[boldermail_city' . $fallback . ']';
		}

		return "[City,fallback={$atts['fallback']}]";

	}

	/**
	 * Subscriber state.
	 *
	 * @since  1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public function state_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'fallback' => '',
			),
			$atts
		);

		if ( 'api' !== $this->filter ) {
			$fallback = ( $atts['fallback'] ) ? " fallback=&quot;{$atts['fallback']}&quot;" : '';
			return '[boldermail_state' . $fallback . ']';
		}

		return "[State,fallback={$atts['fallback']}]";

	}

	/**
	 * Subscriber zip code.
	 *
	 * @since  1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public function zip_code_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'fallback' => '',
			),
			$atts
		);

		if ( 'api' !== $this->filter ) {
			$fallback = ( $atts['fallback'] ) ? " fallback=&quot;{$atts['fallback']}&quot;" : '';
			return '[boldermail_zip_code' . $fallback . ']';
		}

		return "[ZipCode,fallback={$atts['fallback']}]";

	}

	/**
	 * Subscriber country.
	 *
	 * @since  1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public function country_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'fallback' => '',
			),
			$atts
		);

		if ( 'api' !== $this->filter ) {
			$fallback = ( $atts['fallback'] ) ? " fallback=&quot;{$atts['fallback']}&quot;" : '';
			return '[boldermail_country' . $fallback . ']';
		}

		return "[Country,fallback={$atts['fallback']}]";

	}

	/**
	 * Subscriber phone.
	 *
	 * @since  1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public function phone_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'fallback' => '',
			),
			$atts
		);

		if ( 'api' !== $this->filter ) {
			$fallback = ( $atts['fallback'] ) ? " fallback=&quot;{$atts['fallback']}&quot;" : '';
			return '[boldermail_phone' . $fallback . ']';
		}

		return "[Phone,fallback={$atts['fallback']}]";

	}

	/**
	 * Subscriber custom field.
	 *
	 * @since  1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public function custom_field_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'name'     => '',
				'fallback' => '',
			),
			$atts
		);

		if ( 'api' !== $this->filter ) {
			$name     = ( $atts['name'] ) ? " name=&quot;{$atts['name']}&quot;" : '';
			$fallback = ( $atts['fallback'] ) ? " fallback=&quot;{$atts['fallback']}&quot;" : '';

			return '[boldermail_custom_field' . $name . $fallback . ']';
		}

		$name = ( $atts['name'] ) ? boldermail_custom_field_to_tag( $atts['name'] ) : '';

		return ( $name ) ? "[{$name},fallback={$atts['fallback']}]" : '';

	}

	/**
	 * Confirmation link.
	 *
	 * @since  1.0.0
	 * @param  array  $atts    Shortcode attributes.
	 * @param  string $content Shortcode content (if any).
	 * @return string
	 */
	public function confirm_shortcode( $atts, $content = '' ) {

		$a = shortcode_atts(
			array(
				'class' => '',
				'style' => '',
			),
			$atts
		);

		return '<a class="' . esc_attr( $a['class'] ) . '" style="' . esc_attr( $a['style'] ) . '" href="[confirmation_link]">' . do_shortcode( $content ) . '</a>';

	}

	/**
	 * Unsubscribe link.
	 *
	 * Do not use <unsubscribe></unsubscribe> because Emogrifier
	 * does not translate it properly.
	 *
	 * @since  1.0.0
	 * @param  array  $atts    Shortcode attributes.
	 * @param  string $content Shortcode content (if any).
	 * @return string
	 */
	public function unsubscribe_shortcode( $atts, $content = '' ) {

		$a = shortcode_atts(
			array(
				'class' => '',
				'style' => '',
			),
			$atts
		);

		return '<a class="' . esc_attr( $a['class'] ) . '" style="' . esc_attr( $a['style'] ) . '" href="[unsubscribe]">' . do_shortcode( $content ) . '</a>';

	}

	/**
	 * Resubscribe link.
	 *
	 * Do not use <resubscribe></resubscribe> because Emogrifier
	 * does not translate it properly.
	 *
	 * @since  1.0.0
	 * @param  array  $atts    Shortcode attributes.
	 * @param  string $content Shortcode content (if any).
	 * @return string
	 */
	public function resubscribe_shortcode( $atts, $content = '' ) {

		$a = shortcode_atts(
			array(
				'class' => '',
				'style' => '',
			),
			$atts
		);

		return '<a class="' . esc_attr( $a['class'] ) . '" style="' . esc_attr( $a['style'] ) . '" href="[resubscribe]">' . do_shortcode( $content ) . '</a>';

	}

	/**
	 * Current year shortcode.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function current_year_shortcode() {

		global $post;

		$newsletter = boldermail_get_newsletter( $post );

		if ( $newsletter && $newsletter->get_type() === 'autoresponder' ) {

			// If on the public side or while doing an AJAX preview.
			if ( 'api' !== $this->filter ) {
				return gmdate( 'Y' );
			} else {
				return '[currentyear]';
			}

		}

		return gmdate( 'Y' );

	}

	/**
	 * Convert video shortcodes into images.
	 *
	 * @since  1.0.0
	 * @param  string $content Shortcode content (if any).
	 * @return string
	 */
	public function apply_video_shortcodes( $content ) {

		global $wp_embed, $shortcode_tags;

		/**
		 * Back up current registered shortcodes and clear them all out.
		 *
		 * @since 1.2.0
		 */
		$orig_shortcode_tags = $shortcode_tags;
		remove_all_shortcodes();

		/**
		 * Remove filter that processes the [embed] shortcode.
		 *
		 * @since 1.2.0
		 * @see   run_shortcode /wp-includes/class-wp-embed.php
		 */
		remove_filter( 'the_content', array( $wp_embed, 'run_shortcode' ), 8 );

		/**
		 * Remove filter that automatically converts any unlinked URLs
		 * that are on their own line to an embedded frame.
		 *
		 * @since 1.2.0
		 * @see   autoembed /wp-includes/class-wp-embed.php
		 * @see   Embeds    https://codex.wordpress.org/Embeds#Usage
		 */
		remove_filter( 'the_content', array( $wp_embed, 'autoembed' ), 8 );

		/**
		 * Process custom video shortcodes.
		 *
		 * @since 1.2.0
		 */
		add_shortcode( 'embed', array( 'Boldermail_Shortcode_Embed', 'do_shortcode' ) );
		add_shortcode( 'youtube', array( 'Boldermail_Shortcode_YouTube', 'do_shortcode' ) );
		add_shortcode( 'vimeo', array( 'Boldermail_Shortcode_Vimeo', 'do_shortcode' ) );
		add_shortcode( 'instagram', array( 'Boldermail_Shortcode_Instagram', 'do_shortcode' ) );
		add_shortcode( 'tweet', array( 'Boldermail_Shortcode_Tweet', 'do_shortcode' ) );
		add_shortcode( 'gallery', array( 'Boldermail_Shortcode_Gallery', 'do_shortcode' ) );

		$content = Boldermail_Shortcode_AutoEmbed::do_shortcode( $content );
		$content = do_shortcode( $content );

		remove_shortcode( 'embed' );
		remove_shortcode( 'youtube' );
		remove_shortcode( 'vimeo' );
		remove_shortcode( 'instagram' );
		remove_shortcode( 'tweet' );
		remove_shortcode( 'gallery' );

		/**
		 * Restore the filters.
		 *
		 * @since 1.2.0
		 */
		add_filter( 'the_content', array( $wp_embed, 'run_shortcode' ), 8 );
		add_filter( 'the_content', array( $wp_embed, 'autoembed' ), 8 );
		$shortcode_tags = $orig_shortcode_tags; /* phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited */

		return $content;

	}

	/**
	 * HTML comment.
	 *
	 * @since  2.0.0
	 * @param  array  $atts    Shortcode attributes.
	 * @param  string $content Shortcode content (if any).
	 * @return string
	 */
	public function html_comment( $atts, $content = '' ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed */

		return '<!--' . do_shortcode( html_entity_decode( $content ) ) . '-->';

	}

	/**
	 * Get the body of a block template.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function block_template_body() {

		global $post;

		if ( ! in_array( $post->post_type, [ 'bm_block_template', 'bm_template' ], true ) ) {
			return '';
		}

		$object = boldermail_get_object( $post );

		if ( ! $object ) {
			return '';
		}

		$block_template_content = $object->get_content();
		$block_template_content = str_replace( '<div class="boldermail_html_comment">[boldermail_html_comment]', '[boldermail_html_comment]', $block_template_content );
		$block_template_content = str_replace( '[/boldermail_html_comment]</div>', '[/boldermail_html_comment]', $block_template_content );

		return $block_template_content;

	}

	/**
	 * Get the style of a block template.
	 *
	 * @since  2.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public function block_template_style( $atts ) {

		$atts = shortcode_atts(
			array(
				'file' => '',
				'meta' => '',
			),
			$atts
		);

		global $post;

		if ( ! in_array( $post->post_type, [ 'bm_block_template', 'bm_template' ], true ) ) {
			return '';
		}

		$object = boldermail_get_object( $post );

		if ( ! $object ) {
			return '';
		}

		if ( file_exists( $atts['file'] ) ) {
			ob_start();
			include $atts['file'];
			return ob_get_clean();
		}

		if ( $atts['meta'] && method_exists( $object, "get_{$atts['meta']}" ) ) {
			return call_user_func( array( $object, "get_{$atts['meta']}" ) );
		}

		return '';

	}

}
