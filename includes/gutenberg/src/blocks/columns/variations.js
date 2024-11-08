// WordPress dependencies.
const { __ } = wp.i18n;
const { Path, SVG } = wp.components;

// Template option choices for predefined columns layouts.
const variations = [
	{
		name: 'two-columns-equal',
		title: __( 'Two columns; equal split', 'boldermail' ),
		icon: (
			<SVG
				width="48"
				height="48"
				viewBox="0 0 48 48"
				xmlns="http://www.w3.org/2000/svg"
			>
				<Path
					fillRule="evenodd"
					clipRule="evenodd"
					d="M39 12C40.1046 12 41 12.8954 41 14V34C41 35.1046 40.1046 36 39 36H9C7.89543 36 7 35.1046 7 34V14C7 12.8954 7.89543 12 9 12H39ZM39 34V14H25V34H39ZM23 34H9V14H23V34Z"
				/>
			</SVG>
		),
		isDefault: true,
		innerBlocks: [
			[ 'boldermail/column', { width: 300 } ],
			[ 'boldermail/column', { width: 300 } ],
		],
		scope: [ 'block' ],
	},
	{
		name: 'two-columns-one-third-two-thirds',
		title: __( 'Two columns; one-third, two-thirds split', 'boldermail' ),
		icon: (
			<SVG
				width="48"
				height="48"
				viewBox="0 0 48 48"
				xmlns="http://www.w3.org/2000/svg"
			>
				<Path
					fillRule="evenodd"
					clipRule="evenodd"
					d="M39 12C40.1046 12 41 12.8954 41 14V34C41 35.1046 40.1046 36 39 36H9C7.89543 36 7 35.1046 7 34V14C7 12.8954 7.89543 12 9 12H39ZM39 34V14H20V34H39ZM18 34H9V14H18V34Z"
				/>
			</SVG>
		),
		innerBlocks: [
			[ 'boldermail/column', { width: 200 } ],
			[ 'boldermail/column', { width: 400 } ],
		],
		scope: [ 'block' ],
	},
	{
		name: 'two-columns-two-thirds-one-third',
		title: __( 'Two columns; two-thirds, one-third split', 'boldermail' ),
		icon: (
			<SVG
				width="48"
				height="48"
				viewBox="0 0 48 48"
				xmlns="http://www.w3.org/2000/svg"
			>
				<Path
					fillRule="evenodd"
					clipRule="evenodd"
					d="M39 12C40.1046 12 41 12.8954 41 14V34C41 35.1046 40.1046 36 39 36H9C7.89543 36 7 35.1046 7 34V14C7 12.8954 7.89543 12 9 12H39ZM39 34V14H30V34H39ZM28 34H9V14H28V34Z"
				/>
			</SVG>
		),
		innerBlocks: [
			[ 'boldermail/column', { width: 400 } ],
			[ 'boldermail/column', { width: 200 } ],
		],
		scope: [ 'block' ],
	},
	{
		name: 'three-columns-equal',
		title: __( 'Three columns; equal split', 'boldermail' ),
		icon: (
			<SVG
				width="48"
				height="48"
				viewBox="0 0 48 48"
				xmlns="http://www.w3.org/2000/svg"
			>
				<Path
					fillRule="evenodd"
					d="M41 14a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v20a2 2 0 0 0 2 2h30a2 2 0 0 0 2-2V14zM28.5 34h-9V14h9v20zm2 0V14H39v20h-8.5zm-13 0H9V14h8.5v20z"
				/>
			</SVG>
		),
		innerBlocks: [
			[ 'boldermail/column', { width: 200 } ],
			[ 'boldermail/column', { width: 200 } ],
			[ 'boldermail/column', { width: 200 } ],
		],
		scope: [ 'block' ],
	},
	{
		name: 'three-columns-wider-center',
		title: __( 'Three columns; wide center column', 'boldermail' ),
		icon: (
			<SVG
				width="48"
				height="48"
				viewBox="0 0 48 48"
				xmlns="http://www.w3.org/2000/svg"
			>
				<Path
					fillRule="evenodd"
					d="M41 14a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v20a2 2 0 0 0 2 2h30a2 2 0 0 0 2-2V14zM31 34H17V14h14v20zm2 0V14h6v20h-6zm-18 0H9V14h6v20z"
				/>
			</SVG>
		),
		innerBlocks: [
			[ 'boldermail/column', { width: 150 } ],
			[ 'boldermail/column', { width: 300 } ],
			[ 'boldermail/column', { width: 150 } ],
		],
		scope: [ 'block' ],
	},
];

export default variations;
