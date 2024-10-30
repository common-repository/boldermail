( function( $ ) {

	'use strict';

	var boldermail_admin_subscriber_cf = {

		postId : $( 'input[name="post_ID"]' ).val(),

		init : function() {

			/**
			 * Display the custom field inputs based on list selection.
			 *
			 * @since   1.6.0
			 */
			$( '#subscriber_data_panel' ).on( 'change', 'select#list', function() {

				var list = $( this ).val();
				$( '#custom-fields' ).hide();

				return $.ajax( {
					type : 'post',
					dataType : 'json',
					url : boldermail.ajaxUrl,
					data : {
						action: 'boldermail_subscriber_custom_fields',
						post: boldermail_admin_subscriber_cf.postId,
						list: list,
						nonce : boldermail.ajaxNonce,
					},
					success : function( response ) {
						if ( typeof response !== "undefined" && response.html ) {
							$( '#custom-fields' ).html( response.html ).show();
						}
					}
				} );

			} );

			/**
			 * Initialize the custom fields.
			 *
			 * @since   1.6.0
			 */
			$( '#subscriber_data_panel' ).find( 'select#list' ).change();

		}

	};

	boldermail_admin_subscriber_cf.init();

} )( jQuery );
