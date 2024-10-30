// Internal dependencies.
import icon from './icon';
import {
	MIN_SIZE,
	LINK_DESTINATION_MEDIA,
	LINK_DESTINATION_ATTACHMENT,
	ALLOWED_MEDIA_TYPES,
	DEFAULT_SIZE_SLUG,
} from './constants';
import ImageSize from './image-size';
import {
	BorderControl,
	ColorPaletteControl,
	FontFamilyControl,
	FontSizePicker,
	LineHeightControl,
	UnitControl,
} from '../../components';

// External dependencies.
import classnames from 'classnames';

// WordPress dependencies.
const { getBlobByURL, isBlobURL, revokeBlobURL } = wp.blob;
const {
	BaseControl,
	ExternalLink,
	PanelBody,
	ResizableBox,
	Spinner,
	TextareaControl,
	TextControl,
	ToolbarGroup,
	withNotices,
} = wp.components;
const { compose } = wp.compose;
const { withSelect, withDispatch } = wp.data;
const {
	AlignmentToolbar,
	BlockAlignmentToolbar,
	BlockControls,
	BlockIcon,
	InspectorControls,
	InspectorAdvancedControls,
	MediaPlaceholder,
	MediaReplaceFlow,
	RichText,
	__experimentalImageSizeControl: ImageSizeControl,
	__experimentalImageURLInputUI: ImageURLInputUI,
} = wp.blockEditor;
const { Component, Fragment } = wp.element;
const { __, sprintf } = wp.i18n;
const { getPath, prependHTTP } = wp.url;
const { withViewportMatch } = wp.viewport;

export const pickRelevantMediaFiles = ( image ) => {
	const imageProps = {
		alt: image.alt,
		id: image.id,
		link: image.link,
		caption: image.caption
	};

	if ( image && image.sizes && image.sizes.boldermail_newsletter && image.sizes.boldermail_newsletter.url ) {
		imageProps.url = image.sizes.boldermail_newsletter.url;
	} else if (
		image &&
		image.media_details &&
		image.media_details.sizes &&
		image.media_details.sizes.boldermail_newsletter &&
		image.media_details.sizes.boldermail_newsletter.source_url
	) {
		imageProps.url = image.media_details.sizes.boldermail_newsletter.source_url;
	} else {
		imageProps.url = image.url;
	}

	return imageProps;
};

/**
 * Is the URL a temporary blob URL? A blob URL is one that is used temporarily
 * while the image is being uploaded and will not have an id yet allocated.
 *
 * @param   {number=} id    The id of the image.
 * @param   {string=} url   The url of the image.
 * @return  {boolean}       Is the URL a Blob URL
 */
const isTemporaryImage = ( id, url ) => ! id && isBlobURL( url );

/**
 * Is the url for the image hosted externally. An externally hosted image has no id
 * and is not a blob url.
 *
 * @param   {number=} id    The id of the image.
 * @param   {string=} url   The url of the image.
 * @return  {boolean}       Is the url an externally hosted url?
 */
const isExternalImage = ( id, url ) => url && ! id && ! isBlobURL( url );

export class ImageEdit extends Component {
	constructor() {
		super( ...arguments );
		this.updateAlt = this.updateAlt.bind( this );
		this.updateAlignment = this.updateAlignment.bind( this );
		this.onFocusCaption = this.onFocusCaption.bind( this );
		this.onImageClick = this.onImageClick.bind( this );
		this.onSelectImage = this.onSelectImage.bind( this );
		this.onSelectURL = this.onSelectURL.bind( this );
		this.updateImage = this.updateImage.bind( this );
		this.onSetHref = this.onSetHref.bind( this );
		this.onSetTitle = this.onSetTitle.bind( this );
		this.getFilename = this.getFilename.bind( this );
		this.onUploadError = this.onUploadError.bind( this );

		this.state = {
			captionFocused: false,
		};
	}

	componentDidMount() {
		const { attributes, mediaUpload, noticeOperations } = this.props;
		const { id, url = '' } = attributes;

		if ( isTemporaryImage( id, url ) ) {
			const file = getBlobByURL( url );

			if ( file ) {
				mediaUpload( {
					filesList: [ file ],
					onFileChange: ( [ image ] ) => {
						this.onSelectImage( image );
					},
					allowedTypes: ALLOWED_MEDIA_TYPES,
					onError: ( message ) => {
						noticeOperations.createErrorNotice( message );
					},
				} );
			}
		}
	}

	componentDidUpdate( prevProps ) {
		const { id: prevID, url: prevURL = '' } = prevProps.attributes;
		const { id, url = '' } = this.props.attributes;

		if (
			isTemporaryImage( prevID, prevURL ) &&
			! isTemporaryImage( id, url )
		) {
			revokeBlobURL( url );
		}

		if (
			! this.props.isSelected &&
			prevProps.isSelected &&
			this.state.captionFocused
		) {
			this.setState( {
				captionFocused: false,
			} );
		}
	}

	onUploadError( message ) {
		const { noticeOperations } = this.props;
		noticeOperations.removeAllNotices();
		noticeOperations.createErrorNotice( message );
	}

	onSelectImage( media ) {
		if ( ! media || ! media.url ) {
			this.props.setAttributes( {
				url: undefined,
				alt: undefined,
				id: undefined,
				title: undefined,
				caption: undefined,
			} );
			return;
		}

		const {
			id,
			url,
			alt,
			caption,
			linkDestination,
		} = this.props.attributes;

		let mediaAttributes = pickRelevantMediaFiles( media );

		// If the current image is temporary but an alt text was meanwhile written by the user,
		// make sure the text is not overwritten.
		if ( isTemporaryImage( id, url ) ) {
			if ( alt ) {
				// eslint-disable-next-line no-unused-vars
				const { alt: omittedAlt, ...rest } = mediaAttributes;
				mediaAttributes = rest;
			}
		}

		// If a caption text was meanwhile written by the user,
		// make sure the text is not overwritten by empty captions
		if ( caption && ! mediaAttributes.caption ) {
			// eslint-disable-next-line no-unused-vars
			const { caption: omittedCaption, ...rest } = mediaAttributes;
			mediaAttributes = rest;
		}

		let additionalAttributes;
		// Reset the dimension attributes if changing to a different image.
		if ( ! media.id || media.id !== id ) {
			additionalAttributes = {
				width: undefined,
				height: undefined,
				sizeSlug: DEFAULT_SIZE_SLUG,
			};
		} else {
			// Keep the same url when selecting the same file, so "Image Size" option is not changed.
			additionalAttributes = { url };
		}

		// Check if the image is linked to it's media.
		if ( linkDestination === LINK_DESTINATION_MEDIA ) {
			// Update the media link.
			mediaAttributes.href = media.url;
		}

		// Check if the image is linked to the attachment page.
		if ( linkDestination === LINK_DESTINATION_ATTACHMENT ) {
			// Update the media link.
			mediaAttributes.href = media.link;
		}

		this.props.setAttributes( {
			...mediaAttributes,
			...additionalAttributes,
		} );
	}

	onSelectURL( newURL ) {
		const { url } = this.props.attributes;

		if ( newURL !== url ) {
			this.props.setAttributes( {
				url: newURL,
				id: undefined,
				sizeSlug: DEFAULT_SIZE_SLUG,
			} );
		}
	}

	onSetHref( props ) {
		props.href = prependHTTP( props.href );
		this.props.setAttributes( props );
	}

	onSetTitle( value ) {
		// This is the HTML title attribute, separate from the media object title
		this.props.setAttributes( { title: value } );
	}

	onFocusCaption() {
		if ( ! this.state.captionFocused ) {
			this.setState( {
				captionFocused: true,
			} );
		}
	}

	onImageClick() {
		if ( this.state.captionFocused ) {
			this.setState( {
				captionFocused: false,
			} );
		}
	}

	updateAlt( newAlt ) {
		this.props.setAttributes( { alt: newAlt } );
	}

	updateAlignment( nextAlign ) {
		const extraUpdatedAttributes =
			[ 'wide', 'full' ].indexOf( nextAlign ) !== -1 ?
				{ width: undefined, height: undefined } :
				{};
		this.props.setAttributes( {
			...extraUpdatedAttributes,
			align: nextAlign,
		} );
	}

	updateImage( sizeSlug ) {
		const { image } = this.props;

		let url;
		if ( image && image.media_details && image.media_details.sizes && image.media_details.sizes[sizeSlug]) {
			url = image.media_details.sizes[ sizeSlug ].source_url;
		}

		if ( ! url ) {
			return null;
		}

		this.props.setAttributes( {
			url,
			width: undefined,
			height: undefined,
			sizeSlug,
		} );
	}

	getFilename( url ) {
		const path = getPath( url );
		if ( path ) {
			const parts = path.split( '/' );
			return parts[ parts.length - 1 ];
		}
	}

	getImageSizeOptions() {
		const { imageSizes = [], image } = this.props;
		return imageSizes
			.filter(
				( { slug } ) =>
					image &&
					image.media_details &&
					image.media_details.sizes &&
					image.media_details.sizes[ slug ] &&
					image.media_details.sizes[ slug ].source_url
			)
			.map( ( { name, slug } ) => ( { value: slug, label: name } ) );
	}

	render() {
		const {
			attributes,
			setAttributes,
			isLargeViewport,
			isSelected,
			className,
			maxWidth,
			noticeUI,
			isRTL,
			onResizeStart,
			onResizeStop,
		} = this.props;
		const {
			url,
			alt,
			caption,
			align,
			id,
			href,
			rel,
			linkClass,
			linkDestination,
			title,
			width,
			height,
			linkTarget,
			sizeSlug,
			textColor,
			fontFamily,
			fontSize,
			lineHeight,
			letterSpacing,
			textAlign,
			borderWidth,
			borderStyle,
			borderColor,
			borderRadius,
		} = attributes;

		const isExternal = isExternalImage( id, url );
		const controls = (
			<BlockControls>
				<BlockAlignmentToolbar
					controls={ [ 'left', 'center', 'right' ] }
					value={ align }
					onChange={ this.updateAlignment }
					isCollapsed={ false }
				/>
				{ url && (
					<MediaReplaceFlow
						mediaId={ id }
						mediaURL={ url }
						allowedTypes={ ALLOWED_MEDIA_TYPES }
						accept="image/*"
						onSelect={ this.onSelectImage }
						onSelectURL={ this.onSelectURL }
						onError={ this.onUploadError }
					/>
				) }
				{ url && (
					<ToolbarGroup>
						<ImageURLInputUI
							url={ href || '' }
							onChangeUrl={ this.onSetHref }
							linkDestination={ linkDestination }
							mediaUrl={
								this.props.image && this.props.image.source_url
							}
							mediaLink={
								this.props.image && this.props.image.link
							}
							linkTarget={ linkTarget }
							linkClass={ linkClass }
							rel={ rel }
						/>
					</ToolbarGroup>
				) }
			</BlockControls>
		);
		const src = isExternal ? url : undefined;
		const labels = {
			title: ! url ? __( 'Image', 'boldermail' ) : __( 'Edit image', 'boldermail' ),
			instructions: __( 'Upload an image file, pick one from your media library, or add one with a URL.', 'boldermail' ),
		};
		const mediaPreview = !! url && (
			<img
				alt={ __( 'Edit image', 'boldermail' ) }
				title={ __( 'Edit image', 'boldermail' ) }
				className={ 'edit-image-preview' }
				src={ url }
			/>
		);
		const mediaPlaceholder = (
			<MediaPlaceholder
				icon={ <BlockIcon icon={ icon } /> }
				className={ className }
				labels={ labels }
				onSelect={ this.onSelectImage }
				onSelectURL={ this.onSelectURL }
				notices={ noticeUI }
				onError={ this.onUploadError }
				accept="image/*"
				allowedTypes={ ALLOWED_MEDIA_TYPES }
				value={ { id, src } }
				mediaPreview={ mediaPreview }
				disableMediaButtons={ url }
			/>
		);
		if ( ! url ) {
			return (
				<Fragment>
					{ controls }
					{ mediaPlaceholder }
				</Fragment>
			);
		}

		const classes = classnames( className, {
			'is-transient': isBlobURL( url ),
			'is-resized': !! width || !! height,
			'is-focused': isSelected,
			[ `size-${ sizeSlug }` ]: sizeSlug,
		} );

		const isResizable = [ 'wide', 'full' ].indexOf( align ) === -1 && isLargeViewport;

		const imageSizeOptions = this.getImageSizeOptions();

		const getInspectorControls = ( imageWidth, imageHeight ) => (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Image settings', 'boldermail' ) }>
						<TextareaControl
							label={ __( 'Alt text (alternative text)', 'boldermail' ) }
							value={ alt }
							onChange={ this.updateAlt }
							help={
								<Fragment>
									<ExternalLink href="https://www.w3.org/WAI/tutorials/images/decision-tree">
										{ __( 'Describe the purpose of the image', 'boldermail' ) }
									</ExternalLink>
									{ __( 'Leave empty if the image is purely decorative.', 'boldermail' ) }
								</Fragment>
							}
						/>
						<ImageSizeControl
							onChangeImage={ this.updateImage }
							onChange={ ( value ) => setAttributes( value ) }
							slug={ sizeSlug }
							width={ width }
							height={ height }
							imageSizeOptions={ imageSizeOptions }
							isResizable={ isResizable }
							imageWidth={ imageWidth }
							imageHeight={ imageHeight }
						/>
						<BorderControl
							attribute={ {
								borderStyle: 'borderStyle',
								borderWidth: 'borderWidth',
								borderColor: 'borderColor',
							} }
							props={ this.props }
						/>
						<UnitControl
							label={ __( 'Border Radius', 'boldermail' ) }
							options={ 'bmUnitPercentages' }
							attribute={ 'borderRadius' }
							props={ this.props }
						/>
					</PanelBody>
					{ ! RichText.isEmpty( caption ) ? (
						<PanelBody title={ __( 'Caption settings', 'boldermail' ) }>
							<BaseControl label={ __( 'Text Alignment', 'boldermail' ) } >
								<AlignmentToolbar
									value={ textAlign }
									onChange={ newTextAlign => setAttributes( { textAlign: newTextAlign } ) }
									isCollapsed={ false }
									alignmentControls={ [
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
									] }
								/>
							</BaseControl>
							<FontFamilyControl
								attribute={ 'fontFamily' }
								props={ this.props }
							/>
							<FontSizePicker
								attribute={ 'fontSize' }
								props={ this.props }
							/>
							<UnitControl
								label={ __( 'Letter Spacing (in pixels)', 'boldermail' ) }
								attribute={ 'letterSpacing' }
								props={ this.props }
							/>
							<LineHeightControl
								attribute={ 'lineHeight' }
								props={ this.props }
							/>
							<ColorPaletteControl
								label={ __( 'Text Color', 'boldermail' ) }
								attribute={ 'textColor' }
								props={ this.props }
							/>
						</PanelBody>
					) : null }
				</InspectorControls>
				<InspectorAdvancedControls>
					<TextControl
						label={ __( 'Title attribute', 'boldermail' ) }
						value={ title || '' }
						onChange={ this.onSetTitle }
						help={
							<Fragment>
								{ __( 'Describe the role of this image on the page.', 'boldermail' ) }
								<ExternalLink href="https://www.w3.org/TR/html52/dom.html#the-title-attribute">
									{ __( '(Note: many devices and browsers do not display this text.)', 'boldermail' ) }
								</ExternalLink>
							</Fragment>
						}
					/>
				</InspectorAdvancedControls>
			</Fragment>
		);

		// Disable reason: Each block can be selected by clicking on it
		/* eslint-disable jsx-a11y/click-events-have-key-events */
		return (
			<Fragment>
				{ controls }
				<table border="0" cellPadding="0" cellSpacing="0" width="100%" className={ classnames( classes, 'bmCaptionBlock' ) }>
					<tbody className="bmCaptionBlockOuter">
						<tr>
							<td className="bmCaptionBlockInner" valign="top" style={ { padding: '9px' } }>
								<table align="left" border="0" cellPadding="0" cellSpacing="0" width="100%" className="bmCaptionBottomContent" style={ { minWidth: '100%' } }>
									<tbody>
										<tr>
											<td className="bmCaptionBottomImageContent" align={ `${ align || 'center' }` } valign="top" style={ { padding: '0 9px 9px 9px' } }>
												<ImageSize src={ url } dirtynessTrigger={ align }>
													{ ( sizes ) => {
														const {
															imageWidthWithinContainer,
															imageHeightWithinContainer,
															imageWidth,
															imageHeight,
														} = sizes;

														const filename = this.getFilename( url );
														let defaultedAlt;
														if ( alt ) {
															defaultedAlt = alt;
														} else if ( filename ) {
															defaultedAlt = sprintf(
																__( 'This image has an empty alt attribute; its file name is %s', 'boldermail' ),
																filename
															);
														} else {
															defaultedAlt = __( 'This image has an empty alt attribute', 'boldermail' );
														}

														const img = (
															// Disable reason: Image itself is not meant to be interactive, but
															// should direct focus to block.
															/* eslint-disable jsx-a11y/no-noninteractive-element-interactions */
															<Fragment>
																<img
																	src={ url }
																	alt={ defaultedAlt }
																	onClick={ this.onImageClick }
																	style={ {
																		/* maxWidth: '600px', */
																		...( borderWidth && borderStyle && borderColor ) ? { borderWidth, borderStyle, borderColor } : null,
																		borderRadius,
																	} }
																/>
																{ isBlobURL( url ) && <Spinner /> }
															</Fragment>
															/* eslint-enable jsx-a11y/no-noninteractive-element-interactions */
														);

														if (
															! isResizable ||
															! imageWidthWithinContainer
														) {
															return (
																<Fragment>
																	{ getInspectorControls(
																		imageWidth,
																		imageHeight
																	) }
																	<div style={ { width, height } }>
																		{ img }
																	</div>
																</Fragment>
															);
														}

														const currentWidth =
															width || imageWidthWithinContainer;
														const currentHeight =
															height || imageHeightWithinContainer;

														const ratio = imageWidth / imageHeight;
														const minWidth =
															imageWidth < imageHeight ?
																MIN_SIZE :
																MIN_SIZE * ratio;
														const minHeight =
															imageHeight < imageWidth ?
																MIN_SIZE :
																MIN_SIZE / ratio;

														// With the current implementation of ResizableBox, an image needs an explicit pixel value for the max-width.
														// In absence of being able to set the content-width, this max-width is currently dictated by the vanilla editor style.
														// The following variable adds a buffer to this vanilla style, so 3rd party themes have some wiggleroom.
														// This does, in most cases, allow you to scale the image beyond the width of the main column, though not infinitely.
														// @todo It would be good to revisit this once a content-width variable becomes available.
														const maxWidthBuffer = maxWidth * 2.5;

														let showRightHandle = false;
														let showLeftHandle = false;

														/* eslint-disable no-lonely-if */
														// See https://github.com/WordPress/gutenberg/issues/7584.
														if ( align === 'center' ) {
															// When the image is centered, show both handles.
															showRightHandle = true;
															showLeftHandle = true;
														} else if ( isRTL ) {
															// In RTL mode the image is on the right by default.
															// Show the right handle and hide the left handle only when it is aligned left.
															// Otherwise always show the left handle.
															if ( align === 'left' ) {
																showRightHandle = true;
															} else {
																showLeftHandle = true;
															}
														} else {
															// Show the left handle and hide the right handle only when the image is aligned right.
															// Otherwise always show the right handle.
															if ( align === 'right' ) {
																showLeftHandle = true;
															} else {
																showRightHandle = true;
															}
														}
														/* eslint-enable no-lonely-if */

														return (
															<Fragment>
																{ getInspectorControls(
																	imageWidth,
																	imageHeight
																) }
																<ResizableBox
																	size={ {
																		width,
																		height,
																	} }
																	minWidth={ minWidth }
																	maxWidth={ maxWidthBuffer }
																	minHeight={ minHeight }
																	maxHeight={ maxWidthBuffer / ratio }
																	lockAspectRatio
																	enable={ {
																		top: false,
																		right: showRightHandle,
																		bottom: true,
																		left: showLeftHandle,
																	} }
																	onResizeStart={ onResizeStart }
																	onResizeStop={ (
																		event,
																		direction,
																		elt,
																		delta
																	) => {
																		onResizeStop();
																		setAttributes( {
																			width: parseInt(
																				currentWidth + delta.width,
																				10
																			),
																			height: parseInt(
																				currentHeight +
																					delta.height,
																				10
																			),
																		} );
																	} }
																>
																	{ img }
																</ResizableBox>
															</Fragment>
														);
													} }
												</ImageSize>
											</td>
										</tr>
										{ ( ! RichText.isEmpty( caption ) || isSelected ) ? (
											<tr>
												<td className="bmTextContent" valign="top" width="564" style={ {
													padding: '0px 9px',
													color: textColor,
													fontFamily,
													fontSize: fontSize ? fontSize + 'px' : undefined,
													lineHeight,
													letterSpacing,
													textAlign,
												} } >
													<RichText
														tagName="div"
														placeholder={ __( 'Write caption...', 'boldermail' ) }
														value={ caption }
														onChange={ ( value ) =>
															setAttributes( { caption: value } )
														}
														isSelected={ this.state.captionFocused }
														inlineToolbar
													/>
												</td>
											</tr>
										) : null }
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				{ mediaPlaceholder }
			</Fragment>
		);
		/* eslint-enable jsx-a11y/click-events-have-key-events */
	}
}

export default compose( [
	withDispatch( ( dispatch ) => {
		const { toggleSelection } = dispatch( 'core/block-editor' );

		return {
			onResizeStart: () => toggleSelection( false ),
			onResizeStop: () => toggleSelection( true ),
		};
	} ),
	withSelect( ( select, props ) => {
		const { getMedia } = select( 'core' );
		const { getSettings } = select( 'core/block-editor' );
		const {
			attributes: { id },
			isSelected,
		} = props;
		const { mediaUpload, bmImageSizes, isRTL, maxWidth } = getSettings();

		return {
			image: id && isSelected ? getMedia( id ) : null,
			maxWidth,
			isRTL,
			imageSizes: bmImageSizes,
			mediaUpload,
		};
	} ),
	withViewportMatch( { isLargeViewport: 'medium' } ),
	withNotices,
] )( ImageEdit );
