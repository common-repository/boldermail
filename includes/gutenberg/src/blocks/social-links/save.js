// Internal dependencies.
import SocialLinksWrapper from './wrapper';

// WordPress dependencies.
const { InnerBlocks } = wp.blockEditor;

// Save function
export default function save( props ) {
	return (
		<SocialLinksWrapper { ...{ ...props, save: true } }>
			<InnerBlocks.Content />
		</SocialLinksWrapper>
	);
}
