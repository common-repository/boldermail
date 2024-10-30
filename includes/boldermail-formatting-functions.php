<?php
/**
 * Main Boldermail formatting API.
 *
 * Handles many functions for formatting output.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.7.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

/**
 * Get allowed HTML tags for the templates.
 *
 * @since   1.0.0
 * @return  array   Allowed HTML tags.
 */
function boldermail_get_kses_allowed_template_html() {

	$allowed_tags = wp_kses_allowed_html( 'post' );

	return array_merge(
		$allowed_tags,
		array(
			'html'                     => array(
				'xmlns'   => 1,
				'xmlns:v' => 1,
				'xmlns:o' => 1,
			),
			'style'                    => array(
				'type' => 1,
			),
			'meta'                     => array(
				'charset'    => 1,
				'http-equiv' => 1,
				'content'    => 1,
				'name'       => 1,
			),
			'head'                     => 1,
			'body'                     => array(
				'align'            => 1,
				'dir'              => 1,
				'lang'             => 1,
				'xml:lang'         => 1,
				'aria-describedby' => 1,
				'aria-details'     => 1,
				'aria-label'       => 1,
				'aria-labelledby'  => 1,
				'aria-hidden'      => 1,
				'class'            => 1,
				'id'               => 1,
				'style'            => 1,
				'title'            => 1,
				'role'             => 1,
				'data-*'           => 1,
			),
			'center'                   => 1,
			'xml'                      => 1,
			'o:OfficeDocumentSettings' => 1,
			'o:AllowPNG'               => 1,
			'o:PixelsPerInch'          => 1,
		)
	);

}

/**
 * Get allowed HTML tags for email.
 *
 * @see     https://www.pinpointe.com/blog/email-campaign-html-and-css-support
 * @since   1.2.0
 */
function boldermail_get_kses_allowed_email_html() {

	return array(
		'a' => array(
			'class' => 1,
			'href' => 1,
			'id' => 1,
			'style' => 1,
			'target' => 1,
		),
		'b' => array(
			'class' => 1,
			'id' => 1,
			'style' => 1,
		),
		'br' => array(
			'class' => 1,
			'id' => 1,
			'style' => 1,
		),
		'div' => array(
			'align' => 1,
			'class' => 1,
			'dir' => 1,
			'id' => 1,
			'style' => 1,
		),
		'font' => array(
			'class' => 1,
			'color' => 1,
			'face' => 1,
			'id' => 1,
			'size' => 1,
			'style' => 1,
		),
		'h1' => array(
			'align' => 1,
			'class' => 1,
			'dir' => 1,
			'id' => 1,
			'style' => 1,
		),
		'h2' => array(
			'align' => 1,
			'class' => 1,
			'dir' => 1,
			'id' => 1,
			'style' => 1,
		),
		'h3' => array(
			'align' => 1,
			'class' => 1,
			'dir' => 1,
			'id' => 1,
			'style' => 1,
		),
		'h4' => array(
			'align' => 1,
			'class' => 1,
			'dir' => 1,
			'id' => 1,
			'style' => 1,
		),
		'h5' => array(
			'align' => 1,
			'class' => 1,
			'dir' => 1,
			'id' => 1,
			'style' => 1,
		),
		'h6' => array(
			'align' => 1,
			'class' => 1,
			'dir' => 1,
			'id' => 1,
			'style' => 1,
		),
		// 		'head' => array(
			// 			'dir' => 1,
			// 			'lang' => 1,
			// 		),
		'hr' => array(
			'align' => 1,
			'size' => 1,
			'width' => 1,
		),
		'img' => array(
			'align' => 1,
			'border' => 1,
			'class' => 1,
			'height' => 1,
			'hspace' => 1,
			'id' => 1,
			'src' => 1,
			'style' => 1,
			'usemap' => 1,
			'vspace' => 1,
			'width' => 1,
		),
		'label' => array(
			'class' => 1,
			'id' => 1,
			'style' => 1,
		),
		'li' => array(
			'class' => 1,
			'dir' => 1,
			'id' => 1,
			'style' => 1,
			'type' => 1,
		),
		'ol' => array(
			'class' => 1,
			'dir' => 1,
			'id' => 1,
			'style' => 1,
			'type' => 1,
		),
		'p' => array(
			'align' => 1,
			'dir' => 1,
			'id' => 1,
			'style' => 1,
		),
		'span' => array(
			'class' => 1,
			'id' => 1,
			'style' => 1,
		),
		'strong' => array(
			'class' => 1,
			'id' => 1,
			'style' => 1,
		),
		'table' => array(
			'align' => 1,
			'bgcolor' => 1,
			'border' => 1,
			'cellpadding' => 1,
			'cellspacing' => 1,
			'class' => 1,
			'dir' => 1,
			'frame' => 1,
			'id' => 1,
			'rules' => 1,
			'style' => 1,
			'width' => 1,
		),
		'td' => array(
			'abbr' => 1,
			'align' => 1,
			'bgcolor' => 1,
			'class' => 1,
			'colspan' => 1,
			'dir' => 1,
			'height' => 1,
			'id' => 1,
			'lang' => 1,
			'rowspan' => 1,
			'scope' => 1,
			'style' => 1,
			'valign' => 1,
			'width' => 1,
		),
		'th' => array(
			'abbr' => 1,
			'align' => 1,
			'background' => 1,
			'bgcolor' => 1,
			'class' => 1,
			'colspan' => 1,
			'dir' => 1,
			'height' => 1,
			'id' => 1,
			'lang' => 1,
			'scope' => 1,
			'style' => 1,
			'valign' => 1,
			'width' => 1,
		),
		'tr' => array(
			'align' => 1,
			'bgcolor' => 1,
			'class' => 1,
			'dir' => 1,
			'id' => 1,
			'style' => 1,
			'valign' => 1,
		),
		'u' => array(
			'class' => 1,
			'id' => 1,
			'style' => 1,
		),
		'ul' => array(
			'class' => 1,
			'dir' => 1,
			'id' => 1,
			'style' => 1,
		),
	);

}

/**
 * Sanitize the email newsletter.
 *
 * @since   1.0.0
 * @param   string $data  Email HTML to filter.
 * @return  string        Filtered email HTML with allowed HTML tags and attributes intact.
 */
function boldermail_kses_email( $data ) {

	$allowed_html = boldermail_get_kses_allowed_email_html();

	$allowed_protocols = wp_allowed_protocols();

	return wp_kses( $data, $allowed_html, $allowed_protocols );

}

/**
 * Sanitize the template HTML.
 *
 * We don't call `wp_kses` because it replaces content like
 * `<!--[if gte mso 15]>` to `<!--[if gte mso 15]&gt;-->`
 * when it calls the function `wp_kses_split`.
 * Here, we recreate the function to avoid errors in our template.
 *
 * @see     https://developer.wordpress.org/reference/functions/wp_kses/
 * @since   1.0.0
 * @param   string $data  Template HTML to filter.
 * @return  string        Filtered template HTML with allowed HTML tags and attributes intact.
 */
function boldermail_kses_template( $data ) {

	$allowed_html = boldermail_get_kses_allowed_template_html();

	$allowed_protocols = wp_allowed_protocols();

	$data = wp_kses_no_null( $data, array( 'slash_zero' => 'keep' ) );
	$data = wp_kses_normalize_entities( $data );
	$data = wp_kses_hook( $data, $allowed_html, $allowed_protocols );

	// @todo
	// return wp_kses_split( $data, $allowed_html, $allowed_protocols );

	return $data;

}

/**
 * Sanitize post.
 *
 * @since   1.0.0
 * @param   string $post  Post content.
 * @return  string
 */
function boldermail_kses_post( $post ) {

	$kses_defaults = wp_kses_allowed_html( 'post' );

	$svg_args = array(
		'svg'   => array(
			'class'           => true,
			'aria-hidden'     => true,
			'aria-labelledby' => true,
			'role'            => true,
			'xmlns'           => true,
			'width'           => true,
			'height'          => true,
			'fill'            => true,
			'viewbox'         => true, // Must be lower case!
		),
		'g'     => array( 'fill' => true ),
		'title' => array( 'title' => true ),
		'path'  => array(
			'd'    => true,
			'fill' => true,
		),
	);

	return wp_kses( wp_unslash( $post ), array_merge( $kses_defaults, $svg_args ) );

}

/**
 * Display translated text that has been sanitized for allowed HTML tags
 * for post content.
 *
 * @since   1.3.0
 * @param   string $text    Text to translate.
 * @param   string $domain  Text domain. Unique identifier for retrieving translated strings.
 */
function boldermail_kses_post_e( $text, $domain = 'default' ) {

	echo wp_kses_post( translate( $text, $domain ) );

}

/**
 * Clean variables using `sanitize_text_field`. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @since   1.7.0
 * @param   string|array $var   Data to sanitize.
 * @return  string|array
 */
function boldermail_clean( $var ) {

	if ( is_array( $var ) ) {
		return array_map( 'boldermail_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}

}

/**
 * Sanitize key.
 *
 * @since   1.0.0
 * @param   string $key   Key to sanitize.
 * @return  string
 */
function boldermail_sanitize_key( $key ) {
	return sanitize_key( wp_unslash( $key ) );
}

/**
 * Sanitize URL.
 *
 * @since   1.0.0
 * @param   string $url   URL to sanitize.
 * @return  string
 */
function boldermail_sanitize_url( $url ) {
	return esc_url_raw( wp_unslash( $url ) );
}

/**
 * Sanitize text field.
 *
 * @since   1.0.0
 * @param   string $text  Text to sanitize.
 * @return  string
 */
function boldermail_sanitize_text( $text ) {
	return sanitize_text_field( wp_unslash( $text ) );
}

/**
 * Sanitize textarea.
 *
 * @since   1.0.0
 * @param   string $text  Text to sanitize.
 * @return  string
 */
function boldermail_sanitize_textarea( $text ) {
	return sanitize_textarea_field( wp_unslash( $text ) );
}

/**
 * Sanitize integer.
 *
 * @since   1.0.0
 * @param   int $integer  Value to sanitize.
 * @return  string
 */
function boldermail_sanitize_int( $integer ) {
	return is_numeric( wp_unslash( $integer ) ) ? absint( wp_unslash( $integer ) ) : '';
}

/**
 * Sanitize options (radio buttons, checkboxes).
 *
 * @since  1.0.0
 * @param  string   $value          Option to sanitize.
 * @param  string[] $allowed_values Allowed options.
 * @return string
 */
function boldermail_sanitize_option( $value, $allowed_values ) {

	if ( ! $value ) {
		return '';
	}

	if ( in_array( wp_unslash( $value ), $allowed_values ) ) { /* phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict */
		return wp_unslash( $value );
	}

	return '';

}

/**
 * Sanitize email address.
 *
 * @since   1.0.0
 * @param   string $email   Email to sanitize.
 * @return  string
 */
function boldermail_sanitize_email( $email ) {
	return sanitize_email( wp_unslash( $email ) );
}

/**
 * Sanitize a date.
 *
 * `Y-m-d H:i:s` is equivalent to yyyy-mm-dd hh:mm:ss in PHP.
 *
 * @since   1.0.0
 * @param   string $date    Date to sanitize.
 * @param   string $format  Date format.
 * @return  string
 */
function boldermail_sanitize_date( $date, $format = 'Y-m-d H:i:s' ) {

	if ( class_exists( 'DateTime' ) && $date ) {

		$d = DateTime::createFromFormat( $format, $date );

		if ( $d && $d->format( $format ) == $date ) {
			return $date;
		}

	}

	return '';

}

/**
 * Sanitize a string destined to be a tooltip.
 * Tooltips are encoded with htmlspecialchars to prevent XSS.
 * Should not be used in conjunction with esc_attr().
 *
 * @since   1.6.0
 * @param   string $var   Data to sanitize.
 * @return  string
 */
function boldermail_sanitize_tooltip( $var ) {

	return htmlspecialchars(
		wp_kses(
			html_entity_decode( $var ),
			array(
				'br'     => array(),
				'code'   => array(),
				'em'     => array(),
				'strong' => array(),
				'small'  => array(),
				'span'   => array(),
				'ul'     => array(),
				'li'     => array(),
				'ol'     => array(),
				'p'      => array(),
			)
		)
	);

}

/**
 * Implode and escape HTML attributes for output.
 *
 * @since  2.3.0
 * @param  array $raw_attributes Attribute name value pairs.
 * @return string
 */
function boldermail_implode_html_attributes( $raw_attributes ) {

	$attributes = array();

	foreach ( $raw_attributes as $name => $value ) {
		$attributes[] = esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
	}

	return implode( ' ', $attributes );

}

