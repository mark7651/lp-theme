import './barba.js'
import { LPForm } from './forms.js'
import {
	debounce,
	hideOpenedPanels,
	initAsidePanels,
	lazyVideos,
	processLazyImages,
	setPagePositionTop,
} from './utils.js'

const state = {
	loader: null,
	lenis: null,
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

function initAnimationRefresh(debounceTime = 300) {
	let lastWidth = window.innerWidth

	const handleResize = debounce(() => {
		const currentWidth = window.innerWidth
		if (currentWidth !== lastWidth) {
			lastWidth = currentWidth
			ScrollTrigger.refresh()
			state.lenis?.resize()
		}
	}, debounceTime)

	const observer = new ResizeObserver(handleResize)
	observer.observe(document.body)

	return () => observer.disconnect()
}

class LoaderComponent {
	constructor() {
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
				await state.loader.show()
				hideOpenedPanels()
				ScrollTrigger.getAll().forEach(trigger => trigger.kill())
				ScrollTrigger.clearScrollMemory()
			},

			async enter(data) {
				state.loader.hide()
			},
			async after() {
				setPagePositionTop()
				reinitForBarba()
			},
		},
	],
})

barba.hooks.beforeEnter(({ current, next }) => {
	if (!current?.container) return
	const matches = next.html.match(/<body.+?class="([^""]*)"/i)
	document.body.setAttribute('class', (matches && matches[1]) ?? '')
})

barba.hooks.afterEnter(data => {
	state.lenis.resize()
	initAnimations()
	if (ScrollTrigger) {
		ScrollTrigger.refresh()
	}
})

/*-----------------------------------------------------------------------------------*/
/* functions  */
/*-----------------------------------------------------------------------------------*/

document.addEventListener('DOMContentLoaded', () => {
	initOnceFunctions()
})

function reinitForBarba() {
	lazyVideos()
	initAnimationRefresh()

	new LPForm('form-contact')
	window.addEventListener('load', processLazyImages)
	crollToAnchor()
}

function initOnceFunctions() {
	initializeGSAPAndLenis()
	initAsidePanels()
	state.loader.initializeLoader()
}

function initializeGSAPAndLenis() {
	if (typeof gsap === 'undefined') {
		console.warn('GSAP not loaded')
		return
	}

	gsap.registerPlugin(ScrollTrigger)
	gsap.config({
		nullTargetWarn: false,
		trialWarn: false,
	})
	ScrollTrigger.config({ ignoreMobileResize: true })

	state.lenis = new Lenis({
		//autoRaf: true,
		lerp: 0.12,
		direction: 'vertical',
		smoothWheel: true,
		smoothTouch: false,
		touchMultiplier: 0,
		anchors: true,
	})
	window.lenis = state.lenis

	state.lenis.on('scroll', ScrollTrigger.update)
	gsap.ticker.add(time => state.lenis.raf(time * 1000))
	gsap.ticker.lagSmoothing(0)
	state.loader = new LoaderComponent()

	initAnimations()
	reinitForBarba()
	ScrollTrigger.refresh()
	state.lenis.resize()
}

function initAnimations() {
	//reller()
	initFadeAnimations()
	initHeadingAnimations()
}

function reller() {
	gsap.utils.toArray('.reeller').map((line, i) => {
		const items = line.querySelectorAll('.reeller-item')
		const isReversed = i % 2 === 1

		const tl = horizontalLoop(items, {
			repeat: -1,
			speed: 1.25,
			reversed: isReversed,
			invalidateOnRefresh: true,
			paddingRight: parseFloat(gsap.getProperty(items[0], 'marginRight', 'px')),
		})

		ScrollTrigger.create({
			trigger: line,
			start: 'top bottom',
			end: 'bottom top',
			onEnter: () => tl.timeScale(isReversed ? -1 : 1),
			onLeave: () => tl.timeScale(0),
			onEnterBack: () => tl.timeScale(isReversed ? -1 : 1),
			onLeaveBack: () => tl.timeScale(0),
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
			snap = config.snap === false ? v => v : gsap.utils.snap(config.snap || 1),
			totalWidth,
			curX,
			distanceToStart,
			distanceToLoop,
			item,
			i
		gsap.set(items, {
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
				ease: 'power1.inOut',
			}
		)
		triggers.push(tl.scrollTrigger)
	})

	return () => {
		triggers.forEach(trigger => trigger.kill())
	}
}

function initHeadingAnimations(selector = '[scrub-text]', config = {}) {
	class TextAnimator {
		constructor(selector, config) {
			this.selector = selector
			this.config = Object.assign(
				{
					x: 0,
					duration: 0.6,
					ease: 'power2.out',
					staggerEach: 0,
					staggerAmount: 0.8,
					start: 'top 80%',
					//end: 'bottom 60%',
					//scrub: true,
				},
				config
			)
			this.elements = gsap.utils.toArray(selector)
			this.splits = []
			this.triggers = []
			this.init()
		}

		init() {
			if (typeof SplitType === 'undefined') {
				console.warn('SplitType is not loaded.')
				return
			}

			this.elements.forEach(element => {
				element.setAttribute('aria-label', element.textContent)

				const split = new SplitType(element, {
					types: 'words, chars',
					tagName: 'span',
				})
				this.splits.push(split)

				const tl = gsap
					.timeline({
						paused: true,
						defaults: {
							duration: this.config.duration,
							ease: this.config.ease,
						},
					})
					.from(split.chars, {
						opacity: 0,
						x: this.config.y,
						stagger: {
							each: this.config.staggerEach,
							amount: this.config.staggerAmount,
						},
					})

				const trigger = ScrollTrigger.create({
					trigger: element,
					start: this.config.start,
					end: this.config.end,
					scrub: this.config.scrub,
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

	return new TextAnimator(selector, config)
}

function addClassOnViewport() {
	const blocks = document.querySelectorAll('[viewport-check]')

	blocks.forEach(block => {
		ScrollTrigger.create({
			trigger: block,
			start: 'top 70%',
			once: true,
			onEnter: () => {
				block.classList.add('in-viewport')
			},
		})
	})
}

function rippleEffect(event) {
	const btn = event.currentTarget

	const rect = btn.getBoundingClientRect()

	const circle = document.createElement('span')
	const diameter = Math.max(btn.clientWidth, btn.clientHeight)
	const radius = diameter / 2

	circle.style.width = circle.style.height = `${diameter}px`
	circle.style.left = `${event.clientX - rect.left - radius}px`
	circle.style.top = `${event.clientY - rect.top - radius}px`
	circle.classList.add('ripple')

	const ripple = btn.getElementsByClassName('ripple')[0]
	if (ripple) {
		ripple.remove()
	}

	btn.appendChild(circle)
}

document.querySelectorAll('.btn, .btn-small').forEach(button => {
	button.addEventListener('click', rippleEffect)
})
