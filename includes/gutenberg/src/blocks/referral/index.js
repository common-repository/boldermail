// Internal dependencies.
import { name, category, parent } from './block.json';
import ReferralWrapper from './wrapper';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block.
export default registerBlockType( name, {
	title: __( 'Referral', 'boldermail' ),
	description: __( 'Show some love for Boldermail! (You may delete this block if you wish.)', 'boldermail' ),
	category,
	icon: 'email',
	keywords: [
		__( 'affiliate', 'boldermail' ),
		__( 'boldermail', 'boldermail' ),
		__( 'referral', 'boldermail' ),
	],
	example: {},
	supports: {
		customClassName: false,
		html: false,
		reusable: false,
	},
	parent,
	edit: ( props ) => {
		return (
			<ReferralWrapper { ...props }>
				<a href="https://www.boldermail.com/?utm_source=newsletter&utm_medium=email&utm_campaign=referral" target="_blank" rel="noopener noreferrer">
					<img alt={ __( 'Boldermail Banner', 'boldermail' ) } src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/07/banner-324x108_3.png" width="162" height="54" style={ {
						width: '162px',
						height: '54px',
					} } />
				</a>
			</ReferralWrapper>
		);
	},
	save: ( props ) => {
		return (
			<ReferralWrapper { ...{ ...props, save: true } }>
				<a href="https://www.boldermail.com/?utm_source=newsletter&utm_medium=email&utm_campaign=referral" target="_blank" rel="noopener noreferrer" style={ { } }>
					<img alt={ __( 'Boldermail Banner', 'boldermail' ) } src="https://i3.wp.com/boldermail.com/wp-content/uploads/2020/07/banner-324x108_3.png" width="162" height="54" style={ {
						width: '162px',
						height: '54px',
					} } />
				</a>
			</ReferralWrapper>
		);
	},
} );
