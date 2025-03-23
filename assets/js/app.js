import './barba.js'
import {
	debounce,
	handleAnchorClick,
	hideOpenedPanels,
	initAsidePanels,
	lazyVideos,
	processLazyImages,
	scrollToAnchor,
	setPagePositionTop,
} from './utils.js'

import { LPForm } from './forms.js'

let loader, lenis

function initAnimationRefresh(debounceTime = 300) {
	let lastWidth = window.innerWidth

	const handleResize = debounce(entries => {
		const currentWidth = window.innerWidth
		if (currentWidth !== lastWidth) {
			lastWidth = currentWidth
			initAnimationRefresh()
			ScrollTrigger.refresh()
			if (lenis) {
				lenis.resize()
			}
		}
	}, debounceTime)

	const observer = new ResizeObserver(handleResize)
	observer.observe(document.body)

	return () => observer.disconnect()
}

function loadjscssfile(filename, filetype) {
	if (filetype == 'js') {
		const existingScript = document.querySelector('script[src="${filename}"]')
		if (existingScript) {
			existingScript.remove()
		}
		var fileref = document.createElement('script')
		fileref.setAttribute('type', 'text/javascript')
		fileref.setAttribute('src', filename)
	} else if (filetype == 'css') {
		const existingCSS = document.querySelector(`link[href='${filename}']`)
		if (existingCSS) {
			existingCSS.remove()
		}
		var fileref = document.createElement('link')
		fileref.setAttribute('rel', 'stylesheet')
		fileref.setAttribute('type', 'text/css')
		fileref.setAttribute('href', filename)
	}
	if (typeof fileref != 'undefined')
		document.getElementsByTagName('head')[0].appendChild(fileref)
}

class LoaderComponent {
	constructor() {
		this.body = document.querySelector('.layout')
		this.el = document.querySelector('.loader')
		this.backdrop = this.el.querySelector('.loader-backdrop')
		this.fill = this.el.querySelector('.loader-fill')
		this.showTl = this.tlShow()
		this.hideTl = this.tlHide()
		this.initializeLoader()
	}

	initializeLoader() {
		document.documentElement.classList.add('loading')
		gsap.set(this.el, { display: 'block', pointerEvents: 'auto' })
		gsap.set(this.fill, { opacity: 1 })
		gsap.set(this.backdrop, { opacity: 1 })
		gsap.set(this.fill, {
			scaleY: 1,
			transformOrigin: 'bottom bottom',
		})

		window.addEventListener('load', () => {
			this.hide()
		})
	}

	show() {
		document.documentElement.classList.add('loading')
		return this.showTl.play(0)
	}

	hide() {
		document.documentElement.classList.remove('loading')
		return this.hideTl.play(0)
	}

	tlShow() {
		const tl = gsap.timeline({ paused: true })
		tl.set(this.el, { display: 'block', pointerEvents: 'auto' })
		tl.set(this.fill, { opacity: 1 })
		tl.fromTo(this.backdrop, { opacity: 0 }, { opacity: 1 }, 0)
		tl.fromTo(
			this.fill,
			{ scaleY: 0, transformOrigin: 'bottom bottom' },
			{
				scaleY: 1,
				transformOrigin: 'bottom bottom',
				duration: 0.7,
				ease: 'power4.inOut',
				immediateRender: false,
			},
			0
		)
		return tl
	}

	tlHide() {
		const tl = gsap.timeline({ paused: true })
		tl.set(this.el, { pointerEvents: 'none' }, 0)
		tl.set(this.backdrop, { opacity: 0 }, 0)
		tl.to(this.fill, { opacity: 0, duration: 0.7 }, 0)
		tl.set(this.el, { display: 'none' })
		return tl
	}
}

const scripts = {
	init: function () {
		if (document.getElementsByTagName('body')[0].classList.contains('home')) {
			console.log('This is home page')
		} else if (
			document
				.getElementsByTagName('body')[0]
				.classList.contains('page-template-portfolio')
		) {
			console.log('This is projects page')
			this.projects()
		}
	},
	projects: function () {
		//const projectJS = '/wp-content/themes/lptheme/js/fslightbox.js'
		loadjscssfile(projectJS, 'js')
	},
}

barba.init({
	timeout: 4000,
	cacheFirstPage: true,
	preventRunning: true,
	debug: false,
	prevent: ({ el }) => el?.classList?.contains('prevent'),

	transitions: [
		{
			name: 'loader-transition',
			sync: false,

			async leave(data) {
				await loader.show()
				hideOpenedPanels()
				ScrollTrigger.getAll().forEach(trigger => trigger.kill())
				ScrollTrigger.clearScrollMemory()
			},

			async enter(data) {
				loader.hide()
				setTimeout(() => reinitForBarba(), 400)
			},
		},
	],
})

barba.hooks.beforeEnter(({ current, next }) => {
	if (!current?.container) return

	const matches = next.html.match(/<body.+?class="([^""]*)"/i)
	document.body.setAttribute('class', (matches && matches[1]) ?? '')

	//scripts.init()
	setPagePositionTop()
})

barba.hooks.afterEnter(data => {
	lenis.resize()
	initAnimations()
	const hash = localStorage.getItem('scrollToHash')
	if (hash) {
		setTimeout(() => scrollToAnchor(hash), 300)
		localStorage.removeItem('scrollToHash')
	}
})

/*-----------------------------------------------------------------------------------*/
/* functions  */
/*-----------------------------------------------------------------------------------*/

document.addEventListener('DOMContentLoaded', () => {
	initializeGSAPAndLenis()
	initOnceFunctions()
})

function reinitForBarba() {
	lazyVideos()
	initSliders()
	initAnimationRefresh()

	if (ScrollTrigger) {
		ScrollTrigger.refresh()
	}
	new LPForm('form-contact')
	window.addEventListener('load', processLazyImages)
	document.addEventListener('click', handleAnchorClick)
}

function initOnceFunctions() {
	initAsidePanels()
	window.loader = new LoaderComponent()
}

function initializeGSAPAndLenis() {
	if (typeof gsap !== 'undefined') {
		gsap.registerPlugin(ScrollTrigger)
		gsap.config({
			nullTargetWarn: false,
			trialWarn: false,
		})
		ScrollTrigger.config({ ignoreMobileResize: true })

		lenis = new Lenis({
			lerp: 0.14,
			direction: 'vertical',
			smoothWheel: true,
			smoothTouch: false,
			touchMultiplier: 0,
		})

		lenis.on('scroll', ScrollTrigger.update)
		gsap.ticker.add(time => {
			lenis.raf(time * 1000)
		})
		gsap.ticker.lagSmoothing(0)
		loader = new LoaderComponent()

		initAnimations()
		reinitForBarba()

		ScrollTrigger.refresh()
		lenis.resize()
	} else {
		setTimeout(initializeGSAPAndLenis, 50)
	}
}

function initAnimations() {
	reller()
	initFadeAnimations()
	initHeadingAnimations()
}

function reller() {
	let loops = gsap.utils.toArray('.reeller').map((line, i) => {
		const links = line.querySelectorAll('.reeller-item')
		return horizontalLoop(links, {
			repeat: -1,
			speed: 1.5 + i * 1.5,
			reversed: false,
			paddingRight: parseFloat(gsap.getProperty(links[0], 'marginRight', 'px')),
		})
	})

	function horizontalLoop(items, config) {
		items = gsap.utils.toArray(items)
		config = config || {}
		let tl = gsap.timeline({
				repeat: config.repeat,
				paused: config.paused,
				defaults: { ease: 'none' },
				onReverseComplete: () =>
					tl.totalTime(tl.rawTime() + tl.duration() * 100),
			}),
			length = items.length,
			startX = items[0].offsetLeft,
			times = [],
			widths = [],
			xPercents = [],
			curIndex = 0,
			pixelsPerSecond = (config.speed || 1) * 100,
			snap = config.snap === false ? v => v : gsap.utils.snap(config.snap || 1), // some browsers shift by a pixel to accommodate flex layouts, so for example if width is 20% the first element's width might be 242px, and the next 243px, alternating back and forth. So we snap to 5 percentage points to make things look more natural
			totalWidth,
			curX,
			distanceToStart,
			distanceToLoop,
			item,
			i
		gsap.set(items, {
			// convert "x" to "xPercent" to make things responsive, and populate the widths/xPercents Arrays to make lookups faster.
			xPercent: (i, el) => {
				let w = (widths[i] = parseFloat(gsap.getProperty(el, 'width', 'px')))
				xPercents[i] = snap(
					(parseFloat(gsap.getProperty(el, 'x', 'px')) / w) * 100 +
						gsap.getProperty(el, 'xPercent')
				)
				return xPercents[i]
			},
		})
		gsap.set(items, { x: 0 })
		totalWidth =
			items[length - 1].offsetLeft +
			(xPercents[length - 1] / 100) * widths[length - 1] -
			startX +
			items[length - 1].offsetWidth *
				gsap.getProperty(items[length - 1], 'scaleX') +
			(parseFloat(config.paddingRight) || 0)
		for (i = 0; i < length; i++) {
			item = items[i]
			curX = (xPercents[i] / 100) * widths[i]
			distanceToStart = item.offsetLeft + curX - startX
			distanceToLoop =
				distanceToStart + widths[i] * gsap.getProperty(item, 'scaleX')
			tl.to(
				item,
				{
					xPercent: snap(((curX - distanceToLoop) / widths[i]) * 100),
					duration: distanceToLoop / pixelsPerSecond,
				},
				0
			)
				.fromTo(
					item,
					{
						xPercent: snap(
							((curX - distanceToLoop + totalWidth) / widths[i]) * 100
						),
					},
					{
						xPercent: xPercents[i],
						duration:
							(curX - distanceToLoop + totalWidth - curX) / pixelsPerSecond,
						immediateRender: false,
					},
					distanceToLoop / pixelsPerSecond
				)
				.add('label' + i, distanceToStart / pixelsPerSecond)
			times[i] = distanceToStart / pixelsPerSecond
		}
		function toIndex(index, vars) {
			vars = vars || {}
			Math.abs(index - curIndex) > length / 2 &&
				(index += index > curIndex ? -length : length) // always go in the shortest direction
			let newIndex = gsap.utils.wrap(0, length, index),
				time = times[newIndex]
			if (time > tl.time() !== index > curIndex) {
				// if we're wrapping the timeline's playhead, make the proper adjustments
				vars.modifiers = { time: gsap.utils.wrap(0, tl.duration()) }
				time += tl.duration() * (index > curIndex ? 1 : -1)
			}
			curIndex = newIndex
			vars.overwrite = true
			return tl.tweenTo(time, vars)
		}
		tl.next = vars => toIndex(curIndex + 1, vars)
		tl.previous = vars => toIndex(curIndex - 1, vars)
		tl.current = () => curIndex
		tl.toIndex = (index, vars) => toIndex(index, vars)
		tl.times = times
		if (config.reversed) {
			tl.vars.onReverseComplete()
			tl.reverse()
		}
		return tl
	}
}

function initHeadingAnimations() {
	class TextAnimator {
		constructor(selector) {
			this.elements = document.querySelectorAll(selector)
			this.splits = []
			this.triggers = []
			this.init()
		}

		init() {
			this.elements.forEach(element => {
				element.setAttribute('aria-label', element.textContent)
				const split = new SplitType(element, {
					types: 'words, chars',
					tagName: 'span',
				})
				gsap.set(element, { autoAlpha: 1 })

				this.splits.push(split)

				const initialState = {
					opacity: 0,
					y: 30,
					transformOrigin: '0% 50%',
					// filter: 'blur(10px)',
				}

				gsap.set(split.chars, initialState)

				const tl = gsap
					.timeline({
						paused: true,
						defaults: {
							duration: 0.6,
							ease: 'slow(0.7,0.7,false)',
						},
					})
					.to(split.chars, {
						opacity: 1,
						y: 0,
						stagger: 0.02,
					})

				const trigger = ScrollTrigger.create({
					trigger: element,
					start: 'top 90%',
					// scrub: true,
					toggleActions: 'play none none reverse',
					animation: tl,
				})
				this.triggers.push(trigger)
			})
		}

		destroy() {
			this.splits.forEach(split => split.revert())
			this.triggers.forEach(trigger => trigger.kill())
			this.splits = []
			this.triggers = []
		}
	}
	new TextAnimator('.animated-heading')
}

function initFadeAnimations() {
	if (typeof gsap === 'undefined') {
		console.warn('GSAP not loaded')
		return
	}

	const fadeElems = gsap.utils.toArray('.fade-in')
	if (!fadeElems.length) return

	const triggers = []

	fadeElems.forEach((elem, index) => {
		const tl = gsap.timeline({
			scrollTrigger: {
				trigger: elem,
				start: 'top bottom',
				toggleActions: 'play none none none',
				onEnter: () => {
					elem.classList.add('animated')
				},
				onLeave: () => gsap.set(elem, { clearProps: 'willChange' }),
			},
		})

		tl.fromTo(
			elem,
			{
				yPercent: 20,
				opacity: 0,
			},
			{
				yPercent: 0,
				opacity: 1,
				duration: 0.6,
				willChange: 'opacity, transform',
				delay: index * 0.02,
				ease: 'power1.out',
			}
		)
		triggers.push(tl.scrollTrigger)
	})

	return () => {
		triggers.forEach(trigger => trigger.kill())
	}
}

function initSliders() {
	const pressSlider = document.querySelector('.press-slider')
	if (!pressSlider) return

	if (typeof Splide === 'undefined') {
		console.warn('Splide is not loaded')
		return
	}

	try {
		const splide = new Splide(pressSlider, {
			type: 'loop',
			perPage: 3,
			perMove: 1,
			gap: 30,
			autoHeight: true,
			arrows: false,
			pagination: false,
			autoplay: false,
			interval: 4000,
			breakpoints: {
				868: {
					perPage: 2,
				},
				568: {
					perPage: 1,
				},
			},
		})

		const prevButton = document.querySelector('.press-prev__btn')
		const nextButton = document.querySelector('.press-next__btn')

		if (prevButton) {
			prevButton.onclick = () => splide.go('<')
		} else {
			console.warn('Prev button not found')
		}

		if (nextButton) {
			nextButton.onclick = () => splide.go('>')
		} else {
			console.warn('Next button not found')
		}

		splide.mount()
	} catch (error) {
		console.error('Error initializing slider:', error)
	}
}
