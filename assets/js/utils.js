const isMob =
	/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
		navigator.userAgent
	)

function initAsidePanels() {
	const panelFill = document.querySelector('.panel-fill')
	const overlay = document.querySelector('.overlay')
	let activePanel = null
	let activeTrigger = null

	function closePanel(panel, trigger) {
		if (!panel) return

		panel.classList.remove('panel-show')
		document.body.classList.remove('panel-showed')
		trigger?.classList.remove('active')
		document.body.removeAttribute('style')

		activePanel = null
		activeTrigger = null

		if (typeof lenis !== 'undefined') {
			lenis.start()
		}

		overlay.removeEventListener('click', handleOverlayClick)
	}

	function handleOverlayClick() {
		closePanel(activePanel, activeTrigger)
	}

	function handleCloseClick(e) {
		e.stopPropagation()
		closePanel(activePanel, activeTrigger)
	}

	function openPanel(panel, trigger) {
		if (typeof lenis !== 'undefined') {
			lenis.stop()
		}

		if (document.body.classList.contains('panel-showed')) {
			closePanel(activePanel, activeTrigger)
		}

		compensateScrollbar()

		activePanel = panel
		activeTrigger = trigger
		document.body.classList.add('panel-showed')
		trigger.classList.add('active')
		panel.classList.add('panel-show')
		panelFill.style.willChange = 'transform'
		panel.addEventListener(
			'transitionend',
			() => {
				panelFill.style.willChange = 'auto'
			},
			{ once: true }
		)
		overlay.addEventListener('click', handleOverlayClick)
		const closeBtn = panel.querySelector('.panel-close')
		if (closeBtn) {
			closeBtn.addEventListener('click', handleCloseClick)
		}
	}

	function handlePanelTriggerClick(event) {
		const trigger = event.target.closest('.panel-trigger')
		if (!trigger) return

		const panelId = trigger.getAttribute('data-panel')
		if (!panelId) {
			console.warn('Panel trigger is missing data-panel attribute')
			return
		}

		const panel = document.querySelector('#' + panelId)
		if (!panel) {
			console.warn(`Panel with id "${panelId}" not found`)
			return
		}

		openPanel(panel, trigger)
	}

	// Initialize
	function init() {
		document.removeEventListener('click', handlePanelTriggerClick)
		document.addEventListener('click', handlePanelTriggerClick)
		document.addEventListener('keydown', e => {
			if (e.key === 'Escape' && activePanel) {
				closePanel(activePanel, activeTrigger)
			}
		})
	}

	function destroy() {
		document.removeEventListener('click', handlePanelTriggerClick)
		overlay.removeEventListener('click', handleOverlayClick)

		if (activePanel) {
			const closeBtn = activePanel.querySelector('.panel-close')
			closeBtn?.removeEventListener('click', handleCloseClick)
		}
		document.body.removeAttribute('style')
	}

	function closePanelById(id) {
		const panel = document.querySelector('#' + id)
		const trigger = document.querySelector(`[data-panel="${id}"].active`)
		closePanel(panel, trigger)
	}

	init()

	return {
		closePanel,
		openPanel,
		destroy,
		closePanelById,
	}
}

function hideOpenedPanels() {
	const activePanels = document.querySelectorAll('.panel-show')
	const activeBtnTriggers = document.querySelectorAll('.panel-trigger.active')

	activePanels.forEach(function (panel) {
		panel.classList.remove('panel-show')
	})
	activeBtnTriggers.forEach(function (trigger) {
		trigger.classList.remove('active')
	})
	if (lenis) {
		lenis.start()
	}
	document.body.removeAttribute('style')
}

async function processLazyImages() {
	await new Promise(resolve => setTimeout(resolve, 0))
	const lazyImages = document.querySelectorAll('img[loading="lazy"]')
	if (lazyImages && lazyImages.length > 0) {
		lazyImages.forEach(function (image) {
			image.loading = 'eager'
		})
	}
}

const debounce = (func, delay) => {
	let timeout
	return (...args) => {
		clearTimeout(timeout)
		timeout = setTimeout(() => func.apply(this, args), delay)
	}
}

const setPagePositionTop = () => {
	if (window.scrollTo) {
		window.scrollTo(0, 0)
	} else if (document.body.scrollTop) {
		document.body.scrollTop = 0
	} else {
		document.documentElement.scrollTop = 0
	}
}

const getScrollbarWidth = () => {
	const scrollDiv = document.createElement('div')
	scrollDiv.style.width = '100px'
	scrollDiv.style.height = '100px'
	scrollDiv.style.overflow = 'scroll'
	scrollDiv.style.position = 'absolute'
	scrollDiv.style.top = '-9999px'
	scrollDiv.style.scrollbarWidth = 'thin'
	document.body.appendChild(scrollDiv)
	const scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
	document.body.removeChild(scrollDiv)
	return scrollbarWidth
}

const compensateScrollbar = () => {
	const scrollbarWidth = getScrollbarWidth()
	if (scrollbarWidth > 0) {
		const header = document.querySelector('.header .container')
		document.body.style.paddingRight = `${scrollbarWidth}px`
		if (header && header.classList.contains('fixed')) {
			header.style.paddingRight = `${scrollbarWidth}px`
		}
	}
}

function lazyVideos() {
	const lazyVideos = document.querySelectorAll('video.lazy')

	if ('IntersectionObserver' in window) {
		const lazyVideoObserver = new IntersectionObserver((entries, observer) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					const video = entry.target

					Array.from(video.children).forEach(source => {
						if (source.tagName === 'SOURCE' && source.dataset.src) {
							source.src = source.dataset.src
						}
					})

					video.load()
					video.classList.remove('lazy')
					observer.unobserve(video)
				}
			})
		})

		lazyVideos.forEach(video => lazyVideoObserver.observe(video))
	}
}

function scrollToAnchor(hash) {
	if (!hash || !lenis) return

	const target = document.querySelector(hash)
	const header = document.querySelector('.header')
	const headerHeight = header ? header.offsetHeight : 0

	if (target) {
		lenis.scrollTo(target, {
			duration: 0.4,
			offset: -headerHeight,
			easing: t => t,
		})
	}
}

function handleAnchorClick(event) {
	const link = event.target.closest('a')
	if (!link) return

	const href = link.getAttribute('href')
	if (
		!href ||
		link.target === '_blank' ||
		href.startsWith('mailto:') ||
		href.startsWith('tel:')
	)
		return

	const currentLang = document.documentElement.lang
	const newLang = link.getAttribute('hreflang')

	if (newLang && newLang !== currentLang) {
		event.preventDefault()
		window.location.href = href
		return
	}

	const [url, hash] = href.split('#')

	if (hash && (url === '' || url === window.location.pathname)) {
		event.preventDefault()
		scrollToAnchor(`#${hash}`)
		return
	}

	if (hash) {
		localStorage.setItem('scrollToHash', `#${hash}`)
	}

	event.preventDefault()
}

export {
	debounce,
	handleAnchorClick,
	hideOpenedPanels,
	initAsidePanels,
	isMob,
	lazyVideos,
	processLazyImages,
	scrollToAnchor,
	setPagePositionTop,
}
