// WordPress dependencies.
const { __ } = wp.i18n;

const variations = [
	{
		isDefault: true,
		name: 'twitter',
		title: 'Twitter',
		icon: {
			foreground: '#1da1f2',
			src: (
				<svg width="24" height="24" viewBox="0 0 24 24" version="1.1">
					<g>
						<path d="M22.23 5.924c-.736.326-1.527.547-2.357.646.847-.508 1.498-1.312 1.804-2.27-.793.47-1.67.812-2.606.996C18.325 4.498 17.258 4 16.078 4c-2.266 0-4.103 1.837-4.103 4.103 0 .322.036.635.106.935-3.41-.17-6.433-1.804-8.457-4.287-.353.607-.556 1.312-.556 2.064 0 1.424.724 2.68 1.825 3.415-.673-.022-1.305-.207-1.86-.514v.052c0 1.988 1.415 3.647 3.293 4.023-.344.095-.707.145-1.08.145-.265 0-.522-.026-.773-.074.522 1.63 2.038 2.817 3.833 2.85-1.404 1.1-3.174 1.757-5.096 1.757-.332 0-.66-.02-.98-.057 1.816 1.164 3.973 1.843 6.29 1.843 7.547 0 11.675-6.252 11.675-11.675 0-.178-.004-.355-.012-.53.802-.578 1.497-1.3 2.047-2.124z" />
					</g>
				</svg>
			),
		},
		keywords: [ 'tweet', __( 'social', 'boldermail' ) ],
		description: __( 'Embed a tweet.', 'boldermail' ),
		instructions: __(
			'Paste a link to the tweet you want to display on your email. To use this block, you must have entered your access token on the Settings page.',
			'boldermail'
		),
		patterns: [ /^https?:\/\/(www\.)?twitter\.com\/.+/i ],
		attributes: {
			service: 'twitter',
		},
	},
	// {
	// 	name: 'facebook',
	// 	title: 'Facebook',
	// 	icon: {
	// 		foreground: '#3b5998',
	// 		src: (
	// 			<svg width="24" height="24" viewBox="0 0 24 24" version="1.1">
	// 				<path d="M20 3H4c-.6 0-1 .4-1 1v16c0 .5.4 1 1 1h8.6v-7h-2.3v-2.7h2.3v-2c0-2.3 1.4-3.6 3.5-3.6 1 0 1.8.1 2.1.1v2.4h-1.4c-1.1 0-1.3.5-1.3 1.3v1.7h2.7l-.4 2.8h-2.3v7H20c.5 0 1-.4 1-1V4c0-.6-.4-1-1-1z" />
	// 			</svg>
	// 		),
	// 	},
	// 	keywords: [ __( 'social', 'boldermail' ) ],
	// 	description: __( 'Embed a Facebook post.', 'boldermail' ),
	// 	instructions: __( 'Paste a link to the post you want to display on your email.', 'boldermail' ),
	// 	patterns: [ /^https?:\/\/www\.facebook.com\/.+/i ],
	// 	attributes: {
	// 		service: 'facebook',
	// 	},
	// },
	{
		name: 'instagram',
		title: 'Instagram',
		icon: {
			foreground: '#E24361',
			src: (
				<svg width="24" height="24" viewBox="0 0 24 24" version="1.1">
					<g>
						<path d="M12 4.622c2.403 0 2.688.01 3.637.052.877.04 1.354.187 1.67.31.42.163.72.358 1.036.673.315.315.51.615.673 1.035.123.317.27.794.31 1.67.043.95.052 1.235.052 3.638s-.01 2.688-.052 3.637c-.04.877-.187 1.354-.31 1.67-.163.42-.358.72-.673 1.036-.315.315-.615.51-1.035.673-.317.123-.794.27-1.67.31-.95.043-1.234.052-3.638.052s-2.688-.01-3.637-.052c-.877-.04-1.354-.187-1.67-.31-.42-.163-.72-.358-1.036-.673-.315-.315-.51-.615-.673-1.035-.123-.317-.27-.794-.31-1.67-.043-.95-.052-1.235-.052-3.638s.01-2.688.052-3.637c.04-.877.187-1.354.31-1.67.163-.42.358-.72.673-1.036.315-.315.615-.51 1.035-.673.317-.123.794-.27 1.67-.31.95-.043 1.235-.052 3.638-.052M12 3c-2.444 0-2.75.01-3.71.054s-1.613.196-2.185.418c-.592.23-1.094.538-1.594 1.04-.5.5-.807 1-1.037 1.593-.223.572-.375 1.226-.42 2.184C3.01 9.25 3 9.555 3 12s.01 2.75.054 3.71.196 1.613.418 2.186c.23.592.538 1.094 1.038 1.594s1.002.808 1.594 1.038c.572.222 1.227.375 2.185.418.96.044 1.266.054 3.71.054s2.75-.01 3.71-.054 1.613-.196 2.186-.418c.592-.23 1.094-.538 1.594-1.038s.808-1.002 1.038-1.594c.222-.572.375-1.227.418-2.185.044-.96.054-1.266.054-3.71s-.01-2.75-.054-3.71-.196-1.613-.418-2.186c-.23-.592-.538-1.094-1.038-1.594s-1.002-.808-1.594-1.038c-.572-.222-1.227-.375-2.185-.418C14.75 3.01 14.445 3 12 3zm0 4.378c-2.552 0-4.622 2.07-4.622 4.622s2.07 4.622 4.622 4.622 4.622-2.07 4.622-4.622S14.552 7.378 12 7.378zM12 15c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm4.804-8.884c-.596 0-1.08.484-1.08 1.08s.484 1.08 1.08 1.08c.596 0 1.08-.484 1.08-1.08s-.483-1.08-1.08-1.08z" />
					</g>
				</svg>
			),
		},
		keywords: [ __( 'image', 'boldermail' ), __( 'social', 'boldermail' ) ],
		description: __( 'Embed an Instagram post.', 'boldermail' ),
		instructions: __(
			'Paste a link to the media you want to display on your email. To use this block, you must have connected your Instagram account to Boldermail in the Settings page. Please not that due to limitations with the current Instagram API, Boldermail can only embed one of your latest 25 posts on Instagram.',
			'boldermail'
		),
		patterns: [ /^https?:\/\/(www\.)?instagr(\.am|am\.com)\/.+/i ],
		attributes: {
			service: 'instagram',
		},
	},
	{
		name: 'youtube',
		title: 'YouTube',
		icon: {
			foreground: '#ff0000',
			src: (
				<svg width="24" height="24" viewBox="0 0 24 24" version="1.1">
					<path d="M21.8 8s-.195-1.377-.795-1.984c-.76-.797-1.613-.8-2.004-.847-2.798-.203-6.996-.203-6.996-.203h-.01s-4.197 0-6.996.202c-.39.046-1.242.05-2.003.846C2.395 6.623 2.2 8 2.2 8S2 9.62 2 11.24v1.517c0 1.618.2 3.237.2 3.237s.195 1.378.795 1.985c.76.797 1.76.77 2.205.855 1.6.153 6.8.2 6.8.2s4.203-.005 7-.208c.392-.047 1.244-.05 2.005-.847.6-.607.795-1.985.795-1.985s.2-1.618.2-3.237v-1.517C22 9.62 21.8 8 21.8 8zM9.935 14.595v-5.62l5.403 2.82-5.403 2.8z" />
				</svg>
			),
		},
		keywords: [ __( 'music', 'boldermail' ), __( 'video', 'boldermail' ) ],
		description: __( 'Embed a YouTube video.', 'boldermail' ),
		instructions: __( 'Paste a link to the YouTube video you want to display on your email.', 'boldermail' ),
		patterns: [ /^https?:\/\/((m|www)\.)?youtube\.com\/.+/i, /^https?:\/\/youtu\.be\/.+/i ],
		attributes: {
			service: 'youtube',
		},
	},
	{
		name: 'vimeo',
		title: 'Vimeo',
		icon: {
			foreground: '#1ab7ea',
			src: (
				<svg width="24" height="24" viewBox="0 0 24 24" version="1.1">
					<path d="M22.396 7.164c-.093 2.026-1.507 4.8-4.245 8.32C15.323 19.16 12.93 21 10.97 21c-1.214 0-2.24-1.12-3.08-3.36-.56-2.052-1.118-4.105-1.68-6.158-.622-2.24-1.29-3.36-2.004-3.36-.156 0-.7.328-1.634.98l-.978-1.26c1.027-.903 2.04-1.806 3.037-2.71C6 3.95 7.03 3.328 7.716 3.265c1.62-.156 2.616.95 2.99 3.32.404 2.558.685 4.148.84 4.77.468 2.12.982 3.18 1.543 3.18.435 0 1.09-.687 1.963-2.064.872-1.376 1.34-2.422 1.402-3.142.125-1.187-.343-1.782-1.4-1.782-.5 0-1.013.115-1.542.34 1.023-3.35 2.977-4.976 5.862-4.883 2.14.063 3.148 1.45 3.024 4.16z" />
				</svg>
			),
		},
		keywords: [ __( 'video', 'boldermail' ) ],
		description: __( 'Embed a Vimeo video.', 'boldermail' ),
		instructions: __( 'Paste a link to the Vimeo video you want to display on your email.', 'boldermail' ),
		patterns: [ /^https?:\/\/(www\.)?vimeo\.com\/.+/i ],
		attributes: {
			service: 'vimeo',
		},
	},
];

export default variations;
