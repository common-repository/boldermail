// Internal dependencies.
import variations from './variations';

// Get template title by part.
export const getTitleBySite = ( part ) => {
	const variation = variations.find( ( { name } ) => name === part );
	return variation ? variation.title : [];
};
