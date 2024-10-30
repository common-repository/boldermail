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
		type: 'string',
		default: '0px',
	},
	borderStyle: {
		type: 'string',
		default: 'none',
	},
	borderColor: {
		type: 'string',
		default: '#fafafa',
	},
	backgroundColor: {
		type: 'string',
		default: '#fafafa',
	},
	...iconAttributes,
};

export default attributes;
