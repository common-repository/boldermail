// Internal dependencies.
import TemplateWrapper from './wrapper';

// WordPress dependencies.
const { InnerBlocks } = wp.blockEditor;

// Save function.
export default function save( props ) {
	return (
		<TemplateWrapper { ...{ ...props, save: true } }>
			<InnerBlocks.Content />
		</TemplateWrapper>
	);
}