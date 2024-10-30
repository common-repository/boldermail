const attributes = {
	align: {
		type: 'string',
	},
	url: {
		type: 'string',
		source: 'attribute',
		selector: 'img',
		attribute: 'src',
	},
	alt: {
		type: 'string',
		source: 'attribute',
		selector: 'img',
		attribute: 'alt',
		default: '',
	},
	caption: {
		type: 'string',
		source: 'html',
		selector: '.bmTextContent > div',
	},
	title: {
		type: 'string',
		source: 'attribute',
		selector: 'img',
		attribute: 'title',
	},
	href: {
		type: 'string',
		source: 'attribute',
		selector: '.bmCaptionBlock a',
		attribute: 'href',
	},
	rel: {
		type: 'string',
		source: 'attribute',
		selector: '.bmCaptionBlock a',
		attribute: 'rel',
	},
	linkClass: {
		type: 'string',
		source: 'attribute',
		selector: '.bmCaptionBlock a',
		attribute: 'class',
	},
	id: {
		type: 'number',
	},
	width: {
		type: 'number',
	},
	height: {
		type: 'number',
	},
	sizeSlug: {
		type: 'string',
	},
	linkDestination: {
		type: 'string',
		default: 'none',
	},
	linkTarget: {
		type: 'string',
		source: 'attribute',
		selector: '.bmCaptionBlock a',
		attribute: 'target',
	},
	borderWidth: {
		type: 'string',
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
	borderRadius: {
		type: 'string',
	},
	textColor: {
		type: 'string',
	},
	fontFamily: {
		type: 'string',
	},
	fontSize: {
		type: 'number',
	},
	lineHeight: {
		type: 'string',
	},
	letterSpacing: {
		type: 'string',
	},
	textAlign: {
		type: 'string',
	},
};

export default attributes;
