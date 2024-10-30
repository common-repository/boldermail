<?php
/**
 * Twitter v2 API.
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
 * Boldermail_Twitter_API class.
 *
 * @since 2.3.0
 */
class Boldermail_Twitter_API {

	/**
	 * API username.
	 *
	 * @since 2.3.0
	 * @var   string Boldermail API username.
	 */
	private $username;

	/**
	 * API password.
	 *
	 * @since 2.3.0
	 * @var   string Boldermail API password.
	 */
	private $password;

	/**
	 * Initialize API library.
	 *
	 * @since 2.3.0
	 * @param string $username Username.
	 * @param string $password Password.
	 */
	public function __construct( $username, $password ) {

		$this->username = $username;
		$this->password = $password;

	}

	/**
	 * Get data from a tweet.
	 *
	 * @since  2.3.0
	 * @param  int $tweet_id Tweet ID.
	 * @return mixed
	 */
	public function get_tweet( $tweet_id ) {

		$tweet_data = get_transient( "boldermail_twitter_api_get_tweet_{$tweet_id}" );

		if ( $tweet_data ) {
			return $tweet_data;
		}

		$tweet_data = $this->safe_remote_get( "tweet/{$tweet_id}" );

		if ( ! is_wp_error( $tweet_data ) ) {
			set_transient( "boldermail_twitter_api_get_tweet_{$tweet_id}", $tweet_data, 300 );
		}

		return $tweet_data;

	}

	/**
	 * Get the raw response from a safe HTTP request to the Boldermail Twitter API endpoint using the GET method.
	 *
	 * @since  2.3.0
	 * @param  string $path Site URL to retrieve relative to the Instagram API URL.
	 * @param  array  $args Request arguments.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	private function safe_remote_get( $path, $args = [] ) {

		$args = array(
			'headers' => 'Authorization: Basic ' . base64_encode( "{$this->username}:{$this->password}" ), /* phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode */
			'timeout' => 60,
		);

		$wp_response = wp_remote_get( "https://api.boldermail.com/wp-json/boldermail/v1/{$path}", $args );

		if ( is_wp_error( $wp_response ) || 200 !== wp_remote_retrieve_response_code( $wp_response ) ) {
			return new WP_Error( 'invalid_twitter_request' );
		}

		// Decode again because in the body of the WP response is another HTTP response from Twitter.
		$tweet_response = json_decode( wp_remote_retrieve_body( $wp_response ), true );

		if ( is_wp_error( $tweet_response ) || 200 !== wp_remote_retrieve_response_code( $tweet_response ) ) {
			return new WP_Error( 'invalid_twitter_request' );
		}

		return json_decode( wp_remote_retrieve_body( $tweet_response ), true );

	}

}
