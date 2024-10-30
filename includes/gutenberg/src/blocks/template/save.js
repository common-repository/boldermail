// Internal dependencies.
import TemplateWrapper from './wrapper';

// WordPress dependencies.
const { InnerBlocks } = wp.blockEditor;
const { Fragment } = wp.element;

// Save function.
export default function save( props ) {
	return (
		<Fragment>
			<TemplateWrapper { ...{ ...props, save: true } }>
				<InnerBlocks.Content />
			</TemplateWrapper>
		</Fragment>
	);
}
