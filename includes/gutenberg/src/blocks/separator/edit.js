// Internal dependencies.
import SeparatorInspector from './inspector';
import SeparatorWrapper from './wrapper';

// WordPress dependencies.
const { Fragment } = wp.element;

// Edit function.
export default function Edit( props ) {
	return (
		<Fragment>
			<SeparatorInspector { ...props } />
			<SeparatorWrapper { ...props } />
		</Fragment>
	);
}
