<?php
/**
 * Error handler.
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
 * Errors class.
 *
 * @since 1.0.0
 */
class Boldermail_Errors {

	/**
	 * Maybe get error from Boldermail response.
	 *
	 * @since  1.0.0
	 * @param  WP_Error|array $response HTTP request response.
	 * @return WP_Error|false
	 */
	public static function maybe_get_error( $response ) {

		// Check if `WP_Error` from `wp_safe_remote_post` but add own messages.
		if ( $response && is_wp_error( $response ) ) {
			return new WP_Error( $response->get_error_code(), boldermail_get_error_message( $response->get_error_code() ) );
		}

		// Check HTTP status code for errors.
		if ( $response && is_array( $response ) && isset( $response['response']['code'] ) && 200 !== $response['response']['code'] ) {
			return new WP_Error( 'http_request_failed', boldermail_get_error_message( 'http_request_failed' ) );
		}

		// Check if response is empty.
		if ( $response && is_array( $response ) && isset( $response['body'] ) && '' === $response['body'] ) {
			return new WP_Error( 'no_body', boldermail_get_error_message( 'no_body' ) );
		}

		// Check response errors.
		$error_msg = '';

		if ( $response && is_array( $response ) && isset( $response['body'] ) ) {

			// For JSON output.
			if ( boldermail_is_json( $response['body'] ) ) {

				$error_msg = json_decode( $response['body'], true );
				$error_msg = ( isset( $error_msg['status'] ) ) ? $error_msg['status'] : '';

			} else {

				$error_msg = $response['body'];

			}

		}

		if ( ! $error_msg ) {
			return false;
		}

		$error_code = '';

		// Boldermail database error.
		if ( strpos( $error_msg, "Can't connect to database" ) !== false ) {
			$error_code = 'no_database';
		}

		switch ( $error_msg ) {

			// @see boldermail/api/campaigns/create.php
			case 'Campaign created':
			case 'Campaign created and now sending':
				// Do nothing -- success!
				break;

			// Boldermail custom message for invalid post transition.
			case 'Invalid post transition':
				$error_code = 'invalid_post_transition';
				break;

			// @see boldermail/api/
			case 'No data passed':
				$error_code = 'no_data';
				break;

			// @see boldermail/api/
			case 'Invalid API key':
				$error_code = 'invalid_api_key';
				break;

			// @see boldermail/api/
			case 'API key not passed':
				$error_code = 'no_api_key';
				break;

			// @see boldermail/api/campaigns/create.php
			case 'From name not passed':
				$error_code = 'no_from_name';
				break;

			// @see boldermail/api/campaigns/create.php
			case 'From email not passed':
				$error_code = 'no_from_email';
				break;

			// @see boldermail/api/campaigns/create.php
			case 'Reply to email not passed':
				$error_code = 'no_reply_to';
				break;

			// @see boldermail/api/campaigns/create.php
			case 'Subject not passed':
				$error_code = 'no_subject';
				break;

			// @see boldermail/api/campaigns/create.php
			case 'HTML not passed':
				$error_code = 'no_html';
				break;

			// @see boldermail/api/campaigns/create.php, boldermail/api/lists/update.php, boldermail/api/subscribers/subscription-status.php, boldermail/api/subscribers/delete.php
			case 'List or segment ID(s) not passed':
			case 'List ID not passed':
				$error_code = 'no_list_ids';
				break;

			// @see boldermail/api/subscribers/delete.php
			case 'One or more list IDs are invalid':
			case 'List does not exist':
				$error_code = 'invalid_list_ids';
				break;

			// @see boldermail/api/campaigns/create.php
			case 'One or more segment IDs are invalid':
				$error_code = 'invalid_segment_ids';
				break;

			// @see boldermail/api/campaigns/create.php
			case 'List or segment IDs does not belong to a single brand':
				$error_code = 'invalid_list_ids_brand';
				break;

			// @see boldermail/api/lists/add.php, boldermail/api/lists/update.php
			case 'Invalid brand ID':
				$error_code = 'invalid_app';
				break;

			// @see boldermail/api/lists/add.php, boldermail/api/campaigns/create.php
			case 'Brand ID not passed':
				$error_code = 'no_app_id';
				break;

			// @see boldermail/api/campaigns/create.php
			case 'Unable to create campaign':
				$error_code = 'create_campaign_failed';
				break;

			// @see boldermail/api/campaigns/create.php
			case 'Unable to create and send campaign':
				$error_code = 'send_campaign_failed';
				break;

			// @see boldermail/api/campaigns/create.php
			case 'Unable to calculate totals':
				$error_code = 'compute_totals_failed';
				break;

			// @see boldermail/api/campaigns/delete.php
			case 'Campaign ID not passed':
				$error_code = 'no_campaign_id';
				break;

			// @see boldermail/api/campaigns/delete.php
			case 'Unable to delete campaign':
				$error_code = 'delete_campaign_failed';
				break;

			// @see boldermail/api/campaigns/delete.php
			case 'Invalid campaign':
				$error_code = 'invalid_campaign';
				break;

			// Boldermail custom message for RSS feed.
			case 'Feed schedule not passed':
				$error_code = 'no_feed_schedule';
				break;

			// @see boldermail/api/lists/add.php
			case 'List name not passed':
				$error_code = 'no_list_name';
				break;

			// @see boldermail/api/lists/add.php
			case 'Unable to add list':
				$error_code = 'add_list_failed';
				break;

			// @see boldermail/api/lists/update.php
			case 'Unable to update list':
				$error_code = 'update_list_failed';
				break;

			// @see boldermail/subscribe
			case 'Some fields are missing.':
				$error_code = 'missing_fields';
				break;

			// @see boldermail/subscribe
			case 'Invalid email address.':
				$error_code = 'invalid_email';
				break;

			// @see boldermail/api/lists/import-update.php
			case 'Invalid list':
				$error_code = 'invalid_list';
				break;

			// @see boldermail/subscribe
			case 'Invalid list ID.':
				$error_code = 'invalid_list_id';
				break;

			// @see boldermail/subscribe
			case 'Already subscribed.':
				// Do nothing -- just a notification.
				break;

			// @see boldermail/subscribe
			case 'IP address is invalid.':
				$error_code = 'invalid_ip_address';
				break;

			// @see boldermail/subscribe
			case 'Country must be a valid 2 letter country code':
				$error_code = 'invalid_country_code';
				break;

			// @see boldermail/subscribe
			case 'Referrer is not a valid URL':
				$error_code = 'invalid_referrer';
				break;

			// @see boldermail/subscribe
			case 'Consent not given.':
				$error_code = 'invalid_gdpr';
				break;

			// @see boldermail/api/subscribers/delete.php, boldermail/api/subscribers/subscription-status.php
			case 'Email not passed':
			case 'Email address not passed':
				$error_code = 'no_email';
				break;

			// @see boldermail/api/subscribers/subscription-status.php, boldermail/api/subscribers/unsubscribe.php, boldermail/api/subscribers/delete.php
			case 'Email does not exist in list':
			case 'Email does not exist.':
			case 'Subscriber does not exist':
				$error_code = 'no_email_in_list';
				break;

			// @see boldermail/api/lists/import-update.php
			case 'File uploaded and importing':
				// Do nothing -- importing success!
				break;

			// @see boldermail/api/lists/import-update.php
			case 'Invalid CSV file':
				$error_code = 'invalid_file';
				break;

			// @see boldermail/api/lists/import-update.php
			case 'Could not upload file':
				$error_code = 'upload_file_failed';
				break;

			// @see boldermail/api/lists/import-update.php
			case 'Number of columns in CSV does not match CSV format example':
				$error_code = 'invalid_file_format';
				break;

			// @see boldermail/api/lists/import-update.php
			case 'Cron not setup':
				$error_code = 'no_cron';
				break;

			// @see boldermail/api/autoresponders/add.php
			case 'Autoresponder name not passed':
				$error_code = 'no_autoresponder_name';
				break;

			// @see boldermail/api/autoresponders/add.php
			case 'Autoresponder type not passed':
				$error_code = 'no_autoresponder_type';
				break;

			// @see boldermail/api/autoresponders/add.php
			case 'Unable to add autoresponder':
				$error_code = 'add_autoresponder_failed';
				break;

			// @see boldermail/api/autoresponders/delete.php
			case 'Invalid autoresponder':
				$error_code = 'invalid_autoresponder';
				break;

			// @see boldermail/api/autoresponders/delete.php
			case 'Unable to delete autoresponder':
				$error_code = 'delete_autoresponder_failed';
				break;

			// @see boldermail/api/autoresponders-emails/add.php
			case 'Unable to add autoresponder email':
				$error_code = 'add_autoresponder_email_failed';
				break;

			// @see boldermail/api/autoresponders-emails/add.php
			case 'Autoresponder ID not passed':
				$error_code = 'no_autoresponder_id';
				break;

			// @see boldermail/api/autoresponders-emails/update.php
			case 'Autoresponder Email ID not passed':
				$error_code = 'no_ares_email_id';
				break;

			// @see boldermail/api/autoresponders-emails/update.php
			case 'Invalid autoresponder email':
				$error_code = 'invalid_ares_email';
				break;

			// @see boldermail/api/autoresponders-emails/update.php
			case 'Unable to update autoresponder email':
				$error_code = 'update_ares_email_failed';
				break;

			// @see boldermail/api/autoresponders-emails/delete.php
			case 'Unable to delete autoresponder email':
				$error_code = 'delete_ares_email_failed';
				break;

			// Custom Boldermail error for automated emails.
			case 'Autoresponder trigger not passed':
				$error_code = 'no_autoresponder_trigger';
				break;

			// @see boldermail/api/campaigns/test-send.php
			case 'Test email not passed':
				$error_code = 'no_test_email';
				break;

		}

		if ( $error_code ) {
			return new WP_Error( $error_code, boldermail_get_error_message( $error_code ) );
		}

		return false;

	}

	/**
	 * Convert the error code to a user-friendly message.
	 *
	 * @since  1.0.0
	 * @param  string $error_code Error code.
	 * @return string             Error message.
	 */
	public static function get_user_friendly_message( $error_code ) {

		switch ( $error_code ) {

			case 'no_wp_database':
				return '<p>' . __( '<strong>The MySQL server has gone away.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'http_request_failed':
			case 'no_database':
				return '<p>' . __( '<strong>Failed to connect with your Boldermail installation.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'no_body':
				return '<p>' . __( '<strong>The API did not return a response.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'no_object':
				return '<p>' . __( '<strong>Failed to create the Boldermail object.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'no_data':
				return '<p>' . __( '<strong>No data was passed to the Boldermail server.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'invalid_post_transition':
				return '<p>' . __( '<strong>Invalid post transition.</strong> You cannot change the post status once the campaign has been created to avoid sending the campaign twice. However, your changes have been updated for the web version.', 'boldermail' ) . '</p>';

			case 'invalid_api_key':
				return '<p>' . __( '<strong>Invalid API key.</strong> The API key you provided in your Boldermail Settings is not valid. Please make sure you entered the correct API key in your Boldermail Settings.', 'boldermail' ) . '</p>';

			case 'invalid_api_key_settings':
				return '<p>' . __( '<strong>Invalid API key.</strong> The API key you provided is not valid. Please make sure you entered the correct API key.', 'boldermail' ) . '</p>';

			case 'no_api_key':
				return '<p>' . __( '<strong>No API key provided.</strong> Please make sure you entered the correct API key in your Boldermail Settings.', 'boldermail' ) . '</p>';

			case 'no_from_name':
				return '<p>' . __( '<strong>The "From Name" field was not provided.</strong> The "From Name" field is empty or not valid. Please make sure you entered the correct name in the Setup tab of the "Setup your Campaign" meta box <a href="#from_panel" class="boldermail-error-link">here</a>, and try creating your campaign again.', 'boldermail' ) . '</p>';

			case 'no_from_email':
				return '<p>' . __( '<strong>The "From Email" field was not provided.</strong> The "From Email" field is empty or not valid. Please make sure you entered the correct email address in the Setup tab of the "Setup your Campaign" meta box <a href="#from_panel" class="boldermail-error-link">here</a>, and try creating your campaign again.', 'boldermail' ) . '</p>';

			case 'no_reply_to':
				return '<p>' . __( '<strong>The "Reply To" field was not provided.</strong> The "Reply To" field is empty or not valid. Please make sure you entered the correct email address in the Setup tab of the "Setup your Campaign" meta box <a href="#from_panel" class="boldermail-error-link">here</a>, and try creating your campaign again.', 'boldermail' ) . '</p>';

			case 'no_subject':
				return '<p>' . __( '<strong>The "Subject" field was not provided.</strong> The "Subject" field for this email is empty or not valid. Please make sure you entered the correct subject line.', 'boldermail' ) . '</p>';

			case 'no_html':
				return '<p>' . __( '<strong>There was no HTML code associated with the newsletter.</strong> Please check the template design you chose has the correct HTML layout, and try creating your campaign again. If the error persists, please contact technical support.', 'boldermail' ) . '</p>';

			case 'no_list_ids':
				return '<p>' . __( '<strong>The ID of your subscribers list, or a segment of it was not provided.</strong> Please make sure you entered the correct list or segment IDs, and try again.', 'boldermail' ) . '</p>';

			case 'invalid_list':
				return '<p>' . __( '<strong>Invalid list ID.</strong> The list does not exist in the brand. Please make sure you entered the correct brand ID in the Boldermail Settings page, and try again.', 'boldermail' ) . '</p>';

			case 'invalid_list_ids':
				return '<p>' . __( '<strong>The recipients list ID is invalid.</strong> Please make sure you entered the correct list IDs in the Recipients tab of the "Setup your Campaign" meta box <a href="#to_panel" class="boldermail-error-link">here</a>, and try creating your campaign again.', 'boldermail' ) . '</p>';

			case 'invalid_segment_ids':
				return '<p>' . __( '<strong>The recipients segment ID is invalid.</strong> Please make sure you entered the correct segment IDs in the Recipients tab of the "Setup your Campaign" meta box <a href="#to_panel" class="boldermail-error-link">here</a>, and try creating your campaign again.', 'boldermail' ) . '</p>';

			case 'invalid_list_ids_brand':
				return '<p>' . __( '<strong>The list or segment ID belongs to multiple brands.</strong> Please specify the brand associated with your subscribers list in the Boldermail Settings page, and try creating your campaign again.', 'boldermail' ) . '</p>';

			case 'no_app_id':
				return '<p>' . __( '<strong>The brand ID associated with this list or campaign was not provided.</strong> Please make sure you entered the brand ID in the Boldermail Settings page, and try creating your campaign again.', 'boldermail' ) . '</p>';

			case 'invalid_app':
				return '<p>' . __( '<strong>The brand ID associated with this request is not valid.</strong> Please make sure you entered the correct brand ID in the Boldermail Settings page, and try again.', 'boldermail' ) . '</p>';

			case 'create_campaign_failed':
				return '<p>' . __( '<strong>Unable to create campaign.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'send_campaign_failed':
				return '<p>' . __( '<strong>Unable to create and send campaign.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'no_campaign_id':
				return '<p>' . __( '<strong>The campaign ID associated with this campaign was not provided.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'delete_campaign_failed':
				return '<p>' . __( '<strong>Unable to delete campaign.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'invalid_campaign':
				return '<p>' . __( '<strong>Invalid campaign ID.</strong> The campaign does not exist in the brand. Please make sure you entered the correct brand ID in the Boldermail Settings page, and try again.', 'boldermail' ) . '</p>';

			case 'compute_totals_failed':
				return '<p>' . __( '<strong>Unable to calculate total number of emails to send.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'no_feed_schedule':
				return '<p>' . __( '<strong>The feed schedule was not set properly.</strong> Please make sure you entered the correct feed schedule in the Feed tab of the "Setup your Campaign" meta box <a href="#feed_panel" class="boldermail-error-link">here</a>, and try creating your campaign again.', 'boldermail' ) . '</p>';

			case 'no_list_name':
				return '<p>' . __( '<strong>No list name was passed.</strong> Please make sure to provide a name for your list, and try creating your list again.', 'boldermail' ) . '</p>';

			case 'add_list_failed':
				return '<p>' . __( '<strong>Unable to add list.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'update_list_failed':
				return '<p>' . __( '<strong>Unable to update list.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'missing_fields':
				return '<p>' . __( '<strong>No email nor list ID passed.</strong> Please provide this information, and try again.', 'boldermail' ) . '</p>';

			case 'invalid_email':
				return '<p>' . __( '<strong>Invalid email address.</strong> Please make sure the email address is formatted correctly.', 'boldermail' ) . '</p>';

			case 'invalid_list_id':
				return '<p>' . __( '<strong>Invalid list ID.</strong> The list ID provided does not exist.', 'boldermail' ) . '</p>';

			case 'already_subscribed':
				return '';

			case 'invalid_ip_address':
				return '<p>' . __( '<strong>Invalid IP address.</strong> Please make sure the IP address is formatted correctly, and try again.', 'boldermail' ) . '</p>';

			case 'invalid_country_code':
				return '<p>' . __( '<strong>Invalid country code.</strong> Please make sure the country code is formatted correctly, and try again.', 'boldermail' ) . '</p>';

			case 'invalid_referrer':
				return '<p>' . __( '<strong>Invalid referrer URL.</strong> Please make sure the URL is formatted correctly, and try again.', 'boldermail' ) . '</p>';

			case 'invalid_gdpr':
				return '<p>' . __( '<strong>Invalid GDPR consent given.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'no_email':
				return '<p>' . __( '<strong>No email address passed.</strong> Please make sure you entered the correct email address, and try again.', 'boldermail' ) . '</p>';

			case 'no_email_in_list':
				return '<p>' . __( '<strong>The email address does not exist in this list.</strong> Please make sure you entered the correct email address, and try again.', 'boldermail' ) . '</p>';

			// @see partials/list/html-boldermail-list-bulk-subscriber-actions.php
			case 'no_file':
				return '<p>' . __( '<strong>No file was uploaded.</strong> Please, choose a CSV file and try submitting the form again. If the error persists, please contact technical support.', 'boldermail' ) . '</p>';

			case 'invalid_file':
				return '<p>' . __( '<strong>File error.</strong> Please, choose a CSV file and try submitting the form again. If the error persists, please contact technical support.', 'boldermail' ) . '</p>';

			case 'upload_file_failed':
				return '<p>' . __( '<strong>File upload to the Boldermail server failed.</strong> Please, contact technical support and ask the <code>uploads</code> folder permissions to be reset.', 'boldermail' ) . '</p>';

			case 'invalid_file_format':
				return '<p>' . __( '<strong>Incorrect file formatting.</strong> Please, format your file as the example shown below, and try uploading the file again.', 'boldermail' ) . '</p>';

			case 'add_subscriber_no_list':
				return '<p>' . __( '<strong>Error when trying to add a Subscriber.</strong> Subscribers can only be added through a List. Select a List and then click on "Add Subscriber".', 'boldermail' ) . '</p>';

			case 'no_cron':
				return '<p>' . __( '<strong>Cron is not running in your Boldermail server.</strong> Please contact support.', 'boldermail' ) . '</p>';

			case 'no_autoresponder_name':
				return '<p>' . __( '<strong>No autoresponder name was passed.</strong> Please make sure to provide a name for your autoresponder, and try creating your autoresponder again.', 'boldermail' ) . '</p>';

			case 'no_autoresponder_type':
				return '<p>' . __( '<strong>No autoresponder type was passed.</strong> Please make sure to provide a type for your autoresponder, and try creating your autoresponder again.', 'boldermail' ) . '</p>';

			case 'add_email_no_autoresponder':
				return '<p>' . __( '<strong>Error when trying to add an Automated email.</strong> Automated emails can only be added through an Autoresponder. Select an Autoresponder and then click on "Add Email".', 'boldermail' ) . '</p>';

			case 'add_autoresponder_failed':
				return '<p>' . __( '<strong>Unable to add autoresponder.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'no_autoresponder_id':
				return '<p>' . __( '<strong>The autoresponder ID associated with this autoresponder was not provided.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'no_autoresponder_trigger':
				return '<p>' . __( '<strong>The autoresponder trigger associated with this autoresponder was not provided.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'invalid_autoresponder':
				return '<p>' . __( '<strong>Invalid autoresponder ID.</strong> The autoresponder does not exist in the brand. Please make sure you entered the correct brand ID in the Boldermail Settings page, and try again.', 'boldermail' ) . '</p>';

			case 'delete_autoresponder_failed':
				return '<p>' . __( '<strong>Unable to delete autoresponder.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'duplicate_autoresponder':
				return '<p>' . __( '<strong>Autoresponder already exists.</strong> Please create a different type of autoresponder.', 'boldermail' ) . '</p>';

			case 'add_autoresponder_email_failed':
				return '<p>' . __( '<strong>Unable to add automated email.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'no_ares_email_id':
				return '<p>' . __( '<strong>The automated email ID associated with this email was not provided.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'invalid_ares_email':
				return '<p>' . __( '<strong>Invalid automated email ID.</strong> The automated email does not exist in the brand. Please make sure you entered the correct brand ID in the Boldermail Settings page, and try again.', 'boldermail' ) . '</p>';

			case 'update_ares_email_failed':
				return '<p>' . __( '<strong>Unable to update the automated email.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'delete_ares_email_failed':
				return '<p>' . __( '<strong>Unable to delete the automated email.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			case 'no_test_email':
				return '<p>' . __( '<strong>Email addresses to send a test email were not passed.</strong> Please enter an email address.', 'boldermail' ) . '</p>';

			case 'not_production_site':
				return '<p>' . __( '<strong>The WordPress development environment type for this website is not set to production.</strong> You can only communicate with your Boldermail server from your production website.', 'boldermail' ) . '</p>';

			case 'no_auth_code':
				return '<p>' . __( '<strong>Authorization not granted.</strong> To integrate Boldermail with a third-party service, please grant the application permission to connect when requested to do so.', 'boldermail' ) . '</p>';

			case 'no_access_token':
				return '<p>' . __( '<strong>Access token not granted.</strong> Please contact technical support.', 'boldermail' ) . '</p>';

			default:
				return '<p>' . __( '<strong>Unknown error.</strong> Please contact support.', 'boldermail' ) . '</p>';

		}

	}

}

/**
 * Check if the response contains an error code/message.
 *
 * @since  1.0.0
 * @param  WP_Error|array $response WP_Error or array response.
 * @return WP_Error|false           WP_Error on failure or false (no error) on success.
 */
function boldermail_maybe_get_error( $response ) {
	return Boldermail_Errors::maybe_get_error( $response );
}

/**
 * Get an error message from an error code.
 *
 * @since  1.0.0
 * @param  string $code Error code.
 * @return string
 */
function boldermail_get_error_message( $code ) {
	return Boldermail_Errors::get_user_friendly_message( $code );
}

/**
 * Check if a string is valid JSON.
 *
 * @see    https://stackoverflow.com/a/25540509/1991500
 * @since  1.0.0
 * @param  string $response HTTP request response body.
 * @return bool
 */
function boldermail_is_json( $response ) {
	return ( json_decode( $response, true ) === null ) ? false : true;
}
