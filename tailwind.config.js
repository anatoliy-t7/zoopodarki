const colors = require('tailwindcss/colors');

module.exports = {
	plugins: [require('@tailwindcss/line-clamp')],
	mode: 'jit',
	darkMode: false,
	theme: {
		extend: {
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
		},
		fontFamily: {
			sans: ['Open Sans', 'sans-serif'],
		},
		colors: {
			transparent: 'transparent',
			current: 'currentColor',
			black: colors.black,
			white: colors.white,
			green: colors.lime,
			gray: colors.coolGray,
			red: colors.red,
			orange: colors.orange,
			yellow: colors.amber,
			cyan: colors.cyan,
			blue: colors.sky,
			indigo: colors.indigo,
			pink: colors.rose,
			purple: colors.purple,
		},
	},
	variants: {
		opacity: ['responsive', 'hover', 'focus', 'disabled'],
		scale: ['responsive', 'hover', 'focus', 'active', 'disabled'],
		cursor: ['disabled'],
		extend: {
			opacity: ['disabled', 'group-hover'],
			backgroundColor: ['checked'],
			display: ['group-hover'],
		},
	},
	purge: [
		'./resources/**/*.blade.php',
		'./resources/**/**/*.blade.php',
		'./resources/**/**/**/*.blade.php',
		'./resources/**/*.js',
		'./storage/framework/views/*.php',
		'./config/*.php',
	],
};
