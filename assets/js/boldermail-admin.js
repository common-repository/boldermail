'use strict';

/**
 * Remove the capacity to sort meta boxes if class `.not-sortable` is included.
 *
 * @see   https://wordpress.stackexchange.com/a/73806/85404
 * @since 2.0.0
 */
window.jQuery( function ( $ ) {
	$( window ).on( 'load', function () {
		// Check if sortable is already initialized.
		if ( ! $( '.meta-box-sortables' ).data( 'ui-sortable' ) ) {
			// Initialize sortable if not already initialized.
			$( '.meta-box-sortables' ).sortable( {
				// Initialize with any options you want to set initially.
			} );
		}

		// Now that sortable is guaranteed to be initialized, set the 'cancel' option.
		$( '.meta-box-sortables' ).sortable(
			'option',
			'cancel',
			'.boldermail-postbox.not-sortable .hndle, :input, button'
		);

		// And then refresh the instance.
		$( '.meta-box-sortables' ).sortable( 'refresh' );
	} );
} );

/**
 * Initialize help tips.
 *
 * @since 1.6.0
 */
window.jQuery( function ( $ ) {
	$( document )
		.on( 'boldermail-init-tooltips', function () {
			$( '.boldermail-tips, .boldermail-help-tip' ).tipTip( {
				attribute: 'data-tip',
				fadeIn: 50,
				fadeOut: 50,
				delay: 200,
			} );
		} )
		.trigger( 'boldermail-init-tooltips' );
} );

/**
 * Select2 support.
 *
 * @since 1.5.0
 */
window.jQuery( function ( $ ) {
	$( document )
		.on( 'boldermail-init-select2', function () {
			$( '.boldermail-select2' ).each( function () {
				const placeholder = $( this ).data( 'placeholder' );

				$( this ).select2( {
					placeholder,
				} );
			} );
		} )
		.trigger( 'boldermail-init-select2' );
} );

/**
 * Display a panel based on the tab selection.
 *
 * @since 1.0.0
 */
window.jQuery( function ( $ ) {
	$( document )
		.on( 'boldermail-init-tabbed-panels', function () {
			$( 'ul.boldermail-tabs' ).show();

			$( 'ul.boldermail-tabs a' ).on( 'click', function ( e ) {
				e.preventDefault();
				const panelWrap = $( this ).closest( 'div.boldermail-panel-wrap' );
				$( 'ul.boldermail-tabs li', panelWrap ).removeClass( 'active' );
				$( this ).parent().addClass( 'active' );
				$( '> div.boldermail-options-panel', panelWrap ).hide();

				// @see https://stackoverflow.com/a/43106210/1991500
				const panelSelector = $( this ).attr( 'href' );
				window.history.replaceState(
					'',
					document.title,
					window.location.href.replace( window.location.hash, '' ) + panelSelector.replace( '#', '#/' )
				);
				$( panelSelector ).show();
			} );

			$( 'div.boldermail-panel-wrap' ).each( function () {
				const panelSelector = window.location.hash;
				$( this ).find( 'ul.boldermail-tabs li' ).eq( 0 ).find( 'a' ).click();

				if ( panelSelector ) {
					// If there is a hash present, click on the tab.
					$( this )
						.find( 'ul.boldermail-tabs li' )
						.find( 'a[href="' + panelSelector.replace( /^#\/?/, '#' ) + '"]' )
						.click();
				}
			} );
		} )
		.trigger( 'boldermail-init-tabbed-panels' );
} );

/**
 * Get confirmation before publishing newsletter.
 *
 * @since 1.0.0
 */
window.jQuery( function ( $ ) {
	$( 'body.post-type-bm_newsletter #post' ).on( 'submit', function () {
		if ( window.boldermail.screenId !== 'bm_newsletter' ) {
			return true;
		}

		if ( $.inArray( window.boldermail.postStatus, [ 'publish', 'preparing', 'sending', 'sent' ] ) !== -1 ) {
			return true;
		}

		const $btn = $( document.activeElement );

		if (
			$btn.length &&
			$( 'body.post-type-bm_newsletter #post' ).has( $btn ) &&
			$btn.is( 'input[type="submit"]' ) &&
			$btn.is( '[name="publish"]' )
		) {
			/* eslint-disable-next-line no-alert */
			return window.confirm(
				wp.i18n.__(
					'Are you sure your newsletter is ready to be sent? Have you double checked your design and list recipients? Once the newsletter starts sending, you will need to contact technical support to stop it.',
					'boldermail'
				)
			);
		}
	} );
} );

/**
 * Get confirmation before deleting newsletter.
 *
 * @since 1.0.0
 */
window.jQuery( function ( $ ) {
	$( 'body.post-type-bm_newsletter .submitdelete' ).on( 'click', function () {
		if ( window.boldermail.screenId !== 'bm_newsletter' && window.boldermail.screenId !== 'edit-bm_newsletter' ) {
			return true;
		}

		/* eslint-disable-next-line no-alert */
		return window.confirm(
			wp.i18n.__(
				'Are you sure you want to delete your newsletter? This action is irrevocable and will permanently remove your newsletter from your site. Please note that deleting the campaign will not stop your campaign from sending. To stop your campaign from sending, please contact technical support.',
				'boldermail'
			)
		);
	} );
} );

/**
 * Activate the panel based on the error link in the admin notice.
 *
 * @since 1.0.0
 */
window.jQuery( function ( $ ) {
	$( document ).on( 'click', '.boldermail-error-link', function ( e ) {
		e.preventDefault();

		const href = $( this ).attr( 'href' );

		$( 'ul.boldermail-tabs li a[href="' + href + '"]' ).click();

		/* eslint-disable-next-line */
		$( 'html, body' ).animate( {
			scrollTop: parseInt( $( '#boldermail-newsletter' ).offset().top ),
		} );
	} );
} );

/**
 * Toggle content based on input changes.
 *
 * @since 1.0.0
 */
window.jQuery( function ( $ ) {
	window.boldermail.hideIf = () => {
		$( '[data-boldermail-hide-if]' ).each( function () {
			const condition = $( this ).data( 'boldermail-hide-if' );

			if ( $( condition ).length > 0 ) {
				$( this ).hide();
			} else {
				$( this ).show();
			}
		} );
	};

	window.boldermail.showIf = () => {
		$( '[data-boldermail-show-if]' ).each( function () {
			const condition = $( this ).data( 'boldermail-show-if' );

			if ( $( condition ).length > 0 ) {
				$( this ).show();
			} else {
				$( this ).hide();
			}
		} );
	};

	$( document.body ).on(
		'change',
		'.boldermail-options-panel textarea, .boldermail-options-panel input, .boldermail-options-panel select',
		window.boldermail.hideIf
	);

	$( document.body ).on(
		'change',
		'.boldermail-options-panel textarea, .boldermail-options-panel input, .boldermail-options-panel select',
		window.boldermail.showIf
	);

	$( document.body ).on( 'boldermail-init-hide-if', window.boldermail.hideIf ).trigger( 'boldermail-init-hide-if' );
	$( document.body ).on( 'boldermail-init-show-if', window.boldermail.showIf ).trigger( 'boldermail-init-show-if' );
} );

/**
 * Fill list information in newsletter "To:" tab, and
 * show number of subscribers to send to.
 *
 * We use window.jQuery instead of vanilla JavaScript because Select2 does not trigger native events,
 * just window.jQuery events.
 *
 * @see   https://github.com/select2/select2/issues/4686
 * @since 2.3.0
 */
window.jQuery( function ( $ ) {
	$( '#boldermail-newsletter #list_id' )
		.on( 'change', function () {
			const select = $( this );
			const postId = $( 'input[name="post_ID"]' ).val();
			const listId = $( this ).val();

			select.after( '<span class="bm-loading"></span>' );
			const loading = select.siblings( '.bm-loading' );
			loading.css( 'display', 'inline-block' );

			const description = select.siblings( '.description' );
			description.text( '' );

			$.ajax( {
				type: 'post',
				dataType: 'json',
				url: window.boldermail.ajaxUrl,
				data: {
					action: 'boldermail_recipients_count',
					nonce: window.boldermail.ajaxNonce,
					post: postId,
					list_id: listId,
				},
			} )
				.always( function () {
					loading.remove();
				} )
				.done( function ( countHtml ) {
					if ( typeof countHtml !== 'undefined' ) {
						description.text( countHtml );
					}
				} )
				.fail( function () {
					description.text( '' );
				} );

			$.ajax( {
				type: 'post',
				dataType: 'json',
				url: window.boldermail.ajaxUrl,
				data: {
					action: 'boldermail_newsletter_list_data',
					nonce: window.boldermail.ajaxNonce,
					post: postId,
					list_id: $.isArray( listId ) ? listId[ 0 ] : '',
				},
			} ).done( function ( listData ) {
				if ( typeof listData === 'undefined' ) {
					return;
				}
				const fromNameSelector = $( '#boldermail-newsletter input[name="_from_name"]' );
				const fromEmailSelector = $( '#boldermail-newsletter input[name="_from_email"]' );
				const replyToSelector = $( '#boldermail-newsletter input[name="_reply_to"]' );
				const companyNameSelector = $( '#boldermail-newsletter input[name="_company_name"]' );
				const companyAddressSelector = $( '#boldermail-newsletter input[name="_company_address"]' );
				const permissionSelector = $( '#boldermail-newsletter textarea[name="_permission"]' );

				if ( typeof listData.from_name !== 'undefined' && fromNameSelector.val() === '' ) {
					fromNameSelector.val( listData.from_name );
				}

				if ( typeof listData.from_email !== 'undefined' && fromEmailSelector.val() === '' ) {
					fromEmailSelector.val( listData.from_email );
				}

				if ( typeof listData.reply_to !== 'undefined' && replyToSelector.val() === '' ) {
					replyToSelector.val( listData.reply_to );
				}

				if ( typeof listData.company_name !== 'undefined' && companyNameSelector.val() === '' ) {
					companyNameSelector.val( listData.company_name );
				}

				if ( typeof listData.company_address !== 'undefined' && companyAddressSelector.val() === '' ) {
					companyAddressSelector.val( listData.company_address );
				}

				if ( typeof listData.permission !== 'undefined' && permissionSelector.val() === '' ) {
					permissionSelector.val( listData.permission );
				}
			} );
		} )
		.trigger( 'change' );
} );

/**
 * Fill list information in autoresponder newsletter, and get trigger form.
 *
 * @since 1.0.0
 */
window.jQuery( function ( $ ) {
	$( '#boldermail-newsletter select[name="_autoresponder"]' )
		.on( 'change', function () {
			const postId = $( 'input[name="post_ID"]' ).val();
			const autoresponder = $( this ).val();

			$.ajax( {
				type: 'post',
				dataType: 'json',
				url: window.boldermail.ajaxUrl,
				data: {
					action: 'boldermail_newsletter_ares_data',
					nonce: window.boldermail.ajaxNonce,
					post: postId,
					autoresponder,
				},
			} ).done( function ( listData ) {
				if ( typeof listData === 'undefined' ) {
					return;
				}

				const fromNameSelector = $( '#boldermail-newsletter input[name="_from_name"]' );
				const fromEmailSelector = $( '#boldermail-newsletter input[name="_from_email"]' );
				const replyToSelector = $( '#boldermail-newsletter input[name="_reply_to"]' );
				const companyNameSelector = $( '#boldermail-newsletter input[name="_company_name"]' );
				const companyAddressSelector = $( '#boldermail-newsletter input[name="_company_address"]' );
				const permissionSelector = $( '#boldermail-newsletter textarea[name="_permission"]' );

				if ( typeof listData.from_name !== 'undefined' && fromNameSelector.val() === '' ) {
					fromNameSelector.val( listData.from_name );
				}

				if ( typeof listData.from_email !== 'undefined' && fromEmailSelector.val() === '' ) {
					fromEmailSelector.val( listData.from_email );
				}

				if ( typeof listData.reply_to !== 'undefined' && replyToSelector.val() === '' ) {
					replyToSelector.val( listData.reply_to );
				}

				if ( typeof listData.company_name !== 'undefined' && companyNameSelector.val() === '' ) {
					companyNameSelector.val( listData.company_name );
				}

				if ( typeof listData.company_address !== 'undefined' && companyAddressSelector.val() === '' ) {
					companyAddressSelector.val( listData.company_address );
				}

				if ( typeof listData.permission !== 'undefined' && permissionSelector.val() === '' ) {
					permissionSelector.val( listData.permission );
				}
			} );

			$.ajax( {
				type: 'post',
				dataType: 'json',
				url: window.boldermail.ajaxUrl,
				data: {
					action: 'boldermail_newsletter_ares_trigger',
					nonce: window.boldermail.ajaxNonce,
					post_id: postId,
					autoresponder,
				},
			} ).done( function ( response ) {
				if ( typeof response === 'undefined' ) {
					return;
				}

				$( '#boldermail-newsletter #trigger_panel' ).replaceWith( response );
				$( '#boldermail-newsletter' ).find( 'ul.boldermail-tabs li.active' ).eq( 0 ).find( 'a' ).click();
				window.boldermail.hideIf();
			} );
		} )
		.trigger( 'change' );
} );

/**
 * Disables the submit upload buttons when no data is entered.
 *
 * @see    wp-admin/js/common.js
 * @since  1.7.0
 * @return void
 */
window.jQuery( function ( $ ) {
	const form = $( 'form.boldermail-upload-form' );

	// exit when no upload form is found.
	if ( ! form.length ) {
		return;
	}

	const button = form.find( 'input[type="submit"]' );
	const input = form.find( 'input[type="file"]' );

	/**
	 * Determines if any data is entered in any file upload input.
	 *
	 * @since  1.7.0
	 * @return {void}
	 */
	function toggleUploadButton() {
		// When no inputs have a value, disable the upload buttons.
		button.prop(
			'disabled',
			'' ===
				input
					.map( function () {
						return $( this ).val();
					} )
					.get()
					.join( '' )
		);
	}

	// update the status initially
	toggleUploadButton();

	// update the status when any file input changes
	input.on( 'change', toggleUploadButton );
} );

/**
 * Emojis for subject line
 *
 * @see   boldermail_wp_emoji_text_input
 * @since 2.1.0
 */
wp.domReady( () => {
	const emojiPickerFields = document.querySelectorAll( '.emoji-picker-field' );

	for ( let i = 0, len = emojiPickerFields.length; i < len; i++ ) {
		const emojiPickerField = emojiPickerFields[ i ];

		const emojiButton = emojiPickerField.querySelector( '.emoji-button' );
		const emojiContent = emojiPickerField.querySelector( '.emoji-contenteditable' );

		const inputText = emojiPickerField.querySelector( 'input[type="text"]' );
		inputText.style.display = 'none';

		// noinspection JSUnresolvedFunction EmojiButton class is included via `wp_enqueue_scripts`.
		const emojiPicker = new window.EmojiButton( {
			theme: 'auto',
			style: 'native',
			position: 'bottom-end',
		} );

		emojiPicker.on( 'emoji', function ( emoji ) {
			emojiContent.insertAdjacentHTML( 'beforeend', emoji );
			emojiContent.dispatchEvent( new window.Event( 'keyup' ) );
		} );

		// @see https://stackoverflow.com/a/2885716/1991500
		[ 'propertychange', 'change', 'click', 'keyup', 'input', 'paste' ].forEach( function ( e ) {
			emojiContent.addEventListener( e, function () {
				inputText.value = convertToText( this.innerHTML ); // Use innerHTML, not innerText to avoid errors with `&nbsp;`.
			} );
		} );

		emojiButton.addEventListener( 'click', function ( e ) {
			e.preventDefault();

			// noinspection JSUnresolvedFunction EmojiButton class is included via `wp_enqueue_scripts`.
			emojiPicker.togglePicker( emojiButton );
		} );
	}

	/**
	 * Convert `contenteditable` content to text.
	 *
	 * @see    https://gist.github.com/nathansmith/86b5d4b23ed968a92fd4
	 * @since  2.1.0
	 * @param  {string} str String to convert to text.
	 * @return {string}     Sanitized text.
	 */
	const convertToText = ( str = '' ) => {
		// Ensure string.
		let value = String( str );

		// Convert encoding.
		value = value.replace( /&nbsp;/gi, ' ' );
		value = value.replace( /&amp;/gi, '&' );

		// Replace `<br>`.
		value = value.replace( /<br>/gi, '' );

		// Replace `<div>` (from Chrome).
		value = value.replace( /<div>/gi, '' );

		// Replace `<p>` (from IE).
		value = value.replace( /<p>/gi, '' );

		// Remove extra tags.
		value = value.replace( /<(.*?)>/g, '' );

		// Clean up spaces.
		value = value.replace( /[ ]+/g, ' ' );
		value = value.trim();

		// Expose string.
		return value;
	};
} );

/**
 * Prepend "Add New" buttons to template page.
 *
 * @since 2.2.0
 */
wp.domReady( () => {
	const button = document.querySelector( '.post-type-bm_template .page-title-action' );

	if ( ! button ) {
		return;
	}

	button.style.display = 'none';

	if ( window.boldermail.newTemplateLink.classicEditor && window.boldermail.newTemplateLink.blockEditor ) {
		const classicButton = document.createElement( 'a' );
		classicButton.className = 'page-title-action';
		classicButton.href = window.boldermail.newTemplateLink.classicEditor;
		classicButton.text = wp.i18n.__( 'Add New Classic Template', 'boldermail' );
		button.parentNode.insertBefore( classicButton, button );

		const blockButton = document.createElement( 'a' );
		blockButton.className = 'page-title-action';
		blockButton.href = window.boldermail.newTemplateLink.blockEditor;
		blockButton.text = wp.i18n.__( 'Add New Block Template', 'boldermail' );
		button.parentNode.insertBefore( blockButton, button );
	}
} );

/**
 * Prepend "Import" and "Export" buttons to subscriber page.
 *
 * @since 2.4.0
 */
// wp.domReady( () => {
// 	const button = document.querySelector( '.post-type-bm_subscriber .page-title-action' );
//
// 	if ( ! button ) {
// 		return;
// 	}
//
// 	if ( window.boldermail.importSubscriberLink && window.boldermail.exportSubscriberLink ) {
// 		const exportButton = document.createElement( 'a' );
// 		exportButton.className = 'page-title-action';
// 		exportButton.href = window.boldermail.exportSubscriberLink;
// 		exportButton.text = wp.i18n.__( 'Export', 'boldermail' );
// 		button.parentNode.insertBefore( exportButton, button.nextSibling );
//
// 		const importButton = document.createElement( 'a' );
// 		importButton.className = 'page-title-action';
// 		importButton.href = window.boldermail.importSubscriberLink;
// 		importButton.text = wp.i18n.__( 'Import', 'boldermail' );
// 		button.parentNode.insertBefore( importButton, button.nextSibling );
// 	}
// } );

/**
 * Get the post type, taxonomies, and terms for the RSS feed campaign.
 *
 * @since 2.3.0
 */
window.jQuery( function ( $ ) {
	$( '#boldermail-newsletter #post_type' )
		.on( 'change', function () {
			const postId = $( 'input[name="post_ID"]' ).val();
			const postTypeSelect = $( this );

			// Remove taxonomy + terms selectors.
			$( '#boldermail-newsletter #taxonomy' ).parent( '.form-field' ).remove();
			$( '#boldermail-newsletter #term__includes' ).parent( '.form-field' ).remove();
			$( '#boldermail-newsletter #term__excludes' ).parent( '.form-field' ).remove();

			// Remove any leftover loading icons.
			postTypeSelect.parent( '.form-field' ).find( '.bm-loading' ).remove();

			// Add a loading icon after the select element.
			postTypeSelect.after( '<span class="bm-loading"></span>' );
			const postTypeLoading = postTypeSelect.siblings( '.bm-loading' );
			postTypeLoading.css( 'display', 'inline-block' );

			// Load the taxonomy selector.
			$.ajax( {
				type: 'post',
				dataType: 'json',
				url: window.boldermail.ajaxUrl,
				data: {
					action: 'boldermail_rss_post_type_taxonomies',
					nonce: window.boldermail.ajaxNonce,
					post: postId,
					post_type: postTypeSelect.val(),
				},
			} )
				.always( function () {
					postTypeLoading.remove();
				} )
				.done( function ( taxonomyHTML ) {
					if ( typeof taxonomyHTML !== 'undefined' ) {
						// Remove taxonomy selector again in case of slow AJAX call.
						$( '#boldermail-newsletter #taxonomy' ).parent( '.form-field' ).remove();

						// Append HTML.
						$( '#boldermail-newsletter #query_args' ).append( taxonomyHTML );
					}

					$( document ).trigger( 'boldermail-init-select2' );
					$( document ).trigger( 'boldermail-init-tooltips' );

					$( '#boldermail-newsletter #taxonomy' )
						.on( 'change', function () {
							const taxonomySelect = $( this );

							// Remove terms selector.
							$( '#boldermail-newsletter #term__includes' ).parent( '.form-field' ).remove();
							$( '#boldermail-newsletter #term__excludes' ).parent( '.form-field' ).remove();

							// Remove any leftover loading icons.
							taxonomySelect.parent( '.form-field' ).find( '.bm-loading' ).remove();

							// Add a loading icon after the select element.
							taxonomySelect.after( '<span class="bm-loading"></span>' );
							const taxonomyLoading = taxonomySelect.siblings( '.bm-loading' );
							taxonomyLoading.css( 'display', 'inline-block' );

							// Load the terms selector.
							$.ajax( {
								type: 'post',
								dataType: 'json',
								url: window.boldermail.ajaxUrl,
								data: {
									action: 'boldermail_rss_taxonomy_terms',
									nonce: window.boldermail.ajaxNonce,
									post: postId,
									taxonomy: taxonomySelect.val(),
								},
							} )
								.always( function () {
									taxonomyLoading.remove();
								} )
								.done( function ( termsHTML ) {
									if ( typeof termsHTML !== 'undefined' ) {
										// Remove terms selector again in case of slow AJAX call.
										$( '#boldermail-newsletter #term__includes' ).parent( '.form-field' ).remove();
										$( '#boldermail-newsletter #term__excludes' ).parent( '.form-field' ).remove();

										// Append HTML.
										$( '#boldermail-newsletter #query_args' ).append( termsHTML );
									}

									$( document ).trigger( 'boldermail-init-select2' );
									$( document ).trigger( 'boldermail-init-tooltips' );
								} )
								.fail( function () {
									$( '#boldermail-newsletter #term__includes' ).attr( 'disabled', 'disabled' ); // Just in case?
									$( '#boldermail-newsletter #term__excludes' ).attr( 'disabled', 'disabled' );
								} );
						} )
						.trigger( 'change' );
				} )
				.fail( function () {
					$( '#boldermail-newsletter #taxonomy' ).attr( 'disabled', 'disabled' ); // Just in case?
				} );
		} )
		.trigger( 'change' );
} );
