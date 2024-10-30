// Internal dependencies.
import ButtonInspector from './inspector';
import ButtonToolbar from './toolbar';
import ButtonWrapper from './wrapper';

// WordPress dependencies.
const { __ } = wp.i18n;
const { RichText } = wp.blockEditor;
const { Fragment } = wp.element;

// Edit function.
export default function edit( props ) {
	const {
		attributes: {
			text,
			letterSpacing,
			textColor,
		},
		className,
		setAttributes,
	} = props;

	return (
		<Fragment>
			<ButtonInspector { ...props } />
			<ButtonToolbar { ...props } />
			<div className={ className }>
				<ButtonWrapper { ...props }>
					<RichText
						placeholder={ __( 'Add text...', 'boldermail' ) }
						allowedFormats={ [ 'core/bold' ] }
						className="bmButton"
						value={ text }
						onChange={ ( value ) => setAttributes( { text: value } ) }
						withoutInteractiveFormatting
						style={ {
							letterSpacing,
							lineHeight: '100%',
							textAlign: 'center',
							textDecoration: 'none',
							color: textColor,
						} }
					/>
				</ButtonWrapper>
			</div>
		</Fragment>
	);
}
