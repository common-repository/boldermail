( function( $ ) {
	'use strict';

	/**
	 * Hide all editor panels on init.
	 *
	 * @since   1.7.0
	 */
	$( document.body ).on( 'boldermail-init-editor-panels', function() {
		$( 'div.boldermail-editor-wrap' ).each( function() {
			$( this ).find( '.boldermail-editor-meta-box' ).hide();
		} );
	} ).trigger( 'boldermail-init-editor-panels' );

	/**
	 * Display an editor panel.
	 *
	 * @since   1.7.0
	 */
	$( 'a.boldermail-media-button' ).on( 'click', function() {
		// Get editor.
		const editor = $( this ).closest( '.boldermail-editor-wrap' );

		// Hide all panels.
		$( editor ).find( '.boldermail-editor-meta-box' ).hide();

		// Get panel.
		const target = $( this ).attr( 'href' );

		if ( target ) {
			// Show panel.
			$( target ).show();
			$( target + ' .boldermail-options-panel' ).show();

			// Scroll to panel.
			$( 'html, body' ).animate( {
				scrollTop: $( target ).offset().top - 32, // admin bar
			} );
		}

		return false;
	} );

	/**
	 * Close an editor panel.
	 *
	 * @since   1.7.0
	 */
	$( '.boldermail-handlediv' ).on( 'click', function() {
		// Get editor.
		const editor = $( this ).closest( '.boldermail-editor-wrap' );

		// Hide panel.
		$( this ).closest( '.boldermail-editor-meta-box' ).hide();

		// Hide messages.
		editor.find( '.test-send-response' ).html( '' );

		// Scroll to editor.
		$( 'html, body' ).animate( {
			scrollTop: $( editor ).offset().top - 32, // admin bar
		} );

		return false;
	} );

	/**
	 * Show template preview.
	 *
	 * @see     https://stackoverflow.com/a/28569340/1991500
	 * @see     https://stackoverflow.com/a/46894035/1991500
	 * @see     https://premium.wpmudev.org/blog/using-ajax-with-wordpress/
	 * @see     https://eric.blog/2013/06/18/how-to-add-a-wordpress-ajax-nonce/
	 *
	 * @since   1.0.0   assets/js/boldermail-admin.js
	 * @since   1.7.0   assets/js/boldermail-admin-editor.js
	 */
	$( '.boldermail-editor-wrap .template .load-template' ).on( 'click', function( e ) {
		e.preventDefault();

		const postId = $( this ).data( 'id' );

		$.ajax( {
			type: 'post',
			dataType: 'json',
			url: boldermail.ajaxUrl,
			data: {
				action: 'boldermail_template_html',
				nonce: boldermail.ajaxNonce,
				post: postId,
				preview: '',
			},
			success: function( response ) {
				$.fancybox.open( response, {
					closeExisting: true,
				} );
			},
		} );
	} );

	/**
	 * Replace the content of the TinyMCE editor in both text and visual modes
	 * with the HTML of a template.
	 *
	 * @see     https://wordpress.stackexchange.com/a/284818/85404
	 * @since   1.0.0   assets/js/boldermail-admin.js
	 * @since   1.7.0   assets/js/boldermail-admin-editor.js
	 */
	$( '.boldermail-editor-wrap .template .activate-template' ).on( 'click', function( e ) {
		e.preventDefault();

		if ( confirm( wp.i18n.__( 'Activating a template will replace all of the text in the editor with the content of the template. Any customization you may have done will be deleted. Click OK to continue, Cancel to keep your current content.', 'boldermail' ) ) ) {
			const postId = $( this ).data( 'id' );
			const editor = $( this ).closest( '.boldermail-editor-wrap' );
			const input = $( editor ).data( 'editor' );

			$.ajax( {
				type: 'post',
				dataType: 'json',
				url: boldermail.ajaxUrl,
				data: {
					action: 'boldermail_template_html',
					nonce: boldermail.ajaxNonce,
					post: postId,
				},
				success: function( response ) {
					var content = response;

					if ( $( editor ).find( '.wp-editor-wrap' ).hasClass( 'html-active' ) ) {  // Text mode
						$( editor ).find( '.wp-editor-area' ).val( content );
					} else { // Visual mode
						const tinyMCEEditor = window.tinymce.get( input );

						if ( tinyMCEEditor !== null ) {
							tinyMCEEditor.setContent( content );
						}
					}

					// sync the visual editor and textarea content
					window.tinymce.triggerSave();

					$( 'html, body' ).animate( {
						scrollTop: $( editor ).offset().top
					} );
				},
			} );

		}
	} );

	/**
	 * Preview editor HTML content.
	 *
	 * @since   1.7.0
	 */
	$( '.boldermail-html-preview' ).on( 'click', function( e ) {
		e.preventDefault();

		// Sync the visual editor and textarea content.
		if ( typeof window.tinymce !== 'undefined' ) {
			window.tinymce.triggerSave();
		}

		// Get data.
		const editor = $( this ).closest( '.boldermail-editor-wrap' );
		const preview = JSON.parse( JSON.stringify( $( editor ).data( 'preview' ) ) );

		// @see https://stackoverflow.com/a/6627996/1991500
		const postArray = $( '#post' ).serializeArray();
		postArray.push( { name: 'action', value: 'boldermail_editor_html_preview' } );
		postArray.push( { name: 'nonce', value: boldermail.ajaxNonce } );
		postArray.push( { name: 'filter', value: preview.filter } );
		postArray.push( { name: 'meta_key', value: preview.content } );

		$.ajax( {
			type: 'post',
			dataType: 'json',
			url: boldermail.ajaxUrl,
			data: bmSerializeObject( postArray ),
			success: function( response ) {
				$.fancybox.open( response, {
					closeExisting: true,
				} );
			},
		} );
	} );

	/**
	 * Preview editor plain text content.
	 *
	 * @since   1.7.0
	 */
	$( '.boldermail-plain-text-preview' ).on( 'click', function( e ) {
		e.preventDefault();

		// Sync the visual editor and textarea content.
		if ( typeof window.tinymce !== 'undefined' ) {
			window.tinymce.triggerSave();
		}

		// Get data.
		const editor = $( this ).closest( '.boldermail-editor-wrap' );
		const preview = JSON.parse( JSON.stringify( $( editor ).data( 'preview' ) ) );

		// @see https://stackoverflow.com/a/6627996/1991500
		const postArray = $( '#post' ).serializeArray();
		postArray.push( { name: 'action', value: 'boldermail_editor_plain_text_preview' } );
		postArray.push( { name: 'nonce', value: boldermail.ajaxNonce } );
		postArray.push( { name: 'filter', value: preview.filter } );
		postArray.push( { name: 'meta_key', value: preview.content } );

		$.ajax( {
			type: 'post',
			dataType: 'json',
			url: boldermail.ajaxUrl,
			data: bmSerializeObject( postArray ),
			success: function( response ) {
				$.fancybox.open( '<textarea rows="16" readonly disabled class="large-text boldermail-plain-text-preview">' + response + '</textarea>', {
					closeExisting: true,
				} );
			},
		} );
	} );

	/**
	 * Send test emails via AJAX.
	 *
	 * @since   1.7.0
	 */
	$( '.boldermail-editor-wrap .test-send-email input[type="submit"]' ).on( 'click', function( e ) {
		e.preventDefault();

		// Sync the visual editor and textarea content.
		if ( typeof window.tinymce !== 'undefined' ) {
			window.tinymce.triggerSave();
		}

		// Get data.
		const editor = $( this ).closest( '.boldermail-editor-wrap' );
		const preview = JSON.parse( JSON.stringify( $( editor ).data( 'preview' ) ) );
		const testEmail = editor.find( 'input[name="_test_email"]' ).val();

		const spinner = editor.find( '.spinner' );
		spinner.css( 'visibility', 'visible' );
		editor.find( '.test-send-response' ).html( '' );

		// @see https://stackoverflow.com/a/6627996/1991500
		const postArray = $( '#post' ).serializeArray();
		postArray.push( { name: 'action', value: 'boldermail_test_send' } );
		postArray.push( { name: 'nonce', value: boldermail.ajaxNonce } );
		postArray.push( { name: 'test_email', value: testEmail } );

		if ( preview.from_name ) {
			postArray.push( { name: 'from_name', value: preview.from_name } );
		}
		if ( preview.from_email ) {
			postArray.push( { name: 'from_email', value: preview.from_email } );
		}
		if ( preview.reply_to ) {
			postArray.push( { name: 'reply_to', value: preview.reply_to } );
		}
		if ( preview.subject ) {
			postArray.push( { name: 'subject', value: preview.subject } );
		}
		if ( preview.content ) {
			postArray.push( { name: 'content', value: preview.content } );
		}
		if ( preview.filter ) {
			postArray.push( { name: 'filter', value: preview.filter } );
		}

		$.ajax( {
			type: 'post',
			dataType: 'json',
			url: boldermail.ajaxUrl,
			data: bmSerializeObject( postArray ),
			success: function( response ) {
				spinner.css( 'visibility', 'hidden' );
				editor.find( '.test-send-response' ).html( response ).show( 'toggle' );
			},
		} );
	} );

	/**
	 * Save the newsletter data before redirecting to Block Editor.
	 *
	 * @since   2.0.0
	 */
	$( 'button[data-name="redirect_to_block_template"]' ).on( 'click', function( e ) {
		e.preventDefault();

		// Get data.
		const post = $( '#post' );
		const name = $( this ).data( 'name' );
		const value = $( this ).data( 'value' );

		// Prevent "Leave Site?" popup.
		$( window ).off( 'beforeunload.edit-post' );

		// Add hidden input to use in redirect.
		const input = $( '<input />' ).attr( 'type', 'hidden' ).attr( 'name', name ).attr( 'value', value );
		post.append( input );

		// Submit the form.
		post.submit();
	} );

	/**
	 * Save the newsletter data and add input to switch to Classic Editor.
	 *
	 * @since   2.0.0
	 */
	$( 'button[data-name="switch_to_classic_editor"]' ).on( 'click', function( e ) {
		e.preventDefault();

		if ( ! confirm( wp.i18n.__( 'Are you sure you want to switch to the Classic Editor? Boldermail will import your template from the Block Editor into the Classic Editor, if available. This action is IRREVERSIBLE.', 'boldermail' ) ) ) {
			return false;
		}

		// Get data.
		const post = $( '#post' );

		// Prevent "Leave Site?" popup.
		$( window ).off( 'beforeunload.edit-post' );

		// Add hidden input to use in redirect.
		const input = $( '<input />' ).attr( 'type', 'hidden' ).attr( 'name', '_use_block_editor' ).attr( 'value', '0' );
		post.append( input );

		// Submit the form.
		post.submit();
	} );
}( jQuery ) );

/**
 * Convert serialized array to key => value pairs.
 *
 * @see https://stackoverflow.com/a/12399106
 * @see https://stackoverflow.com/a/39919964
 * @since   1.7.0
 * @param   {array} postArray   Post array data.
 * @return  {array}             Serialized data.
 */
function bmSerializeObject( postArray ) {
	const postData = { };
	jQuery.each( postArray, function( i, o ) {
		const n = o.name;
		const v = o.value;

		if ( postData[ n ] === undefined ) {
			postData[ n ] = v;
		} else if ( n.includes( '[]' ) ) {
			// only merge values with '[]' as an attribute
			// otherwise, we create issues with action and wp_http_referrer
			if ( jQuery.isArray( postData[ n ] ) ) {
				postData[ n ] = postData[ n ].concat( v );
			} else {
				postData[ n ] = [ postData[ n ], v ];
			}
		} else {
			// else overwrite the value
			postData[ n ] = v;
		}
	} );

	return postData;
}
