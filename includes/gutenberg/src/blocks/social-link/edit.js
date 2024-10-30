// Internal dependencies.
import SocialLinkInspector from './inspector';
import SocialLinkWrapper from './wrapper';

// WordPress dependencies.
const { Fragment } = wp.element;

// Save function
export default function edit( props ) {
	return (
		<Fragment>
			<SocialLinkInspector { ...props } />
			<SocialLinkWrapper { ...props } />
		</Fragment>
	);
}
