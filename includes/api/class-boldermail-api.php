<?php
/**
 * The Sendy API.
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
 * Boldermail_API class.
 *
 * @since 1.7.0
 */
class Boldermail_API {

	/**
	 * The Sendy application URL.
	 *
	 * @since 1.7.0
	 * @var   string Sendy application URL.
	 */
	private $app_url;

	/**
	 * The Sendy application API key.
	 *
	 * @since 1.7.0
	 * @var   string Sendy application API key.
	 */
	private $api_key;

	/**
	 * The Sendy application brand ID.
	 *
	 * @since 1.7.0
	 * @var   int Sendy application brand ID.
	 */
	private $app_id;

	/**
	 * Initialize API library.
	 *
	 * @since 1.7.0
	 * @param string $app_url Application URL.
	 * @param string $api_key Application API key.
	 * @param string $app_id  Application ID.
	 */
	public function __construct( $app_url, $api_key, $app_id ) {

		$this->app_url = $app_url;
		$this->api_key = $api_key;
		$this->app_id  = $app_id;

	}

	/**
	 * Verify the API key.
	 *
	 * @since  1.7.0
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function verify_key() {

		return $this->safe_remote_post( 'api/login/verify-api-key.php', $this->build_urlencoded_request() );

	}

	/**
	 * Validate the campaign.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function validate_campaign( $params ) {

		if ( ! ( isset( $params['from_name'] ) && boldermail_sanitize_text( $params['from_name'] ) ) ) {
			return new WP_Error( 'no_from_name' );
		}

		if ( ! ( isset( $params['from_email'] ) && boldermail_sanitize_email( $params['from_email'] ) ) ) {
			return new WP_Error( 'no_from_email' );
		}

		if ( ! ( isset( $params['reply_to'] ) && boldermail_sanitize_email( $params['reply_to'] ) ) ) {
			return new WP_Error( 'no_reply_to' );
		}

		if ( ! ( isset( $params['subject'] ) && boldermail_sanitize_text( $params['subject'] ) ) ) {
			return new WP_Error( 'no_subject' );
		}

		if ( ! ( isset( $params['html_text'] ) && $params['html_text'] ) ) {  // Already sanitized.
			return new WP_Error( 'no_html' );
		}

		return array();

	}

	/**
	 * Send the campaign.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function send_campaign( $params ) {

		return $this->safe_remote_post( 'api/campaigns/create.php', $this->build_urlencoded_request( $params ) );

	}

	/**
	 * Delete the campaign.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function delete_campaign( $params ) {

		return $this->safe_remote_post( 'api/campaigns/delete.php', $this->build_urlencoded_request( $params ) );

	}

	/**
	 * Get the campaign data.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function get_campaign_data( $params ) {

		$response = $this->safe_remote_post( 'api/campaigns/get.php', $this->build_urlencoded_request( $params ) );

		if ( ! is_wp_error( $response ) ) {

			// Extract data and sanitize.
			return $this->build_newsletter_data( current( json_decode( $response['body'], true ) ) );

		} else {

			return $response;

		}

	}

	/**
	 * Add an autoresponder email.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function add_ares_email( $params ) {

		return $this->safe_remote_post( 'api/autoresponders-emails/add.php', $this->build_urlencoded_request( $params ) );

	}

	/**
	 * Delete an autoresponder email.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function delete_ares_email( $params ) {

		return $this->safe_remote_post( 'api/autoresponders-emails/delete.php', $this->build_urlencoded_request( $params ) );

	}

	/**
	 * Update an autoresponder email.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function update_ares_email( $params ) {

		return $this->safe_remote_post( 'api/autoresponders-emails/update.php', $this->build_urlencoded_request( $params ) );

	}

	/**
	 * Get the campaign data for an autoresponder email.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function get_ares_email_data( $params ) {

		$response = $this->safe_remote_post( 'api/autoresponders-emails/get.php', $this->build_urlencoded_request( $params ) );

		if ( ! is_wp_error( $response ) ) {

			// Extract data and sanitize.
			return $this->build_newsletter_ares_data( current( json_decode( $response['body'], true ) ) );

		} else {

			return $response;

		}

	}

	/**
	 * Add a list.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function add_list( $params ) {

		return $this->safe_remote_post( 'api/lists/add.php', $this->build_urlencoded_request( $params ) );

	}

	/**
	 * Update a list.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function update_list( $params ) {

		return $this->safe_remote_post( 'api/lists/update.php', $this->build_urlencoded_request( $params ) );

	}

	/**
	 * Add an autoresponder.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function add_autoresponder( $params ) {

		return $this->safe_remote_post( 'api/autoresponders/add.php', $this->build_urlencoded_request( $params ) );

	}

	/**
	 * Delete an autoresponder.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function delete_autoresponder( $params ) {

		return $this->safe_remote_post( 'api/autoresponders/delete.php', $this->build_urlencoded_request( $params ) );

	}

	/**
	 * Subscribe or update subscriber.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function subscribe( $params ) {

		return $this->safe_remote_post( 'subscribe', $this->build_urlencoded_request( $params ) );

	}

	/**
	 * Unsubscribe.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function unsubscribe( $params ) {

		return $this->safe_remote_post( 'unsubscribe', $this->build_urlencoded_request( $params ) );

	}

	/**
	 * Delete subscriber.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function delete_subscriber( $params ) {

		return $this->safe_remote_post( 'api/subscribers/delete.php', $this->build_urlencoded_request( $params ) );

	}

	/**
	 * Import subscribers via CSV file.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function import_subscribers( $params ) {

		return $this->safe_remote_post( 'api/lists/import-update.php', $this->build_multipart_request( $params ) );

	}

	/**
	 * Get subscriber data.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function get_subscriber_data( $params ) {

		$response = $this->safe_remote_post( 'api/subscribers/get.php', $this->build_urlencoded_request( $params ) );

		if ( ! is_wp_error( $response ) ) {

			// Extract data and sanitize.
			return $this->build_subscriber_data( current( json_decode( $response['body'], true ) ) );

		} else {

			return $response;

		}

	}

	/**
	 * Get subscribers data from a list.
	 *
	 * @since  1.7.0
	 * @param  array $params API parameters.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	public function get_list_subscribers_data( $params ) {

		$response = $this->safe_remote_post( 'api/lists/get-subscribers.php', $this->build_urlencoded_request( $params ) );

		if ( ! is_wp_error( $response ) ) {

			// Extract data.
			$subscribers_data = json_decode( $response['body'], true );

			// Sanitize data of first entry (in case of duplicates).
			foreach ( $subscribers_data as &$subscriber_data ) {
				$subscriber_data = $this->build_subscriber_data( $subscriber_data );
			}

			return $subscribers_data;

		} else {

			return $response;

		}

	}

	/**
	 * Assemble request arguments.
	 *
	 * @since  1.7.0
	 * @param  array $params Optional. API parameters.
	 * @return array         API parameters + defaults.
	 */
	private function parse_args( $params = array() ) {

		$default_params = array(
			'api_key'  => boldermail_sanitize_text( $this->api_key ),
			'brand_id' => boldermail_sanitize_int( $this->app_id ),
		);

		return wp_parse_args( $params, $default_params );

	}

	/**
	 * Assemble the arguments for a `application/x-www-form-urlencoded` request.
	 *
	 * @since  1.7.0
	 * @param  array $params Optional. API parameters.
	 * @return array         Request arguments.
	 */
	private function build_urlencoded_request( $params = array() ) {

		$params = $this->parse_args( $params );

		$headers = array(
			'content-type' => 'application/x-www-form-urlencoded',
		);

		$body = http_build_query( $params );

		return array(
			'headers' => $headers,
			'body'    => $body,
		);

	}

	/**
	 * Assemble the argument for a `multipart/form-data` request.
	 *
	 * @since  1.7.0
	 * @param  array $params Optional. API parameters.
	 * @return array         Request arguments.
	 */
	private function build_multipart_request( $params = array() ) {

		$params = $this->parse_args( $params );

		$boundary = wp_generate_password( 24 );

		$headers = array(
			'content-type' => 'multipart/form-data; boundary=' . $boundary,
		);

		$body = '';

		foreach ( $params as $key => $value ) {

			if ( 'csv_file' === $key ) {

				// Upload the file.
				$body .= '--' . $boundary;
				$body .= "\r\n";
				$body .= 'Content-Disposition: form-data; name="csv_file"; filename="' . $value['name'] . '"' . "\r\n";
				$body .= "\r\n";
				$body .= file_get_contents( $value['tmp_name'] ); /* phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents */
				$body .= "\r\n";

			} else {

				// Add the standard POST fields.
				$body .= '--' . $boundary;
				$body .= "\r\n";
				$body .= 'Content-Disposition: form-data; name="' . $key . '"' . "\r\n\r\n";
				$body .= $value;
				$body .= "\r\n";

			}

		}

		$body .= '--' . $boundary . '--';

		return array(
			'headers' => $headers,
			'body'    => $body,
		);

	}

	/**
	 * Get the raw response from a safe HTTP request using the POST method.
	 *
	 * @since  1.7.0
	 * @param  string $path Site URL to retrieve relative to Boldermail installation.
	 * @param  array  $args Request arguments.
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	private function safe_remote_post( $path, $args = array() ) {

		// Assemble request arguments.
		$defaults = array(
			'timeout' => 60,
		);

		$args = wp_parse_args( $args, $defaults );

		// Log request.
		if ( boldermail_doing_debug() ) {
			error_log( serialize( $args ) ); /* phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log, WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize */
		}

		// Get sanitized URL.
		$app_url = esc_url_raw( $this->app_url );

		// Retrieve the response only if on production site.
		if ( ! Boldermail_Site::is_duplicate_site() ) {
			$response = wp_safe_remote_post( "{$app_url}/{$path}", $args );
		} else {
			$response = new Boldermail_Error( 'not_production_site' );
		}

		// Log response.
		if ( boldermail_doing_debug() ) {
			error_log( serialize( json_decode( wp_remote_retrieve_body( $response ), true ) ) ); /* phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log, WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize */
		}

		// Check if WP or Boldermail errors.
		$error = boldermail_maybe_get_error( $response );

		if ( is_wp_error( $error ) ) {
			return $error;
		}

		return $response;

	}

	/**
	 * Assemble and sanitize the newsletter data received from Boldermail.
	 *
	 * @since  1.7.0
	 * @param  array $data Raw newsletter data.
	 * @return array       Sanitized newsletter data.
	 */
	private function build_newsletter_data( $data ) {

		// Only for regular campaigns.
		if ( isset( $data['to_send'] ) && is_numeric( $data['to_send'] ) ) {
			$data['to_send'] = boldermail_sanitize_int( $data['to_send'] );
		}

		$data['recipients'] = ( isset( $data['recipients'] ) && is_numeric( $data['recipients'] ) ) ? boldermail_sanitize_int( $data['recipients'] ) : 0;

		// Convert campaign opens data from string (Sendy) to array (Boldermail).
		if ( isset( $data['opens'] ) && '' !== $data['opens'] ) {

			// 11031:US -- example
			$opens = explode( ',', $data['opens'] );

			foreach ( $opens as &$open ) {

				$open_map = explode( ':', $open );

				$open_data = array();

				$open_data['subscriber_id'] = ( isset( $open_map[0] ) ) ? boldermail_sanitize_int( $open_map[0] ) : 0;
				$open_data['country']       = ( isset( $open_map[1] ) ) ? boldermail_sanitize_text( $open_map[1] ) : '';

				$open = $open_data;

			}

			$data['opens'] = $opens;

		} else {

			$data['opens'] = array();

		}

		if ( isset( $data['links'] ) ) {

			foreach ( $data['links'] as &$links ) {

				$links['link'] = ( isset( $links['link'] ) ) ? boldermail_sanitize_url( $links['link'] ) : '';

				if ( isset( $links['clicks'] ) ) {

					$clicks = explode( ',', $links['clicks'] );

					foreach ( $clicks as &$click ) {
						$click = boldermail_sanitize_int( $click );
					}

					$links['clicks'] = $clicks;

				} else {

					$links['clicks'] = array();

				}

			}

		} else {

			$data['links'] = array();

		}

		return $data;

	}

	/**
	 * Assemble and sanitize the autoresponder newsletter data received from Boldermail.
	 *
	 * @since  1.7.0
	 * @param  array $data Raw newsletter data.
	 * @return array       Sanitized newsletter data.
	 */
	private function build_newsletter_ares_data( $data ) {

		$enabled = $data['enabled'];

		$newsletter_data = $this->build_newsletter_data( $data );

		$newsletter_data['enabled'] = boldermail_sanitize_option( $enabled, array( 0, 1 ) );

		return $newsletter_data;

	}

	/**
	 * Assemble and sanitize the subscriber data received from Boldermail.
	 *
	 * @since  1.7.0
	 * @param  array $data Raw subscriber data.
	 * @return array       Sanitized subscriber data.
	 */
	private function build_subscriber_data( $data ) {

		$data['list']    = ( isset( $data['list'] ) ) ? boldermail_sanitize_text( $data['list'] ) : '';
		$data['list_id'] = $data['list'];

		$list = boldermail_get_list_from_id( $data['list_id'] );

		if ( $list ) {
			$data['list_post_id'] = $list->get_post_id();
		}

		$data['name']          = ( isset( $data['name'] ) ) ? boldermail_sanitize_text( $data['name'] ) : '';
		$data['email']         = ( isset( $data['email'] ) ) ? boldermail_sanitize_email( $data['email'] ) : '';
		$data['status']        = boldermail_get_subscriber_status( $data );
		$data['last_campaign'] = ( isset( $data['last_campaign'] ) ) ? boldermail_sanitize_int( $data['last_campaign'] ) : '';
		$data['last_ares']     = ( isset( $data['last_ares'] ) ) ? boldermail_sanitize_int( $data['last_ares'] ) : '';
		$data['added_via']     = ( isset( $data['added_via'] ) ) ? boldermail_sanitize_int( $data['added_via'] ) : '';
		$data['optin_method']  = ( isset( $data['method'] ) ) ? boldermail_sanitize_int( $data['method'] ) : '';
		$data['join_date']     = ( isset( $data['join_date'] ) ) ? boldermail_sanitize_text( $data['join_date'] ) : '';
		$data['timestamp']     = ( isset( $data['timestamp'] ) ) ? boldermail_sanitize_int( $data['timestamp'] ) : '';
		$data['ip']            = ( isset( $data['ip'] ) ) ? boldermail_sanitize_text( $data['ip'] ) : '';
		$data['country']       = ( isset( $data['country'] ) ) ? boldermail_sanitize_text( $data['country'] ) : '';
		$data['referer']       = ( isset( $data['referrer'] ) ) ? boldermail_sanitize_text( $data['referrer'] ) : '';
		$data['gdpr']          = ( isset( $data['gdpr'] ) ) ? boldermail_sanitize_int( $data['gdpr'] ) : '';

		$custom_fields           = array_merge( Boldermail_List::get_default_fields(), $list->get_custom_fields() );
		$raw_custom_fields       = ( isset( $data['custom_fields'] ) ) ? explode( '%s%', $data['custom_fields'] ) : array();
		$sanitized_custom_fields = array();

		$num_custom_fields = count( $custom_fields );

		for ( $i = 0; $i < $num_custom_fields; $i++ ) {

			switch ( $custom_fields[ $i ]['type'] ) {

				case 'Text':
					$sanitized_custom_fields[ $custom_fields[ $i ]['name'] ] = ( isset( $raw_custom_fields[ $i ] ) ) ? boldermail_sanitize_text( $raw_custom_fields[ $i ] ) : '';
					break;

				case 'Number':
					$sanitized_custom_fields[ $custom_fields[ $i ]['name'] ] = ( isset( $raw_custom_fields[ $i ] ) ) ? boldermail_sanitize_int( $raw_custom_fields[ $i ] ) : '';
					break;

				case 'Date':
					// Dates are stored as UNIX timestamps in Sendy.
					$date = ( isset( $raw_custom_fields[ $i ] ) ) ? boldermail_sanitize_int( $raw_custom_fields[ $i ] ) : '';

					if ( $date ) {

						try {
							$dt = new DateTime( '@' . $date );

							if ( $timezone = get_option( 'timezone_string' ) ) { /* phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found */
								$dt->setTimeZone( new DateTimeZone( $timezone ) );
							}

							$sanitized_custom_fields[ $custom_fields[ $i ]['name'] ] = $dt->format( 'Y-m-d' );
						} catch ( Exception $e ) {
							$sanitized_custom_fields[ $custom_fields[ $i ]['name'] ] = '';
						}

					} else {

						$sanitized_custom_fields[ $custom_fields[ $i ]['name'] ] = '';

					}

					break;

				default:
					$sanitized_custom_fields[ $custom_fields[ $i ]['name'] ] = '';
					break;

			}

		}

		$data['custom_fields'] = $sanitized_custom_fields;

		return $data;

	}

}
