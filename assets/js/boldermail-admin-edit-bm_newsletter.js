'use strict';

window.jQuery( function ( $ ) {
	/**
	 * Update campaign data on page load.
	 *
	 * @since 1.3.0
	 */
	$( window ).on( 'load', function () {
		/**
		 * Update the campaign columns.
		 *
		 * @since 1.3.0
		 * @param {string} postId Post ID.
		 */
		const ajaxUpdateCampaignColumns = function ( postId ) {
			const row = $( 'body.edit-php table.wp-list-table tr[id="post-' + postId + '"]' );

			$.ajax( {
				type: 'post',
				dataType: 'json',
				url: window.boldermail.ajaxUrl,
				data: {
					action: 'boldermail_update_newsletter',
					nonce: window.boldermail.ajaxNonce,
					post_id: postId,
				},
				success( response ) {
					if ( typeof response !== 'undefined' ) {
						if ( typeof response.status !== 'undefined' ) {
							$( row ).find( 'td.status' ).html( response.status, 250 );
						}

						if ( typeof response.recipients !== 'undefined' ) {
							$( row ).find( 'td.recipients' ).html( response.recipients, 250 );
						}

						if ( typeof response.opens !== 'undefined' ) {
							$( row ).find( 'td.opens' ).html( response.opens, 250 );
						}

						if ( typeof response.clicks !== 'undefined' ) {
							$( row ).find( 'td.clicks' ).html( response.clicks, 250 );
						}

						$( document.body ).trigger( 'boldermail-init-tooltips' );
					}
				},
			} );
		};

		/**
		 * For each newsletter preparing to send or sending, we setup a
		 * script that runs every 5 seconds to update the number of emails
		 * sent from Sendy without saturating the server.
		 *
		 * @since 1.3.0
		 */
		let interval = setInterval( function () {
			const rows = $(
				'body.edit-php.post-type-bm_newsletter table.wp-list-table tr.status-preparing td.recipients span.bm-loading, body.edit-php.post-type-bm_newsletter table.wp-list-table tr.status-sending td.recipients span.bm-loading'
			);

			if ( rows.length > 0 ) {
				rows.each( function () {
					const row = $( this ).closest( 'tr.type-bm_newsletter' );

					if ( typeof row.attr( 'id' ) === 'undefined' ) {
						clearInterval( interval );
						interval = null;

						return false;
					}

					const postId = row.attr( 'id' ).replace( 'post-', '' );

					ajaxUpdateCampaignColumns( postId );
				} );
			} else {
				clearInterval( interval );
				interval = null;
			}
		}, 5000 );

		/**
		 * Get the new data and update the row for regular newsletters already
		 * sent, and for enabled and paused autoresponders.
		 * Interval of 0.25 seconds to not saturate the server.
		 *
		 * Update `publish` for backwards compatibility.
		 *
		 * @since 1.3.0
		 */
		$(
			// eslint-disable-next-line no-multi-str
			'body.edit-php.post-type-bm_newsletter table.wp-list-table tr.status-publish, \
			 body.edit-php.post-type-bm_newsletter table.wp-list-table tr.status-sent, \
			 body.edit-php.post-type-bm_newsletter_ares table.wp-list-table tr.status-publish, \
			 body.edit-php.post-type-bm_newsletter_ares table.wp-list-table tr.status-enabled, \
			 body.edit-php.post-type-bm_newsletter_ares table.wp-list-table tr.status-paused'
		).each( function ( index ) {
			const row = $( this );

			if ( typeof row.attr( 'id' ) === 'undefined' ) {
				return false;
			}

			const postId = row.attr( 'id' ).replace( 'post-', '' );

			// set an interval between API calls to avoid killing the server by accident
			setTimeout( function () {
				ajaxUpdateCampaignColumns( postId );
			}, index * 500 );
		} );
	} );
} );
