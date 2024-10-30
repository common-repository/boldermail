// Internal dependencies.
import './editor.scss';

// Import Boldermail blocks.
import './blocks/index.js';

// Import Boldermail formats.
import './formats/index.js';

// WordPress dependencies.
const { domReady } = wp;
const { __, setLocaleData } = wp.i18n;
const { setDefaultBlockName } = wp.blocks;
const { select, dispatch, subscribe } = wp.data;
const { getPlugins, unregisterPlugin } = wp.plugins;

// Set default block.
setDefaultBlockName( 'boldermail/paragraph' );

// Define the internationalization functionality.
setLocaleData( { '': {} }, 'boldermail' );

// Unregister all plugins.
domReady( () => {
	getPlugins().map( ( plugin ) => {
		const { name } = plugin;
		if ( name !== 'edit-post' ) {
			unregisterPlugin( name );
		}
		return null;
	} );
} );

// Set image sizes.
domReady( () => {
	subscribe( () => {
		const settings = select( 'core/block-editor' ).getSettings();

		// Only dispatch if property does not exist to avoid infinite loop with subscribe and dispatch.
		if ( ! settings.bmImageSizes || settings.bmImageSizes.length === 0 ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				bmImageSizes: [
					{
						name: __( 'Full Size', 'boldermail' ),
						slug: 'full',
					},
					{
						name: __( 'Boldermail Newsletter', 'boldermail' ),
						slug: 'boldermail_newsletter',
					},
				],
			} );
		}
	} );
} );

// Set font families.
domReady( () => {
	subscribe( () => {
		const settings = select( 'core/block-editor' ).getSettings();

		// Only dispatch if property does not exist to avoid infinite loop with subscribe and dispatch.
		if ( ! settings.bmFontFamilies || settings.bmFontFamilies.length === 0 ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				bmFontFamilies: [
					{
						label: 'Default',
						slug: 'default',
						value: '',
					},
					{
						label: 'Arial',
						slug: 'arial',
						value: 'Arial, Helvetica Neue, Helvetica, sans-serif',
					},
					{
						label: 'Comic Sans',
						slug: 'comic-sans',
						value: 'Comic Sans MS, Marker Felt-Thin, Arial, sans-serif',
					},
					{
						label: 'Courier New',
						slug: 'courier-new',
						value: 'Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace',
					},
					{
						label: 'Georgia',
						slug: 'georgia',
						value: 'Georgia, Times, Times New Roman, serif',
					},
					{
						label: 'Helvetica',
						slug: 'helvetica',
						value: 'Helvetica Neue, Helvetica, Arial, Verdana, sans-serif',
					},
					{
						label: 'Tahoma',
						slug: 'tahoma',
						value: 'Tahoma, Verdana, Segoe, sans-serif',
					},
					{
						label: 'Times New Roman',
						slug: 'times',
						value: 'Times New Roman, Times, Baskerville, Georgia, serif',
					},
					{
						label: 'Trebuchet MS',
						slug: 'trebuchet-ms',
						value: 'Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif',
					},
					{
						label: 'Verdana',
						slug: 'verdana',
						value: 'Verdana, Geneva, sans-serif',
					},
				],
			} );
		}
	} );
} );

// Set font sizes.
domReady( () => {
	subscribe( () => {
		const settings = select( 'core/block-editor' ).getSettings();

		// Only dispatch if property does not exist to avoid infinite loop with subscribe and dispatch.
		if ( ! settings.bmFontSizes || settings.bmFontSizes.length === 0 ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				bmFontSizes: [
					{
						name: __( 'Extra Small', 'boldermail' ),
						size: 12,
						slug: 'x-small',
					},
					{
						name: __( 'Small', 'boldermail' ),
						size: 14,
						slug: 'small',
					},
					{
						name: __( 'Normal', 'boldermail' ),
						size: 16,
						slug: 'normal',
					},
					{
						name: __( 'Medium', 'boldermail' ),
						size: 18,
						slug: 'medium',
					},
					{
						name: __( 'Large', 'boldermail' ),
						size: 20,
						slug: 'large',
					},
					{
						name: __( 'Extra Large', 'boldermail' ),
						size: 22,
						slug: 'x-large',
					},
					{
						name: __( 'Huge', 'boldermail' ),
						size: 26,
						slug: 'xx-large',
					},
				],
			} );
		}
	} );
} );

// Set color palette defaults.
const colors = [
	{
		name: __( 'Black', 'boldermail' ),
		slug: 'mine-shaft',
		color: '#202020',
	},
	{
		name: __( 'White', 'boldermail' ),
		slug: 'white',
		color: '#fff',
	},
	{
		name: __( 'Alabaster', 'boldermail' ),
		slug: 'alabaster',
		color: '#fafafa',
	},
	{
		name: __( 'Gallery', 'boldermail' ),
		slug: 'gallery',
		color: '#eaeaea',
	},
	{
		name: __( 'Dove Gray', 'boldermail' ),
		slug: 'dove-gray',
		color: '#656565',
	},
	{
		name: __( 'Cinnabar', 'boldermail' ),
		slug: 'cinnabar',
		color: '#ea5b3a',
	},
	{
		name: __( 'Bright Sun', 'boldermail' ),
		slug: 'bright-sun',
		color: '#ffd249',
	},
	{
		name: __( 'Deep Blush', 'boldermail' ),
		slug: 'deep-blush',
		color: '#e0629a',
	},
	{
		name: __( 'De York', 'boldermail' ),
		slug: 'de-york',
		color: '#89d085',
	},
	{
		name: __( 'Shakespeare', 'boldermail' ),
		slug: 'shakespeare',
		color: '#4caad8',
	},
];
domReady( () => {
	subscribe( () => {
		const settings = select( 'core/block-editor' ).getSettings();

		// Only dispatch if property does not exist to avoid infinite loop with subscribe and dispatch.
		if ( ! settings.bmColors || settings.bmColors.length === 0 ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				colors,
				bmColors: colors,
			} );
		}
	} );
} );

// Set border styles.
domReady( () => {
	subscribe( () => {
		const settings = select( 'core/block-editor' ).getSettings();

		// Only dispatch if property does not exist to avoid infinite loop with subscribe and dispatch.
		if ( ! settings.bmBorderStyles || settings.bmBorderStyles.length === 0 ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				bmBorderStyles: [
					{
						value: 'none',
						label: __( 'None', 'boldermail' ),
					},
					{
						value: 'solid',
						label: __( 'Solid', 'boldermail' ),
					},
					{
						value: 'dashed',
						label: __( 'Dashed', 'boldermail' ),
					},
					{
						value: 'dotted',
						label: __( 'Dotted', 'boldermail' ),
					},
					{
						value: 'double',
						label: __( 'Double', 'boldermail' ),
					},
					{
						value: 'groove',
						label: __( 'Groove', 'boldermail' ),
					},
					{
						value: 'ridge',
						label: __( 'Ridge', 'boldermail' ),
					},
					{
						value: 'inset',
						label: __( 'Inset', 'boldermail' ),
					},
					{
						value: 'outset',
						label: __( 'Outset', 'boldermail' ),
					},
				],
			} );
		}
	} );
} );

// Set background position.
domReady( () => {
	subscribe( () => {
		const settings = select( 'core/block-editor' ).getSettings();

		// Only dispatch if property does not exist to avoid infinite loop with subscribe and dispatch.
		if ( ! settings.bmBackgroundPositions || settings.bmBackgroundPositions.length === 0 ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				bmBackgroundPositions: [
					{
						value: 'top',
						label: __( 'Top', 'boldermail' ),
					},
					{
						value: 'center',
						label: __( 'Center', 'boldermail' ),
					},
					{
						value: 'bottom',
						label: __( 'Bottom', 'boldermail' ),
					},
				],
			} );
		}
	} );
} );

// Set background repeat.
domReady( () => {
	subscribe( () => {
		const settings = select( 'core/block-editor' ).getSettings();

		// Only dispatch if property does not exist to avoid infinite loop with subscribe and dispatch.
		if ( ! settings.bmBackgroundRepeat || settings.bmBackgroundRepeat.length === 0 ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				bmBackgroundRepeat: [
					{
						value: 'no-repeat',
						label: __( 'None', 'boldermail' ),
					},
					{
						value: 'repeat-x',
						label: __( 'Horizontal', 'boldermail' ),
					},
					{
						value: 'repeat-y',
						label: __( 'Vertical', 'boldermail' ),
					},
					{
						value: 'repeat',
						label: __( 'Both', 'boldermail' ),
					},
				],
			} );
		}
	} );
} );

// Set background sizes.
domReady( () => {
	subscribe( () => {
		const settings = select( 'core/block-editor' ).getSettings();

		// Only dispatch if property does not exist to avoid infinite loop with subscribe and dispatch.
		if ( ! settings.bmBackgroundSizes || settings.bmBackgroundSizes.length === 0 ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				bmBackgroundSizes: [
					{
						value: 'auto',
						label: __( 'Auto', 'boldermail' ),
					},
					{
						value: 'cover',
						label: __( 'Cover', 'boldermail' ),
					},
					{
						value: 'contain',
						label: __( 'Contain', 'boldermail' ),
					},
				],
			} );
		}
	} );
} );

// Set text alignment.
domReady( () => {
	subscribe( () => {
		const settings = select( 'core/block-editor' ).getSettings();

		// Only dispatch if property does not exist to avoid infinite loop with subscribe and dispatch.
		if ( ! settings.bmTextAlignments || settings.bmTextAlignments.length === 0 ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				bmTextAlignments: [
					{
						icon: 'editor-alignleft',
						title: __( 'Align text left', 'boldermail' ),
						align: 'left',
					},
					{
						icon: 'editor-aligncenter',
						title: __( 'Align text center', 'boldermail' ),
						align: 'center',
					},
					{
						icon: 'editor-alignright',
						title: __( 'Align text right', 'boldermail' ),
						align: 'right',
					},
					{
						icon: 'editor-justify',
						title: __( 'Justify text', 'boldermail' ),
						align: 'justify',
					},
				],
			} );
		}
	} );
} );

// Set line heights.
domReady( () => {
	subscribe( () => {
		const settings = select( 'core/block-editor' ).getSettings();

		// Only dispatch if property does not exist to avoid infinite loop with subscribe and dispatch.
		if ( ! settings.bmLineHeights || settings.bmLineHeights.length === 0 ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				bmLineHeights: [
					{ value: '', label: __( 'Default', 'boldermail' ) },
					{ value: '100%', label: __( 'Normal', 'boldermail' ) },
					{ value: '125%', label: __( 'Slight', 'boldermail' ) },
					{ value: '150%', label: __( '1 1/2 spacing', 'boldermail' ) },
					{ value: '200%', label: __( 'Double space', 'boldermail' ) },
				],
			} );
		}
	} );
} );

// Set unit values.
domReady( () => {
	subscribe( () => {
		const settings = select( 'core/block-editor' ).getSettings();

		// Only dispatch if property does not exist to avoid infinite loop with subscribe and dispatch.
		if ( ! settings.bmUnitValues || settings.bmUnitValues.length === 0 ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				bmUnitValues: [
					{ value: '', label: 'Default' },
					{ value: '0px', label: '0px' },
					{ value: '1px', label: '1px' },
					{ value: '2px', label: '2px' },
					{ value: '3px', label: '3px' },
					{ value: '4px', label: '4px' },
					{ value: '5px', label: '5px' },
					{ value: '6px', label: '6px' },
					{ value: '7px', label: '7px' },
					{ value: '8px', label: '8px' },
					{ value: '9px', label: '9px' },
					{ value: '10px', label: '10px' },
					{ value: '11px', label: '11px' },
					{ value: '12px', label: '12px' },
					{ value: '13px', label: '13px' },
					{ value: '14px', label: '14px' },
					{ value: '15px', label: '15px' },
					{ value: '16px', label: '16px' },
					{ value: '17px', label: '17px' },
					{ value: '18px', label: '18px' },
					{ value: '19px', label: '19px' },
					{ value: '20px', label: '20px' },
					{ value: '21px', label: '21px' },
					{ value: '22px', label: '22px' },
					{ value: '23px', label: '23px' },
					{ value: '24px', label: '24px' },
					{ value: '25px', label: '25px' },
					{ value: '26px', label: '26px' },
					{ value: '27px', label: '27px' },
					{ value: '28px', label: '28px' },
					{ value: '29px', label: '29px' },
					{ value: '30px', label: '30px' },
					{ value: '31px', label: '31px' },
					{ value: '32px', label: '32px' },
					{ value: '33px', label: '33px' },
					{ value: '34px', label: '34px' },
					{ value: '35px', label: '35px' },
					{ value: '36px', label: '36px' },
					{ value: '37px', label: '37px' },
					{ value: '38px', label: '38px' },
					{ value: '39px', label: '39px' },
					{ value: '40px', label: '40px' },
					{ value: '41px', label: '41px' },
					{ value: '42px', label: '42px' },
					{ value: '43px', label: '43px' },
					{ value: '44px', label: '44px' },
					{ value: '45px', label: '45px' },
					{ value: '46px', label: '46px' },
					{ value: '47px', label: '47px' },
					{ value: '48px', label: '48px' },
					{ value: '49px', label: '49px' },
					{ value: '50px', label: '50px' },
					{ value: '60px', label: '60px' },
					{ value: '70px', label: '70px' },
					{ value: '80px', label: '80px' },
					{ value: '90px', label: '90px' },
					{ value: '100px', label: '100px' },
				],
			} );
		}
	} );
} );

// Set unit percentages.
domReady( () => {
	subscribe( () => {
		const settings = select( 'core/block-editor' ).getSettings();

		// Only dispatch if property does not exist to avoid infinite loop with subscribe and dispatch.
		if ( ! settings.bmUnitPercentages || settings.bmUnitPercentages.length === 0 ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				bmUnitPercentages: [
					{ value: '', label: 'Default' },
					{ value: '0%', label: '0%' },
					{ value: '1%', label: '1%' },
					{ value: '2%', label: '2%' },
					{ value: '3%', label: '3%' },
					{ value: '4%', label: '4%' },
					{ value: '5%', label: '5%' },
					{ value: '6%', label: '6%' },
					{ value: '7%', label: '7%' },
					{ value: '8%', label: '8%' },
					{ value: '9%', label: '9%' },
					{ value: '10%', label: '10%' },
					{ value: '11%', label: '11%' },
					{ value: '12%', label: '12%' },
					{ value: '13%', label: '13%' },
					{ value: '14%', label: '14%' },
					{ value: '15%', label: '15%' },
					{ value: '16%', label: '16%' },
					{ value: '17%', label: '17%' },
					{ value: '18%', label: '18%' },
					{ value: '19%', label: '19%' },
					{ value: '20%', label: '20%' },
					{ value: '21%', label: '21%' },
					{ value: '22%', label: '22%' },
					{ value: '23%', label: '23%' },
					{ value: '24%', label: '24%' },
					{ value: '25%', label: '25%' },
					{ value: '50%', label: '50%' },
				],
			} );
		}
	} );
} );

// Remove panels from editor.
domReady( () => {
	dispatch( 'core/edit-post' ).removeEditorPanel( 'template' ); // Template.
	dispatch( 'core/edit-post' ).removeEditorPanel( 'post-status' ); // Status and Visibility.
	dispatch( 'core/edit-post' ).removeEditorPanel( 'post-link' ); // Permalink.
	dispatch( 'core/editor' ).disablePublishSidebar(); // Disable Pre-Publish checks.
} );

// Add "Delete Template" button to the Template post.
domReady( () => {
	subscribe( () => {
		if ( 'bm_template' === select( 'core/editor' ).getCurrentPostType() ) {
			const settings = document.querySelector( '.edit-post-header__settings' );

			if ( null === settings ) {
				return;
			}

			// If the button was already added.
			if ( null !== document.querySelector( '.edit-post-header__settings > .editor-post-trash' ) ) {
				return;
			}

			const button = document.createElement( 'a' );
			button.className = 'components-button boldermail-components-button editor-post-trash is-tertiary';
			button.href = window.boldermail.trashTemplateLink;
			button.text = wp.i18n.__( 'Delete Template', 'boldermail' );

			settings.prepend( button );
		}
	} );
} );

// Add "Go Back" button to the Block Template post.
// @see https://stackoverflow.com/a/60907141
// @see https://github.com/WordPress/gutenberg/issues/13555
// @see https://stackoverflow.com/a/58799532
domReady( () => {
	subscribe( () => {
		if ( 'bm_block_template' !== select( 'core/editor' ).getCurrentPostType() ) {
			return;
		}

		const settings = document.querySelector( '.edit-post-header__settings' );

		if ( null === settings ) {
			return;
		}

		// If the button was already added.
		if ( null !== document.querySelector( '.edit-post-header__settings > .boldermail-block-editor-post-goback' ) ) {
			return;
		}

		const button = document.createElement( 'a' );
		button.className =
			'components-button boldermail-components-button boldermail-block-editor-post-goback is-tertiary';
		button.href = window.boldermail.editNewsletterLink;
		button.textContent = __( 'Go Back', 'boldermail' );

		const span = document.createElement( 'span' );
		span.className = 'screen-reader-text';
		span.textContent = __( '(opens in same window)', 'boldermail' );
		button.appendChild( span );

		settings.prepend( button );

		/**
		 * Redirect the user to the parent page upon click.
		 * We do not save the post here because saving the post is done
		 * asynchronously, and all functions available: `isSavingPost`, `isAutosavingPost`,
		 * `didPostSaveRequestSucceed` do not check whether the post actually
		 * finished saving. Until there is a hook that triggers after the post
		 * finishes saving, we simply ask the user to confirm that they saved
		 * the data before redirecting.
		 *
		 * @since 2.0.0
		 */
		document.querySelector( '.boldermail-block-editor-post-goback' ).addEventListener( 'click', function ( e ) {
			e.preventDefault();

			// Get anchor and data.
			const anchor = this;

			// Modify display of button.
			anchor.setAttribute( 'aria-disabled', 'true' );
			anchor.classList.add( 'is-busy' );

			// Confirm with user before redirecting.
			if (
				// eslint-disable-next-line no-alert
				! window.confirm(
					__(
						'Are you sure you want to go back? Did you already save your changes? Click "OK" to exit out of the Block Editor, or "Cancel" to keep editing your template.',
						'boldermail'
					)
				)
			) {
				// Restore display of button.
				anchor.setAttribute( 'aria-disabled', 'false' );
				anchor.classList.remove( 'is-busy' );

				return false;
			}

			// Redirect to edit post page.
			window.location = anchor.getAttribute( 'href' );
		} );
	} );
} );

domReady( () => {
	subscribe( () => {
		if ( 'bm_block_template' !== select( 'core/editor' ).getCurrentPostType() ) {
			return;
		}

		const settings = select( 'core/block-editor' ).getSettings();

		let updateBlockTypes = false;

		// If all blocks are allowed, or it's an array that does not include 'boldermail/template'.
		if (
			settings.allowedBlockTypes === true ||
			( Array.isArray( settings.allowedBlockTypes ) &&
				! settings.allowedBlockTypes.includes( 'boldermail/template' ) )
		) {
			updateBlockTypes = true;
		}

		// Update settings if necessary.
		if ( updateBlockTypes ) {
			dispatch( 'core/block-editor' ).updateSettings( {
				allowedBlockTypes: [
					'core/block', // Reusable block.
					'boldermail/template',
					'boldermail/template-part',
					'boldermail/paragraph',
					'boldermail/heading',
					'boldermail/list',
					'boldermail/image',
					'boldermail/button',
					'boldermail/boxed-text',
					'boldermail/separator',
					'boldermail/social-links',
					'boldermail/social-link',
					'boldermail/social-shares',
					'boldermail/social-share',
					'boldermail/columns',
					'boldermail/column',
					'boldermail/referral',
					'boldermail/embed',
					'boldermail/countdown',
				],
			} );
		}
	} );
} );
