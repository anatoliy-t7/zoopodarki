const colors = require('tailwindcss/colors');

module.exports = {
	plugins: [
		require('@tailwindcss/typography'),
		require('@tailwindcss/line-clamp'),
	],
	theme: {
		extend: {
			zIndex: {
				60: '60',
				70: '70',
				80: '80',
				90: '90',
				100: '100',
			},
			ringWidth: ['hover'],
			minWidth: {
				half: '50vw',
			},
			maxHeight: {
				half: '50vh',
			},
			animation: {
				'reverse-spin': 'reverse-spin 1s linear infinite',
			},
			keyframes: {
				'reverse-spin': {
					from: {
						transform: 'rotate(360deg)',
					},
				},
			},
			gridTemplateRows: {
				8: 'repeat(8, minmax(0, 1fr))',
			},
		},
		fontFamily: {
			nunito: ['nunito', 'sans-serif'],
		},
		colors: {
			transparent: 'transparent',
			current: 'currentColor',
			black: colors.black,
			white: colors.white,
			green: colors.lime,
			gray: colors.slate,
			red: colors.red,
			orange: colors.orange,
			yellow: colors.amber,
			cyan: colors.cyan,
			blue: colors.sky,
			indigo: colors.indigo,
			pink: colors.rose,
			purple: colors.violet,
			amber: colors.amber,
		},
	},
	content: [
		'./resources/**/*.blade.php',
		'./resources/**/**/*.blade.php',
		'./resources/**/**/**/*.blade.php',
		'./resources/**/*.js',
		'./storage/framework/views/*.php',
		'./config/*.php',
	],
};
