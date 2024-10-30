<?php
/**
 * Boldermail newsletter functions.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/class-boldermail-factory.php';

/**
 * Show hidden meta fields if debugging.
 *
 * @since 1.0.0
 */
if ( boldermail_doing_debug() ) {
	add_filter( 'is_protected_meta', '__return_false', 999 );
}

/**
 * Doing debugging?
 *
 * @since 1.0.0
 */
function boldermail_doing_debug() {
	return defined( 'WP_DEBUG' ) && true === WP_DEBUG;
}

/**
 * Print debug information.
 *
 * @since 1.0.0
 * @param string $debug_info The information to be printed.
 */
function boldermail_print_r( $debug_info ) {
	echo '<pre>' . htmlentities( print_r( $debug_info, true ) ) . '</pre>'; /* phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r, WordPress.Security.EscapeOutput.OutputNotEscaped */
}

/**
 * Get printed debug information.
 *
 * @since  1.0.0
 * @param  string $debug_info The information to be printed.
 * @return string
 */
function boldermail_get_print_r( $debug_info ) {

	ob_start();
	echo '<pre>' . htmlentities( print_r( $debug_info, true ) ) . '</pre>'; /* phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r, WordPress.Security.EscapeOutput.OutputNotEscaped */
	return ob_get_clean();
}

/**
 * Print error log.
 *
 * @since 1.0.0
 * @param string $error The error message that should be logged.
 */
function boldermail_error_log( $error ) {
	error_log( 'Boldermail Error: ' . $error ); /* phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log */
}

/**
 * Get a setting from the settings API.
 *
 * @since  1.7.0
 * @param  mixed $option_name Option name to save.
 * @param  mixed $default     Default value to save.
 * @return mixed
 */
function boldermail_get_option( $option_name, $default = '' ) {

	if ( ! class_exists( 'Boldermail_Settings', false ) ) {
		include BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-settings.php';
	}

	return Boldermail_Settings::get_option( $option_name, $default );

}

/**
 * Get the Boldermail client username from the URL.
 *
 * @since  2.3.0
 * @return string Subdomain.
 */
function boldermail_get_username() {

	return boldermail_get_subdomain( get_option( 'boldermail_url' ) );

}

/**
 * Get the subscriber/list/template/newsletter object.
 *
 * @since  1.0.0
 * @param  int|WP_Post|null $post Post ID or post object. Default is global $post.
 * @return mixed
 */
function boldermail_get_object( $post = null ) {

	$post_type = get_post_type( $post );

	switch ( $post_type ) {

		case 'bm_list':
			return boldermail_get_list( $post );

		case 'bm_newsletter':
		case 'bm_newsletter_rss':
		case 'bm_newsletter_ares':
			return boldermail_get_newsletter( $post );

		case 'bm_template':
			return boldermail_get_template( $post );

		case 'bm_block_template':
			return boldermail_get_block_template( $post );

		case 'bm_subscriber':
			return boldermail_get_subscriber( $post );

		case 'bm_autoresponder':
			return boldermail_get_autoresponder( $post );

		default:
			return null;

	}

}

/**
 * Get the newsletter object.
 *
 * @uses   Boldermail_Factory
 * @since  1.0.0
 * @param  mixed $newsletter Newsletter object or post ID of the newsletter.
 * @return Boldermail_Newsletter_Autoresponder|Boldermail_Newsletter_Regular|Boldermail_Newsletter_RSS_Feed|null|false
 */
function boldermail_get_newsletter( $newsletter = false ) {
	return Boldermail_Factory::get_object( 'newsletter', $newsletter );
}

/**
 * Get the template object.
 *
 * @uses   Boldermail_Factory
 * @since  1.0.0
 * @param  mixed $template Template object or post ID of the template.
 * @return Boldermail_Template|null|false
 */
function boldermail_get_template( $template = false ) {
	return Boldermail_Factory::get_object( 'template', $template );
}

/**
 * Get the block template object.
 *
 * @uses   Boldermail_Factory
 * @since  1.0.0
 * @param  mixed $block_template Block template object or post ID of the template.
 * @return Boldermail_Block_Template|null|false
 */
function boldermail_get_block_template( $block_template = false ) {
	return Boldermail_Factory::get_object( 'block_template', $block_template );
}

/**
 * Get the list object.
 *
 * @uses   Boldermail_Factory
 * @since  1.0.0
 * @param  mixed $list List object or post ID of the list.
 * @return Boldermail_List|null|false
 */
function boldermail_get_list( $list = false ) {
	return Boldermail_Factory::get_object( 'list', $list );
}

/**
 * Get a list object from the API ID.
 *
 * @since  1.0.0
 * @param  string $list_id List API ID.
 * @return Boldermail_List|NULL|false
 */
function boldermail_get_list_from_id( $list_id ) {

	$list_id = boldermail_sanitize_text( $list_id );

	if ( ! $list_id ) {
		return false;
	}

	$list_query = new WP_Query(
		array(
			'posts_per_page' => -1,
			'post_type'      => 'bm_list',
			'post_status'    => 'publish',
			'meta_key'       => '_id', /* phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key */
			'meta_value'     => $list_id, /* phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value */
		)
	);

	if ( 0 === $list_query->found_posts ) {
		return false;
	}

	wp_reset_postdata();

	return boldermail_get_list( $list_query->posts[0] );

}

/**
 * Get the Subscriber object.
 *
 * @uses   Boldermail_Factory
 * @since  1.0.0
 * @param  mixed  $subscriber Optional. Subscriber object or post ID of the subscriber. Defaults to global $post.
 * @param  string $filter     Optional. Type of filter to apply. Accepts 'raw', 'update'. Default 'raw'.
 * @return Boldermail_Subscriber|array|null
 */
function boldermail_get_subscriber( $subscriber = false, $filter = 'raw' ) {

	$subscriber = Boldermail_Factory::get_object( 'subscriber', $subscriber );

	if ( ! $subscriber ) {
		return null;
	}

	if ( 'update' === $filter ) {

		$subscriber_data = boldermail()->api->get_subscriber_data(
			array(
				'email'   => boldermail_sanitize_text( $subscriber->get_email() ),
				'list_id' => boldermail_sanitize_text( $subscriber->get_list_id() ),
			)
		);

		if ( ! is_wp_error( $subscriber_data ) ) {
			$subscriber->save( $subscriber_data );
		}

	}

	return $subscriber;

}

/**
 * Get a Subscriber object from an email and the List API ID.
 *
 * @since  1.0.0
 * @param  string $email   Email address.
 * @param  string $list_id List API ID.
 * @return Boldermail_Subscriber[]|false
 */
function boldermail_get_subscriber_from_email_and_list_id( $email, $list_id ) {

	$list = boldermail_get_list_from_id( $list_id );

	return boldermail_get_subscriber_from_email_and_list( $email, $list );

}

/**
 * Get a Subscriber object from an email and the List post ID.
 *
 * @since  1.0.0
 * @param  string $email Email address.
 * @param  mixed  $list  List object or post ID of the list.
 * @return Boldermail_Subscriber[]|false
 */
function boldermail_get_subscriber_from_email_and_list( $email, $list ) {

	if ( ! $email ) {
		return false;
	}

	$list = boldermail_get_list( $list );

	if ( ! $list ) {
		return false;
	}

	$subscriber_query = new WP_Query(
		array(
			'posts_per_page' => -1,
			'post_type'      => 'bm_subscriber',
			'title'          => $email,
			'post_parent'    => $list->get_post_id(),
			'post_status'    => array( 'publish', 'subscribed', 'unsubscribed', 'unconfirmed', 'bounced', 'complained' ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	if ( 0 === $subscriber_query->found_posts ) {
		return false;
	}

	wp_reset_postdata();

	$subscribers = array();

	foreach ( $subscriber_query->posts as $subscriber_post ) {
		$subscribers[] = boldermail_get_subscriber( $subscriber_post );
	}

	return $subscribers;

}

/**
 * Get a Subscriber duplicate, if it exists.
 *
 * @since  1.0.0
 * @param  mixed $subscriber Subscriber object or post ID of the autoresponder.
 * @return Boldermail_Subscriber|WP_Error|false
 */
function boldermail_maybe_get_subscriber_duplicate( $subscriber ) {

	global $wpdb;

	$subscriber = boldermail_get_subscriber( $subscriber );

	if ( ! $subscriber ) {
		return new WP_Error( 'no_object' );
	}

	// Check for duplicate subscriber.
	$subscriber_posts = get_posts(
		array(
			'posts_per_page' => -1,
			'post_type'      => 'bm_subscriber',
			'title'          => $subscriber->get_email(),
			'post_parent'    => $subscriber->get_list_post_id(),
			'post_status'    => array( 'publish', 'subscribed', 'unsubscribed', 'unconfirmed', 'bounced', 'complained' ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	// Do not add autoresponder if WP database error.
	if ( $wpdb->last_error ) {
		return new WP_Error( 'no_wp_database' );
	}

	// Return the original subscriber at the end because of 'orderby' => 'date'.
	if ( $subscriber_posts && count( $subscriber_posts ) > 1 ) {
		return boldermail_get_subscriber( end( $subscriber_posts ) );
	}

	return false;

}

/**
 * Get the autoresponder object.
 *
 * @uses   Boldermail_Factory
 * @since  1.0.0
 * @param  mixed $autoresponder Autoresponder object or post ID of the autoresponder.
 * @return Boldermail_Autoresponder|null|false
 */
function boldermail_get_autoresponder( $autoresponder = false ) {
	return Boldermail_Factory::get_object( 'autoresponder', $autoresponder );
}

/**
 * Get a Autoresponder duplicate, if it exists.
 *
 * @since  1.0.0
 * @param  mixed $autoresponder Autoresponder object or post ID of the autoresponder.
 * @return Boldermail_Autoresponder|WP_Error|false
 */
function boldermail_maybe_get_autoresponder_duplicate( $autoresponder ) {

	global $wpdb;

	$autoresponder = boldermail_get_autoresponder( $autoresponder );

	if ( ! $autoresponder ) {
		return new WP_Error( 'no_object' );
	}

	// Check for duplicate autoresponder.
	$autoresponder_posts = get_posts(
		array(
			'posts_per_page' => -1,
			'post_type'      => 'bm_autoresponder',
			'post_parent'    => $autoresponder->get_list_post_id(),
			'post_status'    => array( 'publish' ),
			'meta_key'       => '_type', /* phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key */
			'meta_value'     => $autoresponder->get_type(), /* phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value */
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	// Do not add autoresponder if WP database error.
	if ( $wpdb->last_error ) {
		return new WP_Error( 'no_wp_database' );
	}

	// Return the original autoresponder at the end because of 'orderby' => 'date'.
	if ( $autoresponder_posts && count( $autoresponder_posts ) > 1 ) {
		return boldermail_get_autoresponder( end( $autoresponder_posts ) );
	}

	return false;

}

/**
 * Get all List objects.
 *
 * @since  1.0.0
 * @return Boldermail_List[]
 */
function boldermail_get_lists() {

	$lists_query = new WP_Query(
		array(
			'posts_per_page' => -1,
			'post_type'      => 'bm_list',
			'post_status'    => 'publish',
		)
	);

	$lists = array();

	if ( $lists_query->posts ) {

		foreach ( $lists_query->posts as $list_post ) {

			$list = boldermail_get_list( $list_post );

			if ( $list ) {
				$lists[] = $list;
			}

		}

	}

	wp_reset_postdata();

	return $lists;

}

/**
 * Get the names of all List objects.
 *
 * @since  2.3.0
 * @return array
 */
function boldermail_get_lists_names() {

	$lists = boldermail_get_lists();

	$lists_names = array();

	foreach ( $lists as $list ) {
		$lists_names[ $list->get_list_id() ] = $list->get_name();
	}

	return $lists_names;

}

/**
 * Get all Autoresponder objects.
 *
 * @since  1.0.0
 * @return Boldermail_Autoresponder[]
 */
function boldermail_get_autoresponders() {

	$autoresponders_query = new WP_Query(
		array(
			'posts_per_page' => -1,
			'post_type'      => 'bm_autoresponder',
			'post_status'    => 'publish',
		)
	);

	$autoresponders = array();

	if ( $autoresponders_query->posts ) {

		foreach ( $autoresponders_query->posts as $autoresponder_post ) {

			$autoresponder = boldermail_get_autoresponder( $autoresponder_post );

			if ( $autoresponder ) {
				$autoresponders[] = $autoresponder;
			}

		}

	}

	wp_reset_postdata();

	return $autoresponders;

}

/**
 * Get the names of all Autoresponder objects.
 *
 * @since  2.3.0
 * @return array
 */
function boldermail_get_autoresponders_names() {

	$autoresponders = boldermail_get_autoresponders();

	$autoresponders_names = array();

	foreach ( $autoresponders as $autoresponder ) {
		$autoresponders_names[ $autoresponder->get_post_id() ] = $autoresponder->get_name();
	}

	return $autoresponders_names;

}

/**
 * Get the admin screen post type.
 *
 * @since  1.0.0
 * @return string Post type.
 */
function boldermail_get_current_screen_post_type() {

	global $pagenow;

	/* phpcs:disable WordPress.Security.NonceVerification.Recommended */

	if ( ( 'edit.php' === $pagenow || 'post-new.php' === $pagenow ) && isset( $_GET['post_type'] ) ) {
		return boldermail_sanitize_key( $_GET['post_type'] );
	}

	if ( 'post.php' === $pagenow && isset( $_GET['post'] ) ) {
		return get_post_type( boldermail_sanitize_key( $_GET['post'] ) );
	}

	/* phpcs:enable WordPress.Security.NonceVerification.Recommended */

	return '';

}

/**
 * Get all Boldermail screen IDs.
 *
 * @since  1.0.0
 * @return array
 */
function boldermail_get_screen_ids() {

	$screen_ids = array(
		'edit-bm_newsletter',
		'edit-bm_newsletter_rss',
		'edit-bm_newsletter_ares',
		'edit-bm_template',
		'edit-bm_block_template',
		'edit-bm_list',
		'edit-bm_subscriber',
		'edit-bm_autoresponder',
		'bm_newsletter',
		'bm_newsletter_rss',
		'bm_newsletter_ares',
		'bm_template',
		'bm_block_template',
		'bm_list',
		'bm_subscriber',
		'bm_autoresponder',
		'bm_newsletter_page_boldermail-settings',
		'profile',
		'user-edit',
	);

	return apply_filters( 'boldermail_screen_ids', $screen_ids );

}

/**
 * Get contents of include file.
 *
 * @since  1.0.0
 * @param  string $file File to include.
 * @return string
 */
function boldermail_get_include( $file ) {

	ob_start();
	include $file;

	$file_content = ob_get_clean();
	$file_content = str_replace( '%plugin_dir_url', BOLDERMAIL_PLUGIN_URL, $file_content );

	return $file_content;

}

/**
 * Get CSS from HTML block.
 *
 * @since  1.0.0
 * @param  string $html HTML code.
 * @return string
 */
function boldermail_extract_css( $html ) {

	$dom = new DOMDocument();
	@$dom->loadHTML( $html ); /* phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged */

	$css_nodes = $dom->getElementsByTagName( 'style' );

	$css = '';
	foreach ( $css_nodes as $css_node ) {
		$css .= $css_node->nodeValue; /* phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase */
	}

	return esc_textarea( $css );

}

use Pelago\Emogrifier\CssInliner;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;
/**
 * Inline the CSS content into the HTML content.
 *
 * @see    MyIntervals/emogrifier  https://github.com/MyIntervals/emogrifier
 * @since  1.0.0
 * @param  string $html HTML text.
 * @param  string $css  CSS text.
 * @return string
 */
function boldermail_inline_css( $html, $css ) {

	// Emogrifier will throw an error if HTML is empty.
	if ( '' === $html || '' === $css ) {
		return $html;
	}

	class_exists( '\Pelago\Emogrifier\CssInliner' ) || require_once BOLDERMAIL_PLUGIN_DIR . 'includes/plugins/emogrifier/autoload.php';

	/**
	 * Emogrifier uses the DOMDocument PHP class.
	 * At some point, DOMDocument saves the HTML and encodes the URLs
	 * in the text.
	 * This process converts non-valid URL characters like '[', ']', '{', '}',
	 * into their URL encoded counterparts, like '%5B', '%5D', etc.
	 * In our newsletter template, we use brackets to put placeholders
	 * for our Boldermail server to convert to links, like [webversion] and [unsubscribe].
	 * So we need to temporarily convert those "shortcodes" into valid URLs,
	 * and then convert them back to their original state to avoid conflicts.
	 *
	 * @since   1.0.0
	 */
	$html = str_replace( '[unsubscribe]', 'https://www.boldermail.com/#unsubscribe', $html );
	$html = str_replace( '[resubscribe]', 'https://www.boldermail.com/#resubscribe', $html );
	$html = str_replace( '[confirmation_link]', 'https://www.boldermail.com/#confirmation_link', $html );
	$html = str_replace( '[webversion]', 'https://www.boldermail.com/#webversion', $html );
	$html = str_replace( '[currentyear]', 'https://www.boldermail.com/#currentyear', $html );
	$html = str_replace( '[Name]', 'https://www.boldermail.com/#name', $html );
	$html = str_replace( '[Email]', 'https://www.boldermail.com/#email', $html );

	/**
	 * Permalinks for sharing icons.
	 *
	 * @since   1.2.2
	 */
	$html = str_replace( '[boldermail_permalink]', 'https://www.boldermail.com/#boldermail_permalink', $html );
	$html = str_replace( '[boldermail_title]', 'https://www.boldermail.com/#boldermail_title', $html );

	/**
	 * Build a new Emogrifier instance from the unprocessed HTML.
	 *
	 * @since   1.3.0
	 */
	$emogrifier = method_exists( '\Pelago\Emogrifier\CssInliner', 'fromHtml' ) ? CssInliner::fromHtml( $html ) : null;

	/**
	 * By default, Emogrifier will grab all <style> blocks in the HTML
	 * and will apply the CSS styles as inline "style" attributes to
	 * the HTML. The <style> blocks will then be removed from the
	 * HTML. If you want to disable this functionality so that
	 * Emogrifier leaves these <style> blocks in the HTML and does not
	 * parse them, you should use this option. If you use this option,
	 * the contents of the <style> blocks will not be applied as inline
	 * styles and any CSS you want Emogrifier to use *must* be passed in
	 * as described in the Usage section of the Github page.
	 *
	 * @since   1.0.0
	 */
	if ( $emogrifier && method_exists( $emogrifier, 'disableStyleBlocksParsing' ) ) {
		$emogrifier->disableStyleBlocksParsing();
	}

	/**
	 * Use Emogrifier to inline the CSS.
	 * If `disableStyleBlocksParsing` is called, then the CSS *must*
	 * be passed as an argument.
	 *
	 * @since   1.3.0
	 */
	if ( $emogrifier && method_exists( $emogrifier, 'inlineCss' ) && method_exists( $emogrifier, 'render' ) ) {
		try {
			$html = $emogrifier->inlineCss( $css )->render();
		} catch ( SyntaxErrorException $e ) {
			return '';
		}
	}

	/**
	 * Revert back to Boldermail tags.
	 *
	 * @since   1.0.0
	 */
	$html = str_replace( 'https://www.boldermail.com/#unsubscribe', '[unsubscribe]', $html );
	$html = str_replace( 'https://www.boldermail.com/#resubscribe', '[resubscribe]', $html );
	$html = str_replace( 'https://www.boldermail.com/#confirmation_link', '[confirmation_link]', $html );
	$html = str_replace( 'https://www.boldermail.com/#webversion', '[webversion]', $html );
	$html = str_replace( 'https://www.boldermail.com/#currentyear', '[currentyear]', $html );
	$html = str_replace( 'https://www.boldermail.com/#name', '[Name]', $html );
	$html = str_replace( 'https://www.boldermail.com/#email', '[Email]', $html );
	$html = str_replace( 'https://www.boldermail.com/#boldermail_permalink', '[boldermail_permalink]', $html );
	$html = str_replace( 'https://www.boldermail.com/#boldermail_title', '[boldermail_title]', $html );

	return $html;

}

use Soundasleep\Html2TextException;
/**
 * Convert HTML to text.
 *
 * @since  1.0.0
 * @param  string $html HTML to convert to plain-text.
 * @return string
 */
function boldermail_html2text( $html ) {

	require_once BOLDERMAIL_PLUGIN_DIR . 'includes/plugins/html2text/autoload.php';

	if ( method_exists( 'Soundasleep\Html2Text', 'convert' ) ) {
		try {
			return @Soundasleep\Html2Text::convert( $html ); /* phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged */
		} catch ( Html2TextException $e ) {
			return '';
		}
	} else {
		return '';
	}

}

/**
 * Display the HTML content in an <iframe>.
 *
 * @deprecated
 * @since 1.0.0
 * @param string $id   <iframe> ID.
 * @param string $html HTML code.
 */
function boldermail_preview_script( $id, $html ) {

	/* phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped */

	$preview_field = preg_replace( '/[^a-zA-Z0-9]/', '', (string) $id ); ?>

	<script>
	const <?php echo $preview_field; ?> = <?php echo wp_json_encode( $html ); ?>
	design_frame = top.frames["<?php echo $id; ?>"];
	design_frame.contentWindow.document.open();
	design_frame.contentWindow.document.write( <?php echo $preview_field; ?> );
	design_frame.contentWindow.document.close();
	</script>
	<?php

	/* phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped */

}

/**
 * Set up global $post data.
 *
 *  WP Cron does not setup the loop, nor the global $post variable.
 * The shortcodes used in the newsletter depend on the global $post
 * to get the data, so we set it here.
 *
 * @since 1.0.0
 * @param int|WP_Post|null $post Post ID or post object. Defaults to global $post.
 */
function boldermail_setup_postdata( $post = null ) {

	$post = get_post( $post );

	$GLOBALS['post'] = $post; /* phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited */

	setup_postdata( $post );

}

/**
 * Return the html selected attribute if stringified $value is found in array of stringified `$options`
 * or if stringified $value is the same as scalar stringified `$options`.
 *
 * @since  2.3.0
 * @param  string|int       $value   Value to find within options.
 * @param  string|int|array $options Options to go through when looking for value.
 * @return string
 */
function boldermail_selected( $value, $options ) {

	if ( is_array( $options ) ) {
		$options = array_map( 'strval', $options );
		return selected( in_array( (string) $value, $options, true ), true, false );
	}

	return selected( $value, $options, false );

}

/**
 * Check if input is checked or if it should be disabled.
 *
 * @since  1.2.0
 * @param  bool  $is_editable If the object is editable.
 * @param  mixed $checked     One of the values to compare.
 * @param  mixed $current     The other value to compare if not just true.
 * @param  bool  $echo        Whether to echo or just return the string.
 * @return string
 */
function boldermail_checked_or_disabled( $is_editable, $checked, $current, $echo = true ) {

	$checked = checked( $checked, $current, false );

	if ( $checked ) {
		if ( $echo ) {
			echo esc_attr( $checked );
		} else {
			return $checked;
		}
	} elseif ( ! $is_editable && ! $checked ) {
		if ( $echo ) {
			echo 'disabled';
		} else {
			return 'disabled';
		}
	}

	return '';

}

/**
 * Check if input is selected or if it should be disabled.
 *
 * @since   1.2.0
 * @param  bool  $is_editable If the object is editable.
 * @param  mixed $selected    One of the values to compare.
 * @param  mixed $current     The other value to compare if not just true.
 * @param  bool  $echo        Whether to echo or just return the string.
 * @return string
 */
function boldermail_selected_or_disabled( $is_editable, $selected, $current, $echo = true ) {

	$selected = is_array( $current ) ? in_array( $selected, $current, true ) : selected( $selected, $current, false );

	if ( $selected ) {
		if ( $echo ) {
			echo 'selected';
		} else {
			return 'selected';
		}
	} elseif ( ! $is_editable && ! $selected ) {
		if ( $echo ) {
			echo 'disabled';
		} else {
			return 'disabled';
		}
	}

	return '';

}

/**
 * Convert the newsletter status slug to a name.
 *
 * @since  1.0.0
 * @param  string $status Newsletter post status.
 * @return string
 */
function boldermail_get_newsletter_status_name( $status ) {

	switch ( $status ) {

		case 'preparing':
			return __( 'Preparing', 'boldermail' );

		case 'sending':
			return __( 'Sending', 'boldermail' );

		case 'sent':
			return __( 'Sent', 'boldermail' );

		case 'enabled':
			return __( 'Enabled', 'boldermail' );

		case 'paused':
			return __( 'Paused', 'boldermail' );

		default:
			return '';

	}

}

/**
 * Convert the subscriber status slug to a name.
 *
 * @since  1.0.0
 * @param  string $status Subscriber post status.
 * @return string
 */
function boldermail_get_subscriber_status_name( $status ) {

	switch ( $status ) {

		case 'subscribed':
			return __( 'Subscribed', 'boldermail' );

		case 'unsubscribed':
			return __( 'Unsubscribed', 'boldermail' );

		case 'unconfirmed':
			return __( 'Unconfirmed', 'boldermail' );

		case 'bounced':
			return __( 'Bounced', 'boldermail' );

		case 'complained':
			return __( 'Marked as Spam', 'boldermail' );

		default:
			return '';

	}

}

/**
 * Convert the autoresponder type to a name.
 *
 * @since  1.0.0
 * @param  int $type Autoresponder type.
 * @return string
 */
function boldermail_get_autoresponder_type_name( $type ) {

	switch ( $type ) {

		case 1:
			return __( 'Drip campaign', 'boldermail' );

		default:
			return '';

	}

}

/**
 * Convert the list custom field type to a name.
 *
 * @since  1.0.0
 * @param  string $type Custom field type.
 * @return string
 */
function boldermail_get_list_custom_field_type_name( $type ) {

	switch ( $type ) {

		case 'Text':
			return __( 'Text', 'boldermail' );

		case 'Number':
			return __( 'Number', 'boldermail' );

		case 'Date':
			return __( 'Date', 'boldermail' );

		default:
			return '';

	}

}

/**
 * Get the subscriber status
 *
 * @see    sendy/includes/subscribers/subscriber-info.php
 * @since  2.0.0
 * @param  array $subscriber_info List object or post ID of the list.
 * @return string
 */
function boldermail_get_subscriber_status( $subscriber_info ) {

	$status = '';

	if ( ! isset( $subscriber_info['unsubscribed'] ) || ! isset( $subscriber_info['bounced'] ) || ! isset( $subscriber_info['complaint'] ) || ! isset( $subscriber_info['confirmed'] ) ) {
		return $status;
	}

	$unsubscribed = $subscriber_info['unsubscribed'];
	$bounced      = $subscriber_info['bounced'];
	$complained   = $subscriber_info['complaint'];
	$confirmed    = $subscriber_info['confirmed'];

	if ( '0' === $unsubscribed ) {
		$status = 'subscribed';
	} elseif ( '1' === $unsubscribed ) {
		$status = 'unsubscribed';
	}

	if ( '1' === $bounced ) {
		$status = 'bounced';
	}

	if ( '1' === $complained ) {
		$status = 'complained';
	}

	if ( '1' !== $confirmed ) {
		$status = 'unconfirmed';
	}

	return $status;

}

/**
 * Returns an array of single-use query variable names that can be removed from a URL.
 *
 * @since   1.4.0
 * @return  array
 */
function boldermail_removable_query_args() {

	return array_merge( wp_removable_query_args(), array( 'resubscribed', 'unsubscribed' ) );

}

/**
 * Get custom fields formatted for database insertion in Sendy.
 *
 * @since   1.6.0
 * @param   array $custom_fields Custom fields data.
 * @return  string
 */
function boldermail_format_custom_fields( $custom_fields ) {

	// Extract `name` and `type` from custom fields.
	$custom_fields = boldermail_array_columns( $custom_fields, array( 'name', 'type' ) );

	// Convert `Number` type to `Text` type -- Sendy does not support numbers, and numbers are text anyway.
	$custom_fields = array_map(
		function( $custom_field ) {
			$custom_field['type'] = ( 'Number' === $custom_field['type'] ) ? 'Text' : $custom_field['type'];
			return $custom_field;
		},
		$custom_fields
	);

	// Format to Sendy string.
	$custom_fields = array_map(
		function( $custom_field ) {
			return implode( ':', $custom_field );
		},
		$custom_fields
	);
	$custom_fields = implode( '%s%', $custom_fields );

	return $custom_fields;

}

/**
 * Display a Boldermail help tip.
 *
 * @since  1.6.0
 * @param  string $tip        Help tip text.
 * @param  bool   $allow_html Allow sanitized HTML if true or escape.
 * @return string
 */
function boldermail_help_tip( $tip, $allow_html = false ) {

	if ( $allow_html ) {
		$tip = boldermail_sanitize_tooltip( $tip );
	} else {
		$tip = esc_attr( $tip );
	}

	return '<span class="boldermail-help-tip" data-tip="' . $tip . '"></span>';

}

/**
 * Return the values from multiple columns in the input array.
 *
 * @since  1.6.0
 * @param  array $input       Columns.
 * @param  array $column_keys Column keys.
 * @return array
 */
function boldermail_array_columns( array $input, array $column_keys ) {

	$keys = array_flip( $column_keys );

	return array_map(
		function( $a ) use( $keys ) { /* phpcs:ignore WordPress.WhiteSpace.ControlStructureSpacing.NoSpaceBeforeOpenParenthesis, WordPress.WhiteSpace.ControlStructureSpacing.NoSpaceAfterStructureOpen */
			return array_intersect_key( $a, $keys );
		},
		$input
	);

}

/**
 * Convert string to name safe for class.
 *
 * @since  1.6.0
 * @param  string $string String to convert to attribute style.
 * @return string
 */
function boldermail_string_to_attr( $string ) {

	return preg_replace( '/\W+/', '', strtolower( wp_strip_all_tags( $string ) ) );

}

/**
 * Convert custom field name to personalization tag for Sendy API.
 *
 * @since  1.6.0
 * @param  string $name String to convert to attribute style.
 * @return string
 */
function boldermail_custom_field_to_tag( $name ) {

	return str_replace( ' ', '', $name );

}

/**
 * Retrieve the Boldermail URL.
 *
 * @since  1.7.0
 * @param  string $path Path relative to the Boldermail URL.
 * @return string
 */
function boldermail_url( $path = '' ) {

	$url = get_option( 'boldermail_url' );

	if ( $path && is_string( $path ) ) {
		$url .= '/' . $path;
	}

	return $url;

}

/**
 * Get a list of all registered post type labels.
 *
 * @since  2.3.0
 * @param  array $args An array of key => value arguments to match against the post type objects.
 * @return array       An array of post type labels.
 */
function boldermail_get_post_types_labels( $args = array() ) {

	$post_types = get_post_types( $args, 'objects' );

	$options = array();
	foreach ( $post_types as $post_type ) {
		$options[ $post_type->name ] = $post_type->label;
	}

	return $options;

}

/**
 * Get a list of all registered taxonomy labels.
 *
 * @since  2.3.0
 * @param  array $args An array of key => value arguments to match against the taxonomy objects.
 * @return array       An array of taxonomy labels.
 */
function boldermail_get_taxonomies_labels( $args = array() ) {

	$taxonomies = get_taxonomies( $args, 'objects' );

	$options = array();
	foreach ( $taxonomies as $taxonomy ) {
		$options[ $taxonomy->name ] = $taxonomy->label;
	}

	return $options;

}

/**
 * Get the subdomain from an URL (e.g. `http://en.example.com` returns `en`).
 *
 * @since  2.3.0
 * @param  string $url URL to parse.
 * @return string      Subdomain.
 */
function boldermail_get_subdomain( $url ) {

	if ( ! $url ) {
		return '';
	}

	$parsed_url = wp_parse_url( $url );

	$host = explode( '.', $parsed_url['host'] );

	return $host[0];

}

/**
 * Round a number to the nearest thousandth.
 *
 * @see    https://stackoverflow.com/a/36365553/1991500
 * @since  2.3.0
 * @param  string|int $number Number.
 * @return string
 */
function boldermail_round_to_nearest_thousandth( $number ) {

	$number = absint( $number );

	if ( $number > 1000 ) {

		$x = round( $number );

		$x_number_format = number_format( $x );

		$x_array = explode( ',', $x_number_format );

		$x_parts = array( 'K', 'M', 'B', 'T' );

		$x_count_parts = count( $x_array ) - 1;

		$x_display  = $x_array[0] . ( 0 !== (int) $x_array[1][0] ? '.' . $x_array[1][0] : '' );
		$x_display .= $x_parts[ $x_count_parts - 1 ];

		return $x_display;

	}

	return $number;

}

/**
 * Truncate text to a desired number of characters.
 *
 * @see    https://stackoverflow.com/a/79986/1991500
 * @since  2.3.0
 * @param  string $string        Text to truncate.
 * @param  string $desired_width Approximate width.
 * @return string
 */
function boldermail_truncate_text( $string, $desired_width ) {

	$parts       = preg_split( '/([\s\n\r]+)/u', $string, null, PREG_SPLIT_DELIM_CAPTURE );
	$parts_count = count( $parts );

	$length    = 0;
	$last_part = 0;
	for ( ; $last_part < $parts_count; ++$last_part ) {
		$length += strlen( $parts[ $last_part ] );

		if ( $length > $desired_width ) {
			break;
		}
	}

	return implode( array_slice( $parts, 0, $last_part ) );

}

/**
 * Gets the available image sizes.
 *
 * @since  2.3.0
 * @param  string $name Image size identifier.
 * @return string|array
 */
function boldermail_get_image_sizes( $name = '' ) {

	$wp_additional_image_sizes = wp_get_additional_image_sizes();

	$sizes = array();

	$get_intermediate_image_sizes = get_intermediate_image_sizes();

	// Create the full array with sizes and crop info.
	foreach ( $get_intermediate_image_sizes as $_size ) {
		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ), true ) ) {
			$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
		} elseif ( isset( $wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $wp_additional_image_sizes[ $_size ]['width'],
				'height' => $wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $wp_additional_image_sizes[ $_size ]['crop'],
			);
		}
	}

	// Get only 1 size if found.
	if ( $name ) {
		return isset( $sizes[ $name ] ) ? $sizes[ $name ] : false;
	}

	return $sizes;

}
