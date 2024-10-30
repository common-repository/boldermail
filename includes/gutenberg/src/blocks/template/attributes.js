const attributes = {
	style: {
		type: 'string',
		source: 'meta',
		meta: '_template_style',
	},
	blockAlignment: {
		type: 'string',
		default: 'center',
	},
	backgroundColor: {
		type: 'string',
		default: '#fafafa',
	},
	borderTopStyle: {
		type: 'string',
		default: 'none',
	},
	borderTopWidth: {
		type: 'string',
		default: '0px',
	},
	borderTopColor: {
		type: 'string',
		default: '#fafafa',
	},
	h1Color: {
		type: 'string',
		default: '#202020',
	},
	h1FontFamily: {
		type: 'string',
		default: 'Helvetica Neue, Helvetica, Arial, Verdana, sans-serif',
	},
	h1FontSize: {
		type: 'number',
		default: 26,
	},
	h1LineHeight: {
		type: 'string',
		default: '125%',
	},
	h1LetterSpacing: {
		type: 'string',
		default: '0px',
	},
	h2Color: {
		type: 'string',
		default: '#202020',
	},
	h2FontFamily: {
		type: 'string',
		default: 'Helvetica Neue, Helvetica, Arial, Verdana, sans-serif',
	},
	h2FontSize: {
		type: 'number',
		default: 22,
	},
	h2LineHeight: {
		type: 'string',
		default: '125%',
	},
	h2LetterSpacing: {
		type: 'string',
		default: '0px',
	},
	h3Color: {
		type: 'string',
		default: '#202020',
	},
	h3FontFamily: {
		type: 'string',
		default: 'Helvetica Neue, Helvetica, Arial, Verdana, sans-serif',
	},
	h3FontSize: {
		type: 'number',
		default: 20,
	},
	h3LineHeight: {
		type: 'string',
		default: '125%',
	},
	h3LetterSpacing: {
		type: 'string',
		default: '0px',
	},
	h4Color: {
		type: 'string',
		default: '#202020',
	},
	h4FontFamily: {
		type: 'string',
		default: 'Helvetica Neue, Helvetica, Arial, Verdana, sans-serif',
	},
	h4FontSize: {
		type: 'number',
		default: 18,
	},
	h4LineHeight: {
		type: 'string',
		default: '125%',
	},
	h4LetterSpacing: {
		type: 'string',
		default: '0px',
	},
	h1MobileFontSize: {
		type: 'number',
		default: 22,
	},
	h1MobileLineHeight: {
		type: 'string',
		default: '125%',
	},
	h2MobileFontSize: {
		type: 'number',
		default: 20,
	},
	h2MobileLineHeight: {
		type: 'string',
		default: '125%',
	},
	h3MobileFontSize: {
		type: 'number',
		default: 18,
	},
	h3MobileLineHeight: {
		type: 'string',
		default: '125%',
	},
	h4MobileFontSize: {
		type: 'number',
		default: 16,
	},
	h4MobileLineHeight: {
		type: 'string',
		default: '150%',
	},
};

export default attributes;
