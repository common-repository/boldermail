'use strict';

/**
 * Use the WordPress Heartbeat API to save the meta data of the posts.
 *
 * @see   https://wordpress.stackexchange.com/a/335081/85404
 * @since 1.7.0
 */
window.jQuery( function ( $ ) {
	$( document ).on( 'heartbeat-send', function ( event, data ) {
		// Sync the visual editor and textarea content.
		if ( typeof tinymce !== 'undefined' ) {
			window.tinymce.triggerSave();
		}

		// Post ID.
		data.post = $( '[name="post_ID"]' ).val();

		// HTML for templates and newsletters.
		data.html = $( '[name="_html"]' ).val();
	} );
} );
