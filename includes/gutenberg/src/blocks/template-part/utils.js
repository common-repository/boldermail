// Internal dependencies.
import variations from './variations';

// Get template title by part.
export const getTitleByPart = ( part ) => {
	const variation = variations.find( ( { name } ) => name === part );
	return variation ? variation.title : [];
};

// Get template array by part.
export const getTemplateByPart = ( part ) => {
	const variation = variations.find( ( { name } ) => name === part );
	return variation ? variation.template : [];
};

// Get template array by part.
export const getAttributesByPart = ( part ) => {
	const variation = variations.find( ( { name } ) => name === part );
	return variation ? variation.attributes : [];
};
