// Internal dependencies.
import SocialShareInspector from './inspector';
import SocialShareWrapper from './wrapper';

// WordPress dependencies.
const { Fragment } = wp.element;

// Edit function.
export default function edit( props ) {
	return (
		<Fragment>
			<SocialShareInspector { ...props } />
			<SocialShareWrapper { ...props } />
		</Fragment>
	);
}
