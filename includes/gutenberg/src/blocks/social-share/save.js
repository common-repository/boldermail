// Internal dependencies.
import SocialShareWrapper from './wrapper';

// Save function.
export default function save( props ) {
	return (
		<SocialShareWrapper { ...{ ...props, save: true } } />
	);
}
