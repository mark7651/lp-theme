module.exports = {
	content: ['./*.php', './inc/**/*.php', './templates/**/*.php', './**/*.php'],
	safelist: [
		'lenis',
		'lenis-stopped',
		'panel-show',
		'panel-showed',
		'modal-show',
		'modal-showed',
		'active',
		'scrolled',
		'rotate',
		'loading',
	],
	plugins: [
		require('tailwind-scrollbar')({
			nocompatible: true,
			preferredStrategy: 'pseudoelements',
		}),
	],
	future: {
		hoverOnlyWhenSupported: true,
	},

	theme: {
		container: {
			center: true,
			padding: {
				DEFAULT: '1rem', // 16px
			},
			screens: {
				xs: '475px',
				sm: '640px',
				md: '768px',
				lg: '1024px',
				xl: '1280px',
				xxl: '1440px',
			},
		},
		extend: {
			fontFamily: {
				sans: ['Vela Sans', 'sans-serif'],
				heading: ['DIN Condensed', 'sans-serif'],
			},
			colors: {
				primary: {
					DEFAULT: '#010101',
					subtle: 'rgba(17, 17, 17, 0.13)',
				},
				white: '#fff',
				surface: {
					DEFAULT: '#f6f6f6',
					1: '#eee',
				},
				grey: {
					light: '#9f9fa4',
					dark: '#6f6f74',
				},
				border: {
					DEFAULT: '#e7e7e7',
					light: '#f2f2f2',
				},
				danger: {
					DEFAULT: '#f23b15',
					subtle: 'rgba(242, 59, 21, 0.13)',
				},
				success: {
					DEFAULT: '#00a625',
					subtle: 'rgba(0, 166, 37, 0.13)',
				},
				warning: {
					DEFAULT: '#fca21d',
					subtle: 'rgba(252, 151, 0, 0.13)',
				},
			},
			screens: {
				xs: '475px',
				sm: '640px',
				md: '768px',
				lg: '1024px',
				xl: '1280px',
				xxl: '1440px',
			},
			spacing: {
				6: '0.375rem', // 6px
				8: '0.5rem', // 8px
				10: '0.625rem', // 10px
				12: '0.75rem', // 12px
				14: '0.875rem', // 14px
				16: '1rem', // 16px
				18: '1.125rem', // 18px
				20: '1.25rem', // 20px
				22: '1.375rem', // 22px
				24: '1.5rem', // 24px
				26: '1.625rem', // 26px
				28: '1.75rem', // 28px
				32: '2rem', // 32px
				48: '3rem', // 48px
				50: '3.125rem', // 50px
				54: '3.375rem', // 54px
				58: '3.625rem', // 58px
				70: '4.375rem', // 70px
				102: '6.375rem', // 102px
			},
			boxShadow: {
				box: '0 4px 16px 0 rgba(0, 0, 0, 0.1)',
			},
			fontSize: {
				subhead: [
					'20px',
					{
						lineHeight: '120%',
						letterSpacing: '0',
						fontWeight: '700',
					},
				],

				'body-lg': [
					'18px',
					{
						lineHeight: '122%',
						letterSpacing: '0',
						fontWeight: '700',
					},
				],
				body: [
					'16px',
					{
						lineHeight: '137%',
						letterSpacing: '0',
					},
				],

				// Buttons
				'button-lg': [
					'18px',
					{
						lineHeight: '122%',
						letterSpacing: '0',
						fontWeight: '700',
					},
				],
				button: [
					'16px',
					{
						lineHeight: '137%',
						letterSpacing: '0',
						fontWeight: '700',
					},
				],
				'button-sm': [
					'14px',
					{
						lineHeight: '137%',
						letterSpacing: '0',
						fontWeight: '700',
					},
				],
				'button-xs': [
					'12px',
					{
						lineHeight: '117%',
						letterSpacing: '0',
						fontWeight: '700',
					},
				],

				// Captions
				caption: [
					'14px',
					{
						lineHeight: '143%',
						letterSpacing: '0',
					},
				],

				// Labels
				label: [
					'12px',
					{
						lineHeight: '117%',
						letterSpacing: '0',
					},
				],

				// XS Body
				xs: [
					'10px',
					{
						lineHeight: '120%',
						letterSpacing: '0',
						fontWeight: '700',
					},
				],
			},

			transitionTimingFunction: {
				panel: 'cubic-bezier(0.19, 1, 0.56, 1)',
			},
			opacity: {
				87: '0.87',
			},
			zIndex: {
				600: '600',
				973: '973',
				997: '997',
			},

			keyframes: {
				'slide-in': {
					'0%': { transform: 'translateX(calc(100% + 1rem))' },
					'100%': { transform: 'translateX(0)' },
				},
				'slide-out': {
					'0%': { transform: 'translateX(0)' },
					'100%': { transform: 'translateX(calc(100% + 1rem))' },
				},
				'fade-in': {
					'0%': { opacity: 0 },
					'100%': { opacity: 1 },
				},
				'fade-out': {
					'0%': { opacity: 1 },
					'100%': { opacity: 0 },
				},
				progress: {
					'0%': { width: '100%' },
					'100%': { width: '0%' },
				},
				gradient: {
					'0%, 100%': {
						'background-size': '200% 200%',
						'background-position': 'left center',
					},
					'50%': {
						'background-size': '200% 200%',
						'background-position': 'right center',
					},
				},
				'modal-in': {
					'0%': {
						opacity: '0',
						transform: 'scale(0.96)',
					},
					'100%': {
						opacity: '1',
						transform: 'scale(1)',
					},
				},
				'modal-out': {
					'0%': {
						opacity: '1',
						transform: 'scale(1)',
					},
					'100%': {
						opacity: '0',
						transform: 'scale(0.96)',
					},
				},
				'dropdown-in': {
					'0%': { opacity: '0', transform: 'translateY(-4px)' },
					'100%': { opacity: '1', transform: 'translateY(0)' },
				},
				'dropdown-out': {
					'0%': { opacity: '1', transform: 'translateY(0)' },
					'100%': { opacity: '0', transform: 'translateY(-4px)' },
				},
			},

			animation: {
				'slide-in': 'slide-in 0.3s ease-out',
				'slide-out': 'slide-out 0.3s ease-in',
				'fade-in': 'fade-in 0.3s ease-out',
				'fade-out': 'fade-out 0.3s ease-in',
				progress: 'progress 5s linear forwards',
				gradient: 'gradient 2.5s ease infinite',
				'modal-in': 'modal-in 0.6s cubic-bezier(0.19, 1, 0.56, 1)',
				'modal-out': 'modal-out 0.6s cubic-bezier(0.19, 1, 0.56, 1)',
				'dropdown-in': 'dropdown-in 0.2s ease-out',
				'dropdown-out': 'dropdown-out 0.15s ease-in',
			},
		},
	},
}
