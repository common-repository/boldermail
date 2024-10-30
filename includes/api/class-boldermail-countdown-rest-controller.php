<?php
/**
 * Countdown timer endpoint.
 *
 * @link       https://www.boldermail.com/
 * @since      2.3.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Countdown_REST_Controller class.
 *
 * @since 2.3.0
 */
class Boldermail_Countdown_REST_Controller extends WP_REST_Controller {

	/**
	 * Registers the route to return a countdown timer GIF image.
	 *
	 * @since 2.3.0
	 */
	public function register_routes() {

		register_rest_route(
			'boldermail/v1',
			'/countdown/(?P<timestamp>\d+)',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_countdown' ],
				'permission_callback' => [ $this, 'get_countdown_permissions_check' ],
				'args'                => [
					'timestamp' => [
						'required'    => true,
						'type'        => 'string',
						'description' => 'The countdown\'s end time',
						'format'      => 'integer',
					],
				],
			]
		);

		add_filter( 'rest_pre_serve_request', [ $this, 'rest_pre_serve_request' ], 10, 4 );

	}

	/**
	 * Get the countdown GIF image.
	 *
	 * @since 2.3.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_countdown( $request ) {

		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/tools/class-boldermail-countdown-gif.php';

		$timestamp = $request['timestamp']; // If the user does not provide a timestamp, the REST API does not even call this function.

		$countdown_helper = new Boldermail_Countdown_GIF( $timestamp );
		$countdown_helper = $countdown_helper->get_animated_gif();

		$response = new WP_REST_Response( $countdown_helper, 200 );

		// Set the content type to a GIF image.
		$response->header( 'Content-Type', 'image/gif' );

		// Expire this image immediately and tell the browsers not to cache it.
		$response->header( 'Expires', 'Sat, 26 Jul 1997 05:00:00 GMT' ); // Same value MailerLite uses.
		$response->header( 'Last-Modified', gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		$response->header( 'Cache-Control', 'no-store, no-cache, must-revalidate' );
		$response->header( 'Cache-Control', 'post-check=0, pre-check=0', false );
		$response->header( 'Pragma', 'no-cache' );

		return $response;

	}

	/**
	 * This API endpoint returns a GIF image and everyone has access to it.
	 *
	 * @since  2.3.0
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error            True if the request has read access, WP_Error object otherwise.
	 */
	public function get_countdown_permissions_check( $request ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass */

		return true;

	}

	/**
	 * Send the request manually.
	 *
	 * WP_REST_Server::serve_request() calls WP_REST_Server::dispatch() which then
	 * calls the callback function registered in Boldermail_Countdown_REST_Controller::register_routes.
	 * Once Boldermail_Countdown_REST_Controller::get_countdown returns a response,
	 * the headers set in that response are sent and the filter `rest_pre_serve_request`
	 * is called to decide whether the user wants to output the result to the browser
	 * manually, or if the REST API should output the result. The problem is that the REST
	 * API always encodes the output as JSON. Since we are trying to output an image here,
	 * we output the result to the browser ourselves without any JSON encoding. There is
	 * no need to send any headers because those headers were set in the
	 * Boldermail_Countdown_REST_Controller::get_countdown response, and they were sent before
	 * this filter was called.
	 *
	 * @see https://gist.github.com/petenelson/6dc1a405a6e7627b4834
	 *
	 * @since 2.3.0
	 *
	 * @param bool             $served  Whether the request has already been served.
	 * @param WP_HTTP_Response $result  Result to send to the client. Usually a WP_REST_Response.
	 * @param WP_REST_Request  $request Request used to generate the response.
	 * @param WP_REST_Server   $server  Server instance.
	 *
	 * @return bool Whether the request has already been served.
	 */
	public function rest_pre_serve_request( $served, $result, $request, $server ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed */

		if ( preg_match( '/^\/boldermail\/v1\/countdown\/(\d+)$/', $request->get_route(), $matches ) ) {
			echo $result->get_data(); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
			return true;
		}

		return $served;

	}

}

add_action( 'rest_api_init', 'boldermail_countdown_rest_api_init' );
/**
 * Initialize the countdown timer REST controller.
 *
 * @since 2.3.0
 */
function boldermail_countdown_rest_api_init() {

	$countdown_rest_controller = new Boldermail_Countdown_REST_Controller();
	$countdown_rest_controller->register_routes();

}
