<?php
/**
 * Tools for checking if the current site is a duplicate.
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
 * Boldermail_Site class.
 *
 * @since 1.7.0
 */
class Boldermail_Site {

	/**
	 * Sets a flag in the database to record the site's URL. This is then checked
	 * to determine if we are on a duplicate site or the original/main site.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function set_duplicate_site_url_lock() {

		update_option( 'boldermail_siteurl', self::get_current_sites_duplicate_lock() );

	}

	/**
	 * Creates a URL to prevent duplicate newsletters from staging sites.
	 *
	 * The URL can not simply be the site URL, e.g. http://example.com, because
	 * WP Engine replaces all instances of the site URL in the database when
	 * creating a staging site. As a result, we obfuscate the URL by inserting
	 * '_[boldermail_siteurl]_' into the middle of it.
	 *
	 * We don't use a hash because keeping the URL in the value allows for
	 * viewing and editing the URL directly in the database.
	 *
	 * @since  1.7.0
	 * @return string The duplicate lock URL.
	 */
	public static function get_current_sites_duplicate_lock() {

		$site_url = self::get_site_url_from_source( 'current_wp_site' );
		$scheme   = parse_url( $site_url, PHP_URL_SCHEME ) . '://'; /* phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url */
		$site_url = str_replace( $scheme, '', $site_url );

		return $scheme . substr_replace( $site_url, '_[boldermail_siteurl]_', strlen( $site_url ) / 2, 0 );

	}

	/**
	 * Returns WordPress/Boldermail record of the site URL for this site.
	 *
	 * @since  1.7.0
	 * @param  string $source Takes values 'current_wp_site' or 'boldermail_install'.
	 * @return string
	 */
	public static function get_site_url_from_source( $source = 'current_wp_site' ) {

		// Let the default source be WP.
		if ( 'boldermail_install' === $source ) {
			$site_url = self::get_site_url();
		} elseif ( ! is_multisite() && defined( 'WP_SITEURL' ) ) {
			$site_url = WP_SITEURL;
		} else {
			$site_url = get_site_url();
		}

		return $site_url;

	}

	/**
	 * Returns Boldermail record of the site URL for this site
	 *
	 * @since  1.7.0
	 * @param  int    $blog_id Optional. Site ID. Default null (current site).
	 * @param  string $path    Optional. Path relative to the site URL. Default empty.
	 * @param  string $scheme  Optional. Scheme to give the site URL context. Accepts
	 *                         'http', 'https', 'login', 'login_post', 'admin', or
	 *                         'relative'. Default null.
	 * @return string          Site URL link with optional path appended.
	 */
	public static function get_site_url( $blog_id = null, $path = '', $scheme = null ) {

		if ( empty( $blog_id ) || ! is_multisite() ) {
			$url = get_option( 'boldermail_siteurl' );
		} else {
			switch_to_blog( $blog_id );
			$url = get_option( 'boldermail_siteurl' );
			restore_current_blog();
		}

		// Remove the prefix used to prevent the site URL being updated on WP Engine.
		$url = str_replace( '_[boldermail_siteurl]_', '', $url );

		$url = set_url_scheme( $url, $scheme );

		if ( ! empty( $path ) && is_string( $path ) && strpos( $path, '..' ) === false ) {
			$url .= '/' . ltrim( $path, '/' );
		}

		return apply_filters( 'boldermail_site_url', $url, $path, $scheme, $blog_id );

	}

	/**
	 * Checks if the WordPress site URL is the same as the URL for the site
	 * Boldermail normally runs on. Useful for checking if cron sending
	 * should be processed.
	 *
	 * @since  1.7.0
	 * @return bool
	 */
	public static function is_duplicate_site() {

		$wp_site_url_parts = wp_parse_url( self::get_site_url_from_source( 'current_wp_site' ) );
		$bm_site_url_parts = wp_parse_url( self::get_site_url_from_source( 'boldermail_install' ) );

		if ( ! isset( $wp_site_url_parts['path'] ) && ! isset( $bm_site_url_parts['path'] ) ) {
			$paths_match = true;
		} elseif ( isset( $wp_site_url_parts['path'] ) && isset( $bm_site_url_parts['path'] ) && $wp_site_url_parts['path'] === $bm_site_url_parts['path'] ) {
			$paths_match = true;
		} else {
			$paths_match = false;
		}

		if ( isset( $wp_site_url_parts['host'] ) && isset( $bm_site_url_parts['host'] ) && $wp_site_url_parts['host'] === $bm_site_url_parts['host'] ) {
			$hosts_match = true;
		} else {
			$hosts_match = false;
		}

		// Check the host and path, do not check the protocol/scheme to avoid
		// issues with WP Engine and other occasions where the WP_SITEURL constant
		// may be set, but being overridden (e.g. by FORCE_SSL_ADMIN).
		if ( $paths_match && $hosts_match ) {
			$is_duplicate = false;
		} else {
			$is_duplicate = true;
		}

		return apply_filters( 'boldermail_is_duplicate_site', $is_duplicate );

	}

}
