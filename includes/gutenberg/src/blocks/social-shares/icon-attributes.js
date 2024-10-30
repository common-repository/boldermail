const attributes = {
	shareContent: {
		type: 'string',
		default: 'campaign',
	},
	customURL: {
		type: 'string',
	},
	urlDesc: {
		type: 'string',
	},
	iconStyle: {
		type: 'string',
		default: 'outline',
	},
	iconColor: {
		type: 'string',
		default: 'dark',
	},
	fontFamily: {
		type: 'string',
		default: 'arial',
	},
	fontSize: {
		type: 'number',
		default: 11,
	},
	textColor: {
		type: 'string',
		default: '#202020',
	},
	buttonBorderWidth: {
		type: 'string',
		default: '0px',
	},
	buttonBorderStyle: {
		type: 'string',
		default: 'none',
	},
	buttonBorderColor: {
		type: 'string',
	},
	buttonBorderRadius: {
		type: 'number',
		default: 0,
		unit: '%',
	},
	buttonBackgroundColor: {
		type: 'string',
	},
};

export default attributes;
