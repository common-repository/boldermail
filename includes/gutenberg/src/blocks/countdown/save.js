// Internal dependencies.
import CountdownWrapper from './wrapper';

// Save function.
export default function save( props ) {
	return <CountdownWrapper { ...{ ...props, save: true } } />;
}
