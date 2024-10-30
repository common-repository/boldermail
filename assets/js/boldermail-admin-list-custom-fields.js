'use strict';

window.jQuery( function ( $ ) {
	const boldermailCfAdmin = {
		post_id: $( 'input[name="post_ID"]' ).val(),

		/**
		 * Refresh the content of the custom field panel.
		 *
		 * @since 1.6.0
		 */
		refresh() {
			const addons = $( '.boldermail-cf-addon' ).length;

			if ( 0 < addons ) {
				$( '.boldermail-cf-toolbar' ).addClass( 'boldermail-cf-has-addons' );
				$( '.boldermail-cf-addons' ).addClass( 'boldermail-cf-has-addons' );
			} else {
				$( '.boldermail-cf-toolbar' ).removeClass( 'boldermail-cf-has-addons' );
				$( '.boldermail-cf-addons' ).removeClass( 'boldermail-cf-has-addons' );
			}

			$( document.body ).trigger( 'boldermail-init-tooltips' );
		},

		init() {
			/**
			 * Add a custom field form.
			 *
			 * @since 1.6.0
			 */
			$( '#custom_fields_panel' ).on( 'click', '.boldermail-cf-add-field', function () {
				const loop = $( '.boldermail-cf-addons .boldermail-cf-addon' ).length;

				return $.ajax( {
					type: 'post',
					dataType: 'json',
					url: window.boldermail.ajaxUrl,
					data: {
						action: 'boldermail_list_custom_field',
						post: boldermailCfAdmin.post_id,
						nonce: window.boldermail.ajaxNonce,
					},
					success( response ) {
						if ( typeof response !== 'undefined' ) {
							let html = response;

							html = html.replace( /{loop}/g, loop );
							html = html.replace( /closed/g, 'open' );

							$( '.boldermail-cf-addons' ).append( html );

							$( 'select.boldermail-cf-addon-type-select' ).change();

							boldermailCfAdmin.refresh();
						}
					},
				} );
			} );

			/**
			 * Update the header title based on user input.
			 *
			 * @since 1.6.0
			 */
			$( '#custom_fields_panel' ).on( 'change', '.boldermail-cf-addon-content-name', function () {
				if ( $( this ).val() ) {
					$( this )
						.closest( '.boldermail-cf-addon' )
						.find( '.boldermail-cf-addon-name' )
						.text( $( this ).val() );
					$( this ).closest( '.boldermail-cf-addon' ).find( '.boldermail-cf-addon-code' ).show();
					$( this )
						.closest( '.boldermail-cf-addon' )
						.find( '.boldermail-cf-addon-code-name' )
						.text( $( this ).val().replace( ' ', '' ) );
				} else {
					$( this ).closest( '.boldermail-cf-addon' ).find( '.boldermail-cf-addon-name' ).text( '' );
					$( this ).closest( '.boldermail-cf-addon' ).find( '.boldermail-cf-addon-code' ).hide();
					$( this ).closest( '.boldermail-cf-addon' ).find( '.boldermail-cf-addon-code-name' ).text( '' );
				}
			} );

			$( '#custom_fields_panel' ).on( 'change', 'select.boldermail-cf-addon-type-select', function () {
				const parent = $( this ).parents( '.boldermail-cf-addon' ),
					selectedName = $( this ).context.selectedOptions[ 0 ].innerHTML;

				// Update selected type label.
				parent.find( '.boldermail-cf-addon-header .boldermail-cf-addon-type' ).html( selectedName );
			} );

			/**
			 * Remove custom field form.
			 *
			 * @since 1.6.0
			 */
			$( '#custom_fields_panel' ).on( 'click', '.boldermail-cf-remove-addon', function () {
				$( '.boldermail-cf-error-message' ).remove();

				/* eslint-disable-next-line no-alert */
				const answer = window.confirm(
					wp.i18n.__( 'Are you sure you want remove this custom field?', 'boldermail' )
				);

				if ( answer ) {
					const addon = $( this ).closest( '.boldermail-cf-addon' );
					$( addon ).find( 'input' ).val( '' );
					$( addon ).remove();
				}

				$( '.boldermail-cf-addons .boldermail-cf-addon' ).each( function ( index ) {
					const thisIndex = index;

					$( this )
						.find( 'select, input, textarea' )
						.prop( 'name', function ( i, val ) {
							return val.replace( /\[[0-9]+]/g, '[' + thisIndex + ']' );
						} );
				} );

				boldermailCfAdmin.refresh();

				return false;
			} );

			/**
			 * Expand all custom field forms.
			 *
			 * @since 1.6.0
			 */
			$( '#custom_fields_panel' ).on( 'click', '.boldermail-cf-expand-all', function ( e ) {
				e.preventDefault();
				$( '#custom_fields_panel .boldermail-cf-addon' ).removeClass( 'closed' ).addClass( 'open' );
			} );

			/**
			 * Collapse all custom field forms.
			 *
			 * @since 1.6.0
			 */
			$( '#custom_fields_panel' ).on( 'click', '.boldermail-cf-close-all', function ( e ) {
				e.preventDefault();
				$( '#custom_fields_panel .boldermail-cf-addon' ).removeClass( 'open' ).addClass( 'closed' );
			} );

			/**
			 * Toggle custom field form.
			 *
			 * @since 1.6.0
			 */
			$( '#custom_fields_panel' ).on( 'click', '.boldermail-cf-addon-header', function ( e ) {
				e.preventDefault();

				const element = $( this ).parents( '.boldermail-cf-addon' );

				if ( element.hasClass( 'open' ) ) {
					element.removeClass( 'open' ).addClass( 'closed' );
				} else {
					element.removeClass( 'closed' ).addClass( 'open' );
				}
			} );

			/**
			 * Export custom fields.
			 *
			 * @since 1.6.0
			 */
			$( '#custom_fields_panel' ).on( 'click', '.boldermail-cf-export-addons', function () {
				$( '#custom_fields_panel textarea.boldermail-cf-import-field' ).hide();
				$( '#custom_fields_panel textarea.boldermail-cf-export-field' ).slideToggle( '300', function () {
					$( this ).select();
				} );

				return false;
			} );

			/**
			 * Import custom fields.
			 *
			 * @since 1.6.0
			 */
			$( '#custom_fields_panel' ).on( 'click', '.boldermail-cf-import-addons', function () {
				$( '#custom_fields_panel textarea.boldermail-cf-export-field' ).hide();
				$( '#custom_fields_panel textarea.boldermail-cf-import-field' ).slideToggle( '300', function () {
					$( this ).val( '' );
				} );

				return false;
			} );

			/**
			 * Initialize the custom field type.
			 *
			 * @since   1.6.0
			 */
			$( '#custom_fields_panel' ).find( 'select.boldermail-cf-addon-type-select' ).change();
			$( '#custom_fields_panel' ).find( 'input.boldermail-cf-addon-content-name' ).change();
		},
	};

	boldermailCfAdmin.init();
} );
