<?php
/**
 * Instagram Basic Display API.
 *
 * @link       https://www.boldermail.com/about/
 * @since      2.3.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Instagram_API class.
 *
 * @since 2.3.0
 */
class Boldermail_Instagram_API {

	/**
	 * User ID.
	 *
	 * @since 2.3.0
	 * @var   int Instagram user ID.
	 */
	private $user_id;

	/**
	 * Access token.
	 *
	 * @since 2.3.0
	 * @var   string Instagram access token.
	 */
	private $access_token;

	/**
	 * Initialize API library.
	 *
	 * @since 2.3.0
	 * @param int    $user_id      User ID.
	 * @param string $access_token Access token.
	 */
	public function __construct( $user_id, $access_token ) {

		$this->user_id      = $user_id;
		$this->access_token = $access_token;

	}

	/**
	 * Get an Instagram user profile.
	 *
	 * @since  2.3.0
	 * @param  string[] $fields Query string parameters.
	 * @return mixed
	 */
	public function get_user( $fields = [ 'account_type', 'id', 'media_count', 'username' ] ) {

		$fields = implode( ',', $fields );

		$user_profile = get_transient( "boldermail_instagram_api_get_user_{$this->user_id}_{$fields}" );

		if ( $user_profile ) {
			return $user_profile;
		}

		$user_profile = $this->safe_remote_get( "{$this->user_id}?fields={$fields}" );

		if ( ! is_wp_error( $user_profile ) ) {
			$user_profile['profile_url'] = "https://www.instagram.com/{$user_profile['username']}";
			set_transient( "boldermail_instagram_api_get_user_{$this->user_id}_{$fields}", $user_profile, 300 );
		}

		return $user_profile;

	}

	/**
	 * Get an Instagram media item.
	 *
	 * @since  2.3.0
	 * @param  string   $media_shortcode Media URL shortcode.
	 * @param  string[] $fields          Query string parameters.
	 * @return mixed
	 */
	public function get_media_by_shortcode( $media_shortcode, $fields = [ 'caption', 'id', 'media_type', 'media_url', 'permalink', 'thumbnail_url', 'timestamp', 'username', 'children' ] ) {

		$fields = implode( ',', $fields );

		$user_media = get_transient( "boldermail_instagram_api_get_user_media_{$this->user_id}_{$fields}" );

		if ( ! $user_media ) {
			$user_media = $this->safe_remote_get( "{$this->user_id}/media?fields={$fields}" );
		}

		if ( is_wp_error( $user_media ) ) {
			return $user_media;
		} else {
			set_transient( "boldermail_instagram_api_get_user_media_{$this->user_id}_{$fields}", $user_media, 300 );
		}

		$data = isset( $user_media['data'] ) ? $user_media['data'] : [];

		// Look for the media item that has the shortcode provided in the function arguments.
		foreach ( $data as $media_data ) {
			if ( isset( $media_data['permalink'] ) && strpos( $media_data['permalink'], $media_shortcode ) !== false ) {
				return $media_data;
			}
		}

		return new WP_Error( 'media_not_found' );

	}

	/**
	 * Get an Instagram media item.
	 *
	 * @since  2.3.0
	 * @param  string   $media_id Media ID.
	 * @param  string[] $fields   Query string parameters.
	 * @return mixed
	 */
	public function get_media( $media_id, $fields = [ 'caption', 'id', 'media_type', 'media_url', 'permalink', 'thumbnail_url', 'timestamp', 'username', 'children' ] ) {

		$fields = implode( ',', $fields );

		$media_data = get_transient( "boldermail_instagram_api_get_media_{$media_id}_{$fields}" );

		if ( $media_data ) {
			return $media_data;
		}

		$media_data = $this->safe_remote_get( "{$media_id}?fields={$fields}" );

		if ( ! is_wp_error( $media_data ) ) {
			set_transient( "boldermail_instagram_api_get_media_{$media_id}_{$fields}", $media_data, 300 );
		}

		return $media_data;

	}


	/**
	 * Refresh an Instagram access token.
	 *
	 * @since  2.3.0
	 * @return mixed
	 */
	public function refresh_token() {

		return $this->safe_remote_get( 'refresh_access_token?grant_type=ig_refresh_token' );

	}

	/**
	 * Get the raw response from a safe HTTP request to Instagram using the GET method.
	 *
	 * @since  2.3.0
	 * @param  string $path Site URL to retrieve relative to the Instagram API URL.
	 * @param  array  $args Request arguments.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	private function safe_remote_get( $path, $args = [] ) {

		// Assemble request arguments.
		$defaults = array(
			'timeout' => 60,
		);

		$args = wp_parse_args( $args, $defaults );

		// Add token to request URL.
		$url = add_query_arg( 'access_token', $this->access_token, "https://graph.instagram.com/{$path}" );

		// Retrieve the response from Instagram.
		$response = wp_safe_remote_get( $url, $args );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return new WP_Error( 'invalid_instagram_request' );
		}

		return json_decode( wp_remote_retrieve_body( $response ), true );

	}

}
