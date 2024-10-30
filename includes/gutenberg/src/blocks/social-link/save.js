// Internal dependencies.
import SocialLinkWrapper from './wrapper';

// Save function
export default function save( props ) {
	return (
		<SocialLinkWrapper { ...{ ...props, save: true } } />
	);
}
