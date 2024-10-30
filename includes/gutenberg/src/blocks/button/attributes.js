const attributes = {
	url: {
		type: 'string',
		source: 'attribute',
		selector: 'a',
		attribute: 'href',
	},
	text: {
		type: 'string',
		source: 'html',
		selector: 'a',
	},
	blockAlignment: {
		type: 'string',
		default: 'center',
	},
	fontFamily: {
		type: 'string',
		default: 'arial',
	},
	fontSize: {
		type: 'number',
		default: 16,
	},
	letterSpacing: {
		type: 'string',
		default: '0px',
	},
	textColor: {
		type: 'string',
		default: '#fff',
	},
	borderStyle: {
		type: 'string',
		default: 'none',
	},
	borderWidth: {
		type: 'string',
		default: '2px',
	},
	borderColor: {
		type: 'string',
		default: '#000',
	},
	backgroundColor: {
		type: 'string',
		default: '#079bc4',
	},
	borderRadius: {
		type: 'string',
		default: '3px',
	},
	padding: {
		type: 'string',
		default: '18px',
	},
};

export default attributes;
