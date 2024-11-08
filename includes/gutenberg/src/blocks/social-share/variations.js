// WordPress dependencies.
const { __ } = wp.i18n;

const variations = [
	{
		isDefault: true,
		name: 'facebook',
		attributes: {
			service: 'facebook',
			label: __( 'Share', 'boldermail' ),
			url: 'http://www.facebook.com/sharer/sharer.php?u=[boldermail_permalink]',
		},
		title: 'Facebook',
		icon: <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.5 2 2 6.5 2 12c0 5 3.7 9.1 8.4 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7C18.3 21.1 22 17 22 12c0-5.5-4.5-10-10-10z" /></svg>,
	},
	//	{ /* let's add this service if someone requests it */
	//		name: 'forwardtofriend',
	//		attributes: { service: 'forwardtofriend' },
	//		title: 'Email',
	//		text: 'Forward',
	//		icon: <svg width="24" height="24" viewBox="0 0 24 24" version="1.1"><path d="M20,4H4C2.895,4,2,4.895,2,6v12c0,1.105,0.895,2,2,2h16c1.105,0,2-0.895,2-2V6C22,4.895,21.105,4,20,4z M20,8.236l-8,4.882 L4,8.236V6h16V8.236z" /></svg>,
	//	},
	{
		name: 'linkedin',
		attributes: {
			service: 'linkedin',
			label: __( 'Share', 'boldermail' ),
			url: 'http://www.linkedin.com/shareArticle?url=[boldermail_permalink]&mini=true&title=[boldermail_title]',
		},
		title: 'LinkedIn',
		icon: <svg width="24" height="24" viewBox="0 0 24 24" version="1.1"><path d="M19.7,3H4.3C3.582,3,3,3.582,3,4.3v15.4C3,20.418,3.582,21,4.3,21h15.4c0.718,0,1.3-0.582,1.3-1.3V4.3 C21,3.582,20.418,3,19.7,3z M8.339,18.338H5.667v-8.59h2.672V18.338z M7.004,8.574c-0.857,0-1.549-0.694-1.549-1.548 c0-0.855,0.691-1.548,1.549-1.548c0.854,0,1.547,0.694,1.547,1.548C8.551,7.881,7.858,8.574,7.004,8.574z M18.339,18.338h-2.669 v-4.177c0-0.996-0.017-2.278-1.387-2.278c-1.389,0-1.601,1.086-1.601,2.206v4.249h-2.667v-8.59h2.559v1.174h0.037 c0.356-0.675,1.227-1.387,2.526-1.387c2.703,0,3.203,1.779,3.203,4.092V18.338z" /></svg>,
	},
	{
		name: 'pinterest',
		attributes: {
			service: 'pinterest',
			label: __( 'Pin', 'boldermail' ),
			url: 'https://www.pinterest.com/pin/find/?url=[boldermail_permalink]',
		},
		title: 'Pinterest',
		icon: <svg width="24" height="24" viewBox="0 0 24 24" version="1.1"><path d="M12.289,2C6.617,2,3.606,5.648,3.606,9.622c0,1.846,1.025,4.146,2.666,4.878c0.25,0.111,0.381,0.063,0.439-0.169 c0.044-0.175,0.267-1.029,0.365-1.428c0.032-0.128,0.017-0.237-0.091-0.362C6.445,11.911,6.01,10.75,6.01,9.668 c0-2.777,2.194-5.464,5.933-5.464c3.23,0,5.49,2.108,5.49,5.122c0,3.407-1.794,5.768-4.13,5.768c-1.291,0-2.257-1.021-1.948-2.277 c0.372-1.495,1.089-3.112,1.089-4.191c0-0.967-0.542-1.775-1.663-1.775c-1.319,0-2.379,1.309-2.379,3.059 c0,1.115,0.394,1.869,0.394,1.869s-1.302,5.279-1.54,6.261c-0.405,1.666,0.053,4.368,0.094,4.604 c0.021,0.126,0.167,0.169,0.25,0.063c0.129-0.165,1.699-2.419,2.142-4.051c0.158-0.59,0.817-2.995,0.817-2.995 c0.43,0.784,1.681,1.446,3.013,1.446c3.963,0,6.822-3.494,6.822-7.833C20.394,5.112,16.849,2,12.289,2" /></svg>,
	},
	{
		name: 'twitter',
		attributes: {
			service: 'twitter',
			label: __( 'Tweet', 'boldermail' ),
			url: 'http://twitter.com/intent/tweet?text=[boldermail_title]: [boldermail_permalink]',
		},
		title: 'Twitter',
		icon: <svg width="24" height="24" viewBox="0 0 24 24" version="1.1"><path d="M22.23,5.924c-0.736,0.326-1.527,0.547-2.357,0.646c0.847-0.508,1.498-1.312,1.804-2.27 c-0.793,0.47-1.671,0.812-2.606,0.996C18.324,4.498,17.257,4,16.077,4c-2.266,0-4.103,1.837-4.103,4.103 c0,0.322,0.036,0.635,0.106,0.935C8.67,8.867,5.647,7.234,3.623,4.751C3.27,5.357,3.067,6.062,3.067,6.814 c0,1.424,0.724,2.679,1.825,3.415c-0.673-0.021-1.305-0.206-1.859-0.513c0,0.017,0,0.034,0,0.052c0,1.988,1.414,3.647,3.292,4.023 c-0.344,0.094-0.707,0.144-1.081,0.144c-0.264,0-0.521-0.026-0.772-0.074c0.522,1.63,2.038,2.816,3.833,2.85 c-1.404,1.1-3.174,1.756-5.096,1.756c-0.331,0-0.658-0.019-0.979-0.057c1.816,1.164,3.973,1.843,6.29,1.843 c7.547,0,11.675-6.252,11.675-11.675c0-0.178-0.004-0.355-0.012-0.531C20.985,7.47,21.68,6.747,22.23,5.924z" /></svg>,
	},
	{
		name: 'instapaper',
		attributes: {
			service: 'instapaper',
			label: __( 'Read Later', 'boldermail' ),
			url: 'http://www.instapaper.com/hello2?url=[boldermail_permalink]&title=[boldermail_title]',
		},
		title: 'Instapaper',
		icon: <svg width="24" height="24" viewBox="0 0 32 32" version="1.1"><path d="M8.938 5A3.951 3.951 0 0 0 5 8.938v14.124A3.951 3.951 0 0 0 8.938 27h14.124A3.951 3.951 0 0 0 27 23.062V8.938A3.951 3.951 0 0 0 23.062 5zm0 2h14.124C24.145 7 25 7.855 25 8.938v14.124A1.922 1.922 0 0 1 23.062 25H8.938A1.922 1.922 0 0 1 7 23.062V8.938C7 7.856 7.855 7 8.938 7zm3.25 2.031A.183.183 0 0 0 12 9.22v.687c0 .098.059.172.156.188.875.12 2.844.5 2.844.937V21c0 .996-2.793.938-2.813.938-.109.003-.187.082-.187.187v.688c0 .105.078.218.188.218h7.593c.11 0 .219-.113.219-.218v-.688c0-.105-.078-.184-.188-.188-.019 0-2.812.024-2.812-.937v-9.969c0-.523 2.129-.86 2.813-.937.101-.012.187-.09.187-.188V9.22c0-.106-.11-.188-.219-.188z" /></svg>,
	},
];

export default variations;
