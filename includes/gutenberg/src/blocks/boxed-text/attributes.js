const attributes = {
	isWideWidth: {
		type: 'boolean',
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
	paddingTop: {
		type: 'string',
		default: '9px',
	},
	paddingBottom: {
		type: 'string',
		default: '9px',
	},
	backgroundColor: {
		type: 'string',
		default: '#079bc4',
	},
	backgroundId: {
		type: 'number',
	},
	backgroundUrl: {
		type: 'string',
	},
	backgroundRepeat: {
		type: 'string',
		default: 'no-repeat',
	},
	backgroundPosition: {
		type: 'string',
		default: 'center',
	},
	backgroundSize: {
		type: 'string',
		default: 'cover',
	},
};

export default attributes;
