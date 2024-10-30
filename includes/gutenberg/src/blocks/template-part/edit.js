// Internal dependencies.
import { getTemplateByPart } from './utils';
import ALLOWED_BLOCKS from './allowed-blocks';
import TemplateInspector from './inspector';
import TemplateWrapper from './wrapper';

// WordPress dependencies.
const { InnerBlocks } = wp.blockEditor;

// Edit function.
export default function edit( props ) {
	const {
		attributes: { preheaderStyle, headerStyle, bodyStyle, footerStyle, part },
		className,
	} = props;

	let style = '';

	switch ( part ) {
		case 'preheader':
			style = preheaderStyle;
			break;
		case 'header':
			style = headerStyle;
			break;
		case 'body':
			style = bodyStyle;
			break;
		case 'footer':
			style = footerStyle;
			break;
	}

	return (
		<div className={ className }>
			<style>{ style }</style>
			<TemplateInspector { ...props } />
			<TemplateWrapper { ...props }>
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					template={ getTemplateByPart( part ) }
					templateLock={ false }
					renderAppender={ () => (
						<InnerBlocks.ButtonBlockAppender />
					) }
				/>
			</TemplateWrapper>
		</div>
	);
}
