// WordPress dependencies.
const { __ } = wp.i18n;

// Variations.
const variations = [
	{
		isDefault: true,
		name: 'preheader',
		attributes: {
			part: 'preheader',
			backgroundColor: '#fafafa',
			borderTopWidth: '0px',
			borderTopStyle: 'none',
			borderTopColor: '#202020',
			borderBottomWidth: '0px',
			borderBottomStyle: 'none',
			borderBottomColor: '#202020',
			paddingTop: '9px',
			paddingBottom: '9px',
			textColor: '#656565',
			textFontFamily: 'Helvetica Neue, Helvetica, Arial, Verdana, sans-serif',
			textFontSize: 12,
			textLineHeight: '150%',
			textLetterSpacing: '0px',
			linkColor: '#656565',
			mobileTextFontSize: 14,
			mobileTextLineHeight: '150%',
		},
		title: __( 'Preheader', 'boldermail' ),
		template: [
			[
				'boldermail/paragraph',
				{
					content: __(
						'[boldermail_permalink]View this email in your browser[/boldermail_permalink]',
						'boldermail'
					),
					textAlign: 'center',
				},
			],
		],
	},
	{
		name: 'header',
		attributes: {
			part: 'header',
			backgroundColor: '#fff',
			borderTopWidth: '0px',
			borderTopStyle: 'none',
			borderTopColor: '#202020',
			borderBottomWidth: '0px',
			borderBottomStyle: 'none',
			borderBottomColor: '#202020',
			paddingTop: '9px',
			paddingBottom: '0px',
			textColor: '#202020',
			textFontFamily: 'Helvetica Neue, Helvetica, Arial, Verdana, sans-serif',
			textFontSize: 16,
			textLineHeight: '150%',
			textLetterSpacing: '0px',
			linkColor: '#2baadf',
			mobileTextFontSize: 16,
			mobileTextLineHeight: '150%',
		},
		title: __( 'Header', 'boldermail' ),
		template: [
			[
				'boldermail/heading',
				{
					content: __( 'Enter Your Title Here', 'boldermail' ),
					level: 1,
					textAlign: 'center',
				},
			],
		],
	},
	{
		name: 'body',
		attributes: {
			part: 'body',
			backgroundColor: '#fff',
			borderTopWidth: '0px',
			borderTopStyle: 'none',
			borderTopColor: '#eaeaea',
			borderBottomWidth: '2px',
			borderBottomStyle: 'solid',
			borderBottomColor: '#eaeaea',
			paddingTop: '0px',
			paddingBottom: '9px',
			textColor: '#202020',
			textFontFamily: 'Helvetica Neue, Helvetica, Arial, Verdana, sans-serif',
			textFontSize: 16,
			textLineHeight: '150%',
			textLetterSpacing: '0px',
			linkColor: '#2baadf',
			mobileTextFontSize: 16,
			mobileTextLineHeight: '150%',
		},
		title: __( 'Body', 'boldermail' ),
		template: [
			[
				'boldermail/paragraph',
				{
					content: __(
						'Write your newsletter content here. You may add images, links, GIFs … whatever your heart desires! Be aware, however, that certain elements do not translate well to emails -- for example, embedding Pinterest pins or Instagram posts. To make sure your content will look good when sent, check out the preview using the &quot;Preview&quot; button in the menu above.',
						'boldermail'
					),
				},
			],
		],
	},
	{
		name: 'footer',
		attributes: {
			part: 'footer',
			backgroundColor: '#fafafa',
			borderTopWidth: '0px',
			borderTopStyle: 'none',
			borderTopColor: '#202020',
			borderBottomWidth: '0px',
			borderBottomStyle: 'none',
			borderBottomColor: '#202020',
			paddingTop: '9px',
			paddingBottom: '9px',
			textColor: '#656565',
			textFontFamily: 'Helvetica Neue, Helvetica, Arial, Verdana, sans-serif',
			textFontSize: 12,
			textLineHeight: '150%',
			textLetterSpacing: '0px',
			linkColor: '#656565',
			mobileTextFontSize: 14,
			mobileTextLineHeight: '150%',
		},
		title: __( 'Footer', 'boldermail' ),
		template: [
			[ 'boldermail/separator' ],
			[ 'boldermail/social-links' ],
			[
				'boldermail/paragraph',
				{
					content: __(
						'<em>Copyright © [boldermail_current_year] [boldermail_company_name], All rights reserved.</em><br>[boldermail_permission]',
						'boldermail'
					),
					textAlign: 'center',
				},
			],
			[
				'boldermail/paragraph',
				{
					content: __(
						'<strong>Our mailing address is:</strong><br>[boldermail_company_address]',
						'boldermail'
					),
					textAlign: 'center',
				},
			],
			[
				'boldermail/paragraph',
				{
					content: __(
						'You can unsubscribe from this list by clicking [boldermail_unsubscribe]here[/boldermail_unsubscribe].',
						'boldermail'
					),
					textAlign: 'center',
				},
			],
			[ 'boldermail/referral' ],
		],
	},
];

export default variations;
