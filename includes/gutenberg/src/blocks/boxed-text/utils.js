export function attributesFromMedia( setAttributes ) {
	return ( media ) => {
		if ( ! media || ! media.url ) {
			setAttributes( { backgroundUrl: undefined, backgroundId: undefined } );
			return;
		}

		setAttributes( {
			backgroundUrl: media.url,
			backgroundId: media.id,
		} );
	};
}
