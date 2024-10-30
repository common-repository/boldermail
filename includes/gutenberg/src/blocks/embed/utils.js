// Internal dependencies.
import variations from './variations';

// Get embed title by site.
export const getTitleBySite = ( service ) => {
	const variation = variations.find( ( { name } ) => name === service );
	return variation ? variation.title : [];
};

// Get embed icon by site.
export const getIconBySite = ( service ) => {
	const variation = variations.find( ( { name } ) => name === service );
	return variation ? variation.icon : [];
};

// Get embed instructions by site.
export const getInstructionsBySite = ( service ) => {
	const variation = variations.find( ( { name } ) => name === service );
	return variation ? variation.instructions : [];
};
