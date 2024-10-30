// Internal dependencies.
import SocialSharesWrapper from './wrapper';

// WordPress dependencies.
const { InnerBlocks } = wp.blockEditor;

// Save function.
export default function save( props ) {
	return (
		<SocialSharesWrapper { ...{ ...props, save: true } }>
			<InnerBlocks.Content />
		</SocialSharesWrapper>
	);
}
