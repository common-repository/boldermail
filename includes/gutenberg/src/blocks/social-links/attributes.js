import iconAttributes from './icon-attributes';

const attributes = {
	isFullWidth: {
		type: 'boolean',
		default: true,
	},
	blockAlignment: {
		type: 'string',
		default: 'center',
	},
	borderWidth: {
		type: 'number',
		default: '2px',
	},
	borderStyle: {
		type: 'string',
		default: 'none',
	},
	borderColor: {
		type: 'string',
		default: '#202020',
	},
	backgroundColor: {
		type: 'string',
		default: '',
	},
	...iconAttributes,
};

export default attributes;
