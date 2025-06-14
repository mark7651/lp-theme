const isMob =
	/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
		navigator.userAgent
	)

const isTouchDevice = () => {
	return (
		'ontouchstart' in window ||
		navigator.maxTouchPoints > 0 ||
		navigator.msMaxTouchPoints > 0
	)
}

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

		activePanel = null
		activeTrigger = null

		compensateScrollbar.unlock()
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

		compensateScrollbar.lock()

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

	function closeAllPanels() {
		const openPanels = document.querySelectorAll('.panel-show')
		openPanels.forEach(panel => {
			const id = panel.getAttribute('id')
			const trigger = document.querySelector(`[data-panel="${id}"].active`)
			closePanel(panel, trigger)
		})
	}

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
		closeAllPanels,
	}
}

function initModals() {
	const overlay = document.querySelector('.modal-overlay')
	let activeModal = null
	let activeTrigger = null

	function closeModal(modal, trigger) {
		if (!modal) return

		modal.classList.remove('modal-show')
		document.body.classList.remove('modal-opened')
		trigger?.classList.remove('active')

		compensateScrollbar.unlock()

		activeModal = null
		activeTrigger = null

		overlay.removeEventListener('click', handleOverlayClick)
	}

	function handleOverlayClick() {
		closeModal(activeModal, activeTrigger)
	}

	function handleCloseClick(e) {
		e.stopPropagation()
		closeModal(activeModal, activeTrigger)
	}

	function openModal(modal, trigger) {
		if (typeof lenis !== 'undefined') {
			lenis.stop()
		}

		if (document.body.classList.contains('modal-opened')) {
			closeModal(activeModal, activeTrigger)
		}

		compensateScrollbar.lock()

		activeModal = modal
		activeTrigger = trigger
		document.body.classList.add('modal-opened')
		trigger?.classList.add('active')
		modal.classList.add('modal-show')

		overlay.addEventListener('click', handleOverlayClick)
		const closeBtn = modal.querySelector('.modal-close')
		if (closeBtn) {
			closeBtn.addEventListener('click', handleCloseClick)
		}
	}

	function handleModalTriggerClick(event) {
		const trigger = event.target.closest('.modal-trigger')
		if (!trigger) return

		const modalId = trigger.getAttribute('data-modal')

		if (!modalId) {
			console.warn('Modal trigger is missing data-modal attribute')
			return
		}

		const modal = document.querySelector('#' + modalId)
		if (!modal) {
			console.warn(`Modal with id "${modalId}" not found`)
			return
		}

		openModal(modal, trigger)
	}

	function init() {
		document.removeEventListener('click', handleModalTriggerClick)
		document.addEventListener('click', handleModalTriggerClick)
		document.addEventListener('keydown', e => {
			if (e.key === 'Escape' && activeModal) {
				closeModal(activeModal, activeTrigger)
			}
		})
	}

	function destroy() {
		document.removeEventListener('click', handleModalTriggerClick)
		overlay.removeEventListener('click', handleOverlayClick)

		if (activeModal) {
			const closeBtn = activeModal.querySelector('.modal-close')
			closeBtn?.removeEventListener('click', handleCloseClick)
		}
		document.body.removeAttribute('style')
	}

	function closeModalById(id) {
		const modal = document.querySelector('#' + id)
		const trigger = document.querySelector(`[data-modal="${id}"].active`)
		closeModal(modal, trigger)
	}

	init()

	return {
		closeModal,
		openModal,
		destroy,
		closeModalById,
	}
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
		window.scrollTo({ top: 0, behavior: 'instant' })
	} else if (document.body.scrollTop) {
		document.body.scrollTop = 0
	} else {
		document.documentElement.scrollTop = 0
	}
}

const getScrollbarWidth = () => {
	return window.innerWidth - document.documentElement.clientWidth
}

const compensateScrollbar = (function () {
	const scrollbarWidth = getScrollbarWidth()
	const header = document.querySelector('.header')

	return {
		lock() {
			if (lenis.isStopped && scrollbarWidth > 0) {
				document.body.style.paddingRight = `${scrollbarWidth}px`
				if (header) {
					//header.style.marginRight = `${scrollbarWidth / 2}px`
				}
				lenis.stop()
			}
		},
		unlock() {
			document.body.removeAttribute('style')
			if (header) {
				header.removeAttribute('style')
			}
			lenis.start()
		},
	}
})()

function lazyVideos() {
	const lazyVideos = [].slice.call(document.querySelectorAll('video.lazy'))

	if ('IntersectionObserver' in window) {
		const lazyVideoObserver = new IntersectionObserver(function (
			entries,
			observer
		) {
			entries.forEach(function (video) {
				if (video.isIntersecting) {
					for (var source in video.target.children) {
						var videoSource = video.target.children[source]
						if (
							typeof videoSource.tagName === 'string' &&
							videoSource.tagName === 'SOURCE'
						) {
							videoSource.src = videoSource.dataset.src
						}
					}

					video.target.load()
					video.target.classList.remove('lazy')
					lazyVideoObserver.unobserve(video.target)
				}
			})
		})

		lazyVideos.forEach(function (lazyVideo) {
			lazyVideoObserver.observe(lazyVideo)
		})
	}
}

function scrollToAnchor() {
	const anchorLinks = document.querySelectorAll('a[href*="#"]')

	anchorLinks.forEach(link => {
		link.addEventListener('click', e => {
			e.preventDefault()

			const targetId = link.getAttribute('href').substring(1)
			const targetElement = document.getElementById(targetId)

			if (targetElement) {
				targetElement.scrollIntoView({
					behavior: 'smooth',
					block: 'start',
				})

				// Update URL without jumping
				history.pushState(null, null, `#${targetId}`)

				const asidePanels = initAsidePanels()
				asidePanels.closeAllPanels()
			}
		})
	})
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
}

function accordeon() {
	const container = document.querySelector('.accordeon')
	const descriptions = document.querySelectorAll('.accordeon-item__description')

	descriptions.forEach(initHeight)

	const activeTab = container.querySelector('.accordeon-item.active')
	if (activeTab) {
		const descriptionContainer = activeTab.querySelector(
			'.accordeon-item__description'
		)
		const descriptionHeight = descriptionContainer.getAttribute('data-height')
		descriptionContainer.style.maxHeight = `${descriptionHeight}px`
	}

	container.addEventListener('click', function (event) {
		const tab = event.target.closest('.accordeon-item')
		if (!tab || !container.contains(tab)) return

		toggleItem(tab)
	})

	function toggleItem(tab) {
		const siblings = getSiblings(tab)
		const descriptionContainer = tab.querySelector(
			'.accordeon-item__description'
		)
		const descriptionHeight = descriptionContainer.getAttribute('data-height')

		const isActive = tab.classList.contains('active')

		closeDescriptions(descriptions)
		siblings.forEach(sibling => sibling.classList.remove('active'))
		tab.classList.toggle('active')

		if (!isActive) {
			descriptionContainer.style.maxHeight = `${descriptionHeight}px`
		} else {
			descriptionContainer.style.maxHeight = 0
		}
	}

	function initHeight(item) {
		const height = item.offsetHeight
		item.setAttribute('data-height', height)
		item.style.maxHeight = 0
	}

	function closeDescriptions(descriptionList) {
		descriptionList.forEach(el => (el.style.maxHeight = 0))
	}

	function getSiblings(element) {
		return [...element.parentNode.children].filter(
			sibling =>
				sibling !== element && sibling.classList.contains('accordeon-item')
		)
	}
}

function tabs() {
	const tabContainer = document.querySelector('[data-tabs]')
	if (!tabContainer) return

	const tabs = tabContainer.querySelectorAll('[data-tab-target]')
	const tabContents = document.querySelectorAll('[data-tab-content]')

	tabContainer.addEventListener('click', event => {
		const clickedTab = event.target.closest('[data-tab-target]')
		if (!clickedTab) return

		const targetId = clickedTab.dataset.tabTarget
		const targetContent = document.querySelector(targetId)
		if (!targetContent) return

		tabs.forEach(tab => tab.classList.remove('active'))
		tabContents.forEach(content => content.classList.remove('active'))

		clickedTab.classList.add('active')
		targetContent.classList.add('active')
	})
}

const dropdown = () => {
	const dropdownContainer = document.body

	window.addEventListener('click', e => {
		document.querySelectorAll('.dropdown.active').forEach(item => {
			if (!item.contains(e.target)) {
				closeDropdown(item)
			}
		})
	})

	dropdownContainer.addEventListener('click', event => {
		const dropdownInput = event.target.closest('.dropdown-input')
		const dropdownOption = event.target.closest('.dropdown-panel li')

		if (dropdownInput) {
			const dropdown = dropdownInput.closest('.dropdown')
			toggleDropdown(dropdown)
		}

		if (dropdownOption) {
			selectOption(dropdownOption)
		}
	})

	function toggleDropdown(dropdown) {
		const isActive = dropdown.classList.contains('active')
		document.querySelectorAll('.dropdown.active').forEach(item => {
			if (item !== dropdown) closeDropdown(item)
		})

		dropdown.classList.toggle('active')
		const caret = dropdown.querySelector('.dropdown-input .caret')
		if (caret) caret.classList.toggle('rotate-180', !isActive)
	}

	function closeDropdown(dropdown) {
		dropdown.classList.remove('active')
		const caret = dropdown.querySelector('.dropdown-input .caret')
		if (caret) caret.classList.remove('rotate-180')
	}

	function selectOption(option) {
		const dropdown = option.closest('.dropdown')
		const dropdownValue = dropdown.querySelector('.dropdown-value')
		const input = dropdown.querySelector('.dropdown-input input')
		const options = dropdown.querySelectorAll('.dropdown-panel li')

		let selectedValues = dropdownValue.value
			? dropdownValue.value.split(',')
			: []
		const value = option.getAttribute('data-value')

		if (selectedValues.includes(value)) {
			selectedValues = selectedValues.filter(v => v !== value)
			option.classList.remove('selected-option')
			option.removeAttribute('aria-selected')
		} else {
			selectedValues.push(value)
			option.classList.add('selected-option')
			option.setAttribute('aria-selected', 'true')
		}

		dropdownValue.value = selectedValues.join(',')

		const selectedNames = selectedValues.map(val =>
			Array.from(options)
				.find(opt => opt.getAttribute('data-value') === val)
				.textContent.trim()
		)
		input.value = selectedNames.length > 0 ? selectedNames.join(', ') : ''
	}
}

function initSlider(selector, options = {}, customHandlers = {}) {
	const sliderElement = document.querySelector(selector)
	if (!sliderElement) {
		console.warn(`Slider not found: ${selector}`)
		return
	}

	if (typeof Splide === 'undefined') {
		console.warn('Splide is not loaded')
		return
	}

	try {
		const splide = new Splide(sliderElement, options)

		if (customHandlers.onMounted) {
			splide.on('mounted', () =>
				customHandlers.onMounted(splide, sliderElement)
			)
		}

		if (customHandlers.navigation) {
			const { prevSelector, nextSelector } = customHandlers.navigation
			const prevButtons = document.querySelectorAll(prevSelector)
			const nextButtons = document.querySelectorAll(nextSelector)

			if (prevButtons.length) {
				prevButtons.forEach(btn => (btn.onclick = () => splide.go('<')))
			} else {
				console.warn(`Prev button not found: ${prevSelector}`)
			}

			if (nextButtons.length) {
				nextButtons.forEach(btn => (btn.onclick = () => splide.go('>')))
			} else {
				console.warn(`Next button not found: ${nextSelector}`)
			}
		}

		splide.mount()
	} catch (error) {
		console.error(`Error initializing slider (${selector}):`, error)
	}
}

const stickyHeader = () => {
	const header = document.querySelector('.header')

	if (!header) return

	const headerHeight = header.offsetHeight
	const threshold = headerHeight / 3

	const toggleHeaderClass = () => {
		header.classList.toggle('scrolled', window.scrollY > threshold)
	}
	window.addEventListener('scroll', toggleHeaderClass)
	toggleHeaderClass()
}

export {
	accordeon,
	debounce,
	dropdown,
	handleAnchorClick,
	initAsidePanels,
	initModals,
	initSlider,
	isMob,
	isTouchDevice,
	lazyVideos,
	processLazyImages,
	scrollToAnchor,
	setPagePositionTop,
	stickyHeader,
	tabs,
}
