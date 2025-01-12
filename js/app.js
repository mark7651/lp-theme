/*-----------------------------------------------------------------------------------*/
/* functions init */
/*-----------------------------------------------------------------------------------*/

pagePreloader()
helperFunctions()
cookieFunctions()

/*-----------------------------------------------------------------------------------*/
/* declare const */
/*-----------------------------------------------------------------------------------*/

const overlay = document.querySelector('.lp-overlay')
const html = document.querySelector('html')
const splide = document.querySelector('.splide')

/*-----------------------------------------------------------------------------------*/
/* functions */
/*-----------------------------------------------------------------------------------*/

document.addEventListener('DOMContentLoaded', () => {
	asidePanels()

	const form_1 = new LPform('form-contact')
	const form_2 = new LPform('form-callback')

	phoneMask()
	faqAccordeon()
	mobileMenu()
	pagePrerender()
})

function pagePreloader() {
	document.addEventListener('DOMContentLoaded', () => {
		document.body.classList.remove('is-loading')
		document.body.classList.add('loaded')

		const links = document.querySelectorAll('a')
		if (links) {
			links.forEach(link => {
				link.addEventListener('click', event => {
					const href = link.getAttribute('href')
					if (href && href.startsWith(window.location.origin)) {
						document.body.classList.remove('loaded')
						document.body.classList.add('loading')
					}
				})
			})
		}
	})

	window.addEventListener(
		'pagehide',
		event => {
			if (event.persisted) {
				setTimeout(() => {
					document.body.classList.remove('loading')
					document.body.classList.add('loaded')
				}, 500)
			}
		},
		false
	)
}

function cookieFunctions() {
	// Create cookie
	function setCookie(cname, cvalue, exdays) {
		const d = new Date()
		d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000)
		let expires = 'expires=' + d.toUTCString()
		document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/'
	}

	// Delete cookie
	function deleteCookie(cname) {
		let expires = 'expires=Thu, 01-Jan-1970 00:00:01 GMT'
		document.cookie = cname + '=;' + expires + ';path=/'
	}

	// Read cookie
	function getCookie(cname) {
		let name = cname + '='
		let decodedCookie = decodeURIComponent(document.cookie)
		let ca = decodedCookie.split(';')
		for (let i = 0; i < ca.length; i++) {
			let c = ca[i]
			while (c.charAt(0) == ' ') {
				c = c.substring(1)
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length)
			}
		}
		return ''
	}

	// Set cookie consent
	let cookiesAcceptBtn = document.querySelector('.cookies__accept')
	let cookie_consent = getCookie('user_cookie_consent')

	if (
		cookiesAcceptBtn != 'undefined' &&
		cookiesAcceptBtn != null &&
		cookiesAcceptBtn != ''
	) {
		function acceptCookieConsent() {
			deleteCookie('user_cookie_consent')
			setCookie('user_cookie_consent', 1, 30)
			document.getElementById('cookiesNotice').style.display = 'none'
		}

		cookiesAcceptBtn.addEventListener('click', () => {
			acceptCookieConsent()
		})

		if (cookie_consent !== '') {
			document.getElementById('cookiesNotice').style.display = 'none'
		} else {
			document.getElementById('cookiesNotice').style.display = 'block'
		}
	}
}

function helperFunctions() {
	function classReg(className) {
		return new RegExp('(^|\\s+)' + className + '(\\s+|$)')
	}

	function hasClass(elem, c) {
		return elem.classList.contains(c)
	}

	function addClass(elem, c) {
		elem.classList.add(c)
	}

	function removeClass(elem, c) {
		elem.classList.remove(c)
	}

	function toggleClass(elem, c) {
		let fn = hasClass(elem, c) ? removeClass : addClass
		fn(elem, c)
	}

	;(function (window) {
		'use strict'

		let classie = {
			hasClass: hasClass,
			addClass: addClass,
			removeClass: removeClass,
			toggleClass: toggleClass,
			has: hasClass,
			add: addClass,
			remove: removeClass,
			toggle: toggleClass,
		}

		if (typeof define === 'function' && define.amd) {
			define(classie)
		} else {
			window.classie = classie
		}
	})(window)
}

async function lazyTemplateParts() {
	const templatePartContainers = document.querySelectorAll(
		'.async-template-part'
	)

	if (templatePartContainers.length === 0) {
		return
	}

	const wpAjaxUrl = wp_ajax.ajax_url

	async function loadTemplatePart(container) {
		const templatePartName = container.getAttribute('data-template-part')
		const pageId = container.dataset.pageId

		if (!templatePartName) {
			return
		}

		const data = new FormData()
		data.append('action', 'lp_template_markup')
		data.append('template_part_name', templatePartName)
		data.append('template_page_id', pageId)

		try {
			const response = await fetch(wpAjaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: data,
			})

			if (!response.ok) {
				throw new Error('Error loading template part: ' + response.status)
			}

			const html = await response.text()

			if (container.innerHTML === '') {
				container.innerHTML = html
				container.classList.add('loaded')

				if (
					templatePartName === 'sections/cta' &&
					container.classList.contains('loaded')
				) {
					// Do something specific for 'sections/cta'
				}

				const allTemplatePartsLoaded = [...templatePartContainers].every(
					container => container.innerHTML !== ''
				)

				if (allTemplatePartsLoaded) {
					// Reinitialize functions
				}
			}
		} catch (error) {
			console.log(error)
		}
	}

	async function lazyLoadParts() {
		const templatePartArray = Array.from(templatePartContainers)
		const promises = templatePartArray.map(loadTemplatePart)
		await Promise.all(promises)
	}

	await lazyLoadParts()
}

function asidePanels() {
	const panelFill = document.querySelector('.panel-fill')

	function removeModal(panel, el) {
		classie.remove(panel, 'panel-show')
		document.body.classList.remove('panel-showed')
		el.classList.remove('active')
		document.body.removeAttribute('style')
	}

	function removeModalHandler(panel, el) {
		removeModal(panel, el)
		el.classList.remove('active')
	}

	document.addEventListener('click', function (event) {
		const target = event.target
		const panelTrigger = target.closest('.panel-trigger')

		if (panelTrigger) {
			const panel = document.querySelector(
				'#' + panelTrigger.getAttribute('data-panel')
			)
			const close = panel.querySelector('.panel-close')

			compensateScrollbar()

			if (document.body.classList.contains('panel-showed')) {
				hideOpenedPanels()
			} else {
				document.body.classList.add('panel-showed')
				panelFill.style.willChange = 'transform'
				panel.addEventListener('transitionend', function () {
					panelFill.style.willChange = 'auto'
				})
			}

			panelTrigger.classList.add('active')
			classie.add(panel, 'panel-show')

			overlay.removeEventListener(
				'click',
				removeModalHandler.bind(null, panel, panelTrigger)
			)
			overlay.addEventListener(
				'click',
				removeModalHandler.bind(null, panel, panelTrigger)
			)

			if (close && close != null) {
				close.addEventListener('click', function (ev) {
					ev.stopPropagation()
					removeModalHandler(panel, panelTrigger)
				})
			}
		}
	})
}

function faqAccordeon() {
	const tabs = document.querySelectorAll('.accordeon-item')
	const descriptions = document.querySelectorAll('.accordeon-item__description')

	tabs.forEach(function (element) {
		element.addEventListener('click', toggleItem)
	})

	descriptions.forEach(function (element) {
		initHeight(element)
	})

	// Check if any tab has the 'active' class on page load
	tabs.forEach(function (tab) {
		if (tab.classList.contains('active')) {
			const descriptionContainer = tab.querySelector(
				'.accordeon-item__description'
			)
			const descriptionHeight = descriptionContainer.getAttribute('data-height')
			descriptionContainer.style.maxHeight = `${descriptionHeight}px`
		}
	})

	function toggleItem() {
		let siblings = getSiblings(this)
		let descriptionContainer = this.querySelector(
			'.accordeon-item__description'
		)
		let descriptionHeight = descriptionContainer.getAttribute('data-height')
		this.classList.toggle('active')

		closeDescriptions(descriptions)

		siblings.forEach(function (sibling) {
			sibling.classList.remove('active')
		})

		if (this.classList.contains('active')) {
			descriptionContainer.style.maxHeight = `${descriptionHeight}px`
		} else {
			descriptionContainer.style.maxHeight = 0
		}
	}

	function initHeight(item) {
		let height = item.offsetHeight
		item.setAttribute('data-height', height)
		item.style.maxHeight = 0
	}

	function closeDescriptions(descriptionList) {
		descriptionList.forEach(function (element) {
			element.style.maxHeight = 0
		})
	}

	function getSiblings(element) {
		let siblings = new Array()

		let sibling = element.parentNode.firstChild
		for (; sibling; sibling = sibling.nextSibling) {
			if (sibling.nodeType !== 1 || sibling === element) continue
			siblings.push(sibling)
		}
		return siblings
	}
}

function mobileMenu() {
	const myMenu = document.querySelector('.mega-menu__box')
	const oppMenu = document.querySelector('.mega-menu__btn')
	if (myMenu != 'undefined' && myMenu != null && myMenu != '') {
		myMenu.addEventListener('transitionend', OnTransitionEnd, false)
		oppMenu.addEventListener('click', toggleClassMenu, false)
		myMenu.addEventListener('click', toggleClassMenu, false)
	}

	function toggleClassMenu() {
		myMenu.classList.add('menu--animatable')
		if (!myMenu.classList.contains('menu--visible')) {
			myMenu.classList.add('menu--visible')
		} else {
			myMenu.classList.remove('menu--visible')
		}
	}

	function OnTransitionEnd() {
		myMenu.classList.remove('menu--animatable')
	}
}

function tabs() {
	const tabs = document.querySelectorAll('[data-tab-target]')
	const tabContents = document.querySelectorAll('[data-tab-content]')

	tabs.forEach(tab => {
		tab.addEventListener('click', () => {
			const target = document.querySelector(tab.dataset.tabTarget)
			tabContents.forEach(tabContent => {
				tabContent.classList.remove('active')
			})
			tabs.forEach(tab => {
				tab.classList.remove('active')
			})
			tab.classList.add('active')
			target.classList.add('active')
		})
	})
}

// scrollbar compensation
function getScrollbarWidth() {
	const scrollDiv = document.createElement('div')
	scrollDiv.style.width = '100px'
	scrollDiv.style.height = '100px'
	scrollDiv.style.overflow = 'scroll'
	scrollDiv.style.position = 'absolute'
	scrollDiv.style.top = '-9999px'
	document.body.appendChild(scrollDiv)
	const scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
	document.body.removeChild(scrollDiv)
	return scrollbarWidth
}

function compensateScrollbar() {
	const scrollbarWidth = getScrollbarWidth()
	document.body.style.marginRight = `${scrollbarWidth}px`
}

// process lazy images
async function processLazyImages() {
	await new Promise(resolve => setTimeout(resolve, 0))
	const lazyImages = document.querySelectorAll('img[loading="lazy"]')
	if (lazyImages && lazyImages.length > 0) {
		lazyImages.forEach(function (image) {
			image.loading = 'eager'
		})
	}
}

window.addEventListener('load', processLazyImages)

// pages prerender
function pagePrerender() {
	!(function (n, e) {
		'object' == typeof exports && 'undefined' != typeof module
			? (module.exports = e())
			: 'function' == typeof define && define.amd
			? define(e)
			: ((n || self).ProbaClick = e())
	})(this, function () {
		function n(n, e) {
			var t = void 0 === e ? {} : e,
				o = t.max,
				i = t.callback
			function u() {
				i(n),
					f++,
					(s = { time: 0, interactions: 0 }),
					(a = !0),
					null !== o && f >= o && r('remove')
			}
			function r(e) {
				for (var t in v) n[e + 'EventListener'](t, v[t])
			}
			var c = t.delay,
				l = t.count,
				a = !1,
				f = 0,
				d = 0,
				m = null,
				s = { time: 0, interactions: 0 },
				v = {
					mouseover: function () {
						return (
							(a = !1),
							(d = Date.now()),
							s.interactions++,
							(null !== l && s.interactions >= l && (u(), !0)) ||
								(null !== c &&
									(m = setTimeout(function () {
										u()
									}, c - s.time)))
						)
					}.bind(this),
					mouseleave: function () {
						return (
							clearTimeout(m), a || void (s.time = s.time + (Date.now() - d))
						)
					}.bind(this),
				}
			return (
				r('add'),
				{
					remove: function () {
						return r('remove')
					},
				}
			)
		}
		return function (e, t) {
			var o,
				i = void 0 === t ? {} : t,
				u = i.max,
				r = void 0 === u ? null : u,
				c = i.delay,
				l = void 0 === c ? 500 : c,
				a = i.count,
				f = void 0 === a ? null : a,
				d = i.callback,
				m = void 0 === d ? function () {} : d,
				s = ((o = e),
				'string' == typeof o
					? (o = document.querySelectorAll(o))
					: o instanceof NodeList || (o = [o]),
				[].slice.call(o)).map(function (e) {
					return new n(e, { delay: l, callback: m, max: r, count: f })
				})
			return {
				remove: function () {
					s.forEach(function (n) {
						return n.remove()
					})
				},
			}
		}
	})

	let hasBeen = {
		prefetch: [],
		prerender: [],
	}
	console.log(hasBeen)

	const makeHint = (href, type) => {
		let link = document.createElement('link')
		link.setAttribute('rel', type)
		link.setAttribute('href', href)
		document.head.appendChild(link)
		return href
	}

	const isExternalLink = href => {
		return !href.match(/^\//) && !href.includes(window.location.host)
	}

	const maybeMakeHint = ({ link, type, max = null } = {}) => {
		let href = link.getAttribute('href')

		if (isExternalLink(href)) return
		if (hasBeen[type].includes(href)) return
		if (max !== null && hasBeen[type].length >= max) return

		hasBeen[type].push(makeHint(href, type))
	}

	ProbaClick('a', {
		callback: function (link) {
			maybeMakeHint({
				link,
				type: 'prefetch',
			})
		},
	})

	ProbaClick('a', {
		delay: 1000,
		count: 3,
		callback: function (link) {
			maybeMakeHint({
				link,
				type: 'prerender',
				max: 1,
			})
		},
	})
}

/**
 *--------------------------------------------------------------------------------------------------------------
 * contact forms
 *--------------------------------------------------------------------------------------------------------------
 */

const LPform = (function (window) {
	let removeErrorBound = {
		length: 0,
	}

	function getPassPhrase() {
		let token = '9320087105434084715'
		token = token.split('')
		token = token.reverse().join('')
		return token
	}

	function onFieldFocus(self) {
		self.formFocused = true
	}

	function removeError(self, targetElement) {
		var els = document.querySelectorAll('[name=' + targetElement.name + ']'),
			i

		for (i = 0; i < els.length; i++) {
			els[i].classList.remove('error')
			els[i].removeEventListener(
				'focus',
				removeErrorBound[targetElement.name],
				false
			)
		}

		delete removeErrorBound[targetElement.name]
		removeErrorBound.length--
		if (removeErrorBound.length <= 0) {
			removeErrorBound.length = 0
			self.setSubmitState('initial')
		}
	}

	// Scrolls window to make visible target element on the screen
	function scrollToShowElement(element) {
		let bounding = element.getBoundingClientRect(),
			fromTop = Math.round(bounding.top) - 5,
			viewportHeight = window.innerHeight

		if (fromTop <= 0) {
			window.scrollBy(0, fromTop)
			return
		}

		if (fromTop >= viewportHeight) {
			window.scrollBy(0, fromTop - viewportHeight + 30)
		}
	}

	function LPform(formID) {
		let self = this,
			form = document.getElementById(formID)

		if (!form) {
			console.warn("Couldn't bind to form element")
			return null
		}

		self.dict = {
			sendSuccess: 'Успех', //wp_ajax.success_msg,
			sendError: 'Mail server has experienced an error. Please try again.',
			timeoutError: 'Timeout Error',
			markedAsSpamError: 'SPAM',
		}

		self.responseTimeout = 5000
		self.url = form.action || location.href
		self.form = form
		self.processing = false

		// Binding submit button
		self.submitButton = form.querySelector('[type="submit"]')
		if (!self.submitButton) {
			console.warn("Couldn't bind to submit button")
			return null
		}

		// Binding to notification box
		self.notificationBox = form.querySelector('.notification-box')
		if (!self.notificationBox) {
			console.warn("Couldn't bind to submit button - " + form)
			return null
		}

		self.notificationBox.addEventListener(
			'click',
			function () {
				this.classList.remove('show-error')
				this.classList.remove('show-success')
			},
			false
		)

		// BOT prevent
		self.formFocused = false
		self.focusBound = null

		// Init
		self.init()
		return self
	}

	LPform.prototype.logError = function (msg) {
		this.notify(msg, 'error')
	}

	LPform.prototype.notify = function (message, type) {
		let notificationBox = this.notificationBox

		if (!notificationBox) {
			console.warn('Notification box not found')
			return
		}
		notificationBox.innerHTML = message
		notificationBox.classList.add('show-' + (type || 'error'))
		scrollToShowElement(notificationBox)

		setTimeout(function () {
			notificationBox.classList.remove('show-error')
			notificationBox.classList.remove('show-success')
		}, 3000)
	}

	// Sets state to submit
	LPform.prototype.setSubmitState = function (state) {
		let self = this,
			form = self.form,
			submit = self.submitButton,
			className = submit.className.replace(/state-[a-z]+/gi, ''),
			FormClassName = form.className.replace(/state-[a-z]+/gi, '')

		self.processing = state === 'processing'
		submit.className = className + ' state-' + state
		form.className = FormClassName + ' state-' + state
	}

	LPform.prototype.validateForm = function () {
		let self = this,
			form = self.form,
			els = form.elements,
			secretField,
			honeyField,
			i,
			el,
			error = false,
			formError = false,
			emailPattern =
				/^([\w\-]+(?:\.[\w\-]+)*)@((?:[\w\-]+\.)*\w[\w\-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i

		// Remove errors
		for (i = els.length - 1; i >= 0; --i) {
			el = els[i]
			if (removeErrorBound[el.name]) {
				removeError(self, el)
			}
		}

		// Add new errors
		for (i = els.length - 1; i >= 0; --i) {
			el = els[i]
			error = false

			if (el.value === '' && el.required) {
				error = true
			} else {
				if (el.type === 'checkbox' && el.required && !el.checked) {
					error = true
				}
				if (el.type === 'tel' && el.required && el.value.length === 13) {
					error = true
				}
				if (
					el.type === 'email' &&
					el.value !== '' &&
					!emailPattern.test(el.value)
				) {
					error = true
				}

				if (el.type === 'radio' && el.required) {
					if (!document.querySelector('[name=' + el.name + ']:checked')) {
						error = true
					}
				}
			}

			if (error) {
				el.classList.add('error')
				if (!removeErrorBound[el.name]) {
					removeErrorBound[el.name] = removeError.bind(null, self, el)
					removeErrorBound.length++
				}
				el.addEventListener('focus', removeErrorBound[el.name], false)
				formError = true
			} else {
				el.classList.remove('error')
				el.classList.add('valid')
			}

			if (formError) {
				self.setSubmitState('error')
			}
		}

		if (!formError) {
			if (self.formFocused !== true) {
				self.logError(self.dict.markedAsSpamError)
				return false
			}

			secretField = form.querySelector('[name="contact_secret"]')
			honeyField = form.querySelector('[name="honey_field"]')
			if (!secretField) {
				secretField = document.createElement('input')
				secretField.type = 'hidden'
				secretField.name = 'contact_secret'
				form.appendChild(secretField)
			}
			if (!honeyField) {
				honeyField = document.createElement('input')
				honeyField.type = 'hidden'
				honeyField.name = 'honey_field'
				form.appendChild(honeyField)
			}
			secretField.value = getPassPhrase()
		}

		setTimeout(function () {
			window.scrollBy(0, -1)
		}, 1)
		return !formError
	}

	LPform.prototype.successForm = function (msg) {
		let self = this,
			form = self.form
		form.classList.add('sent')
		self.setSubmitState('success')
		self.notify(msg, 'success')
	}

	LPform.prototype.resetForm = function () {
		let self = this,
			formElements = self.form,
			submitButton = self.submitButton,
			tmpElement,
			i

		formElements.classList.remove('sent')
		formElements.classList.remove('completed')

		for (i = formElements.length - 1; i >= 0; --i) {
			tmpElement = formElements[i]

			if (tmpElement !== submitButton) {
				tmpElement.classList.remove('success')
				tmpElement.value = ''
			}
		}
		self.setSubmitState('initial')
	}

	LPform.prototype.init = function () {
		let self = this,
			form = self.form,
			submit = self.submitButton,
			requiredElements = form.elements,
			tmpElement,
			i

		form.addEventListener('submit', self.submitForm.bind(self), true)

		self.focusBound = onFieldFocus.bind(null, self)

		self.formFocused = false
		for (i = requiredElements.length - 1; i >= 0; --i) {
			tmpElement = requiredElements[i]
			if (tmpElement.type !== 'submit') {
				tmpElement.addEventListener('focus', self.focusBound, false)
			}
		}
	}

	LPform.prototype.send = function (formData) {
		let self = this,
			dict = self.dict
		self.setSubmitState('initial')

		formData.append('action', 'lp_send_message')
		formData.append('nonce', wp_ajax.nonce)

		let options = {
			method: 'POST',
			mode: 'no-cors',
			cache: 'no-cache',
			credentials: 'same-origin',
			headers: {
				'Content-Type': 'application/json',
			},
			body: formData,
		}

		fetch(wp_ajax.ajax_url, options).then(async response => {
			try {
				const data = await response.json()
				console.log(data)
				if (data.code === 200) {
					self.successForm(data.message)
					setTimeout(self.resetForm.bind(self), 3000)
				} else {
					self.logError(data.message)
				}
			} catch (error) {
				console.error('Error:', error)
				self.logError('Error')
			}
		})
	}

	LPform.prototype.submitForm = function (event) {
		let self = this,
			formData = ''

		if (event) {
			event.preventDefault()
			event.stopPropagation()
		}

		if (self.validateForm()) {
			self.setSubmitState('processing')
			formData = new FormData(self.form)
			self.send(formData)
		}
	}

	return LPform
})(window)

function phoneMask() {
	window.addEventListener('DOMContentLoaded', function () {
		let inputs = document.querySelectorAll('input[type="tel"]')
		Array.prototype.forEach.call(inputs, function (input) {
			new InputMask({
				selector: input,
				layout: input.dataset.mask,
			})
		})
	})

	function InputMask(options) {
		this.el = this.getElement(options.selector)
		if (!this.el) return console.log('Something is wrong with the selector')
		this.layout = options.layout || '+38 (___) ___ __ __'
		this.maskreg = this.getRegexp()
		this.setListeners()
	}

	InputMask.prototype.getRegexp = function () {
		var str = this.layout.replace(/_/g, '\\d')
		str = str.replace(/\(/g, '\\(')
		str = str.replace(/\)/g, '\\)')
		str = str.replace(/\+/g, '\\+')
		str = str.replace(/\s/g, '\\s')

		return str
	}

	InputMask.prototype.mask = function (e) {
		var _this = e.target,
			matrix = this.layout,
			i = 0,
			def = matrix.replace(/\D/g, ''),
			val = _this.value.replace(/\D/g, '')

		if (def.length >= val.length) val = def

		_this.value = matrix.replace(/./g, function (a) {
			return /[_\d]/.test(a) && i < val.length
				? val.charAt(i++)
				: i >= val.length
				? ''
				: a
		})

		// Prevent cursor from being placed before the prefix "+38"
		const prefixLength = matrix.indexOf('_')
		if (_this.selectionStart < prefixLength) {
			this.setCursorPosition(prefixLength, _this)
		}

		if (e.type == 'blur') {
			var regexp = new RegExp(this.maskreg)
			if (!regexp.test(_this.value)) _this.value = ''
		} else {
			this.setCursorPosition(_this.value.length, _this)
		}
	}

	InputMask.prototype.setCursorPosition = function (pos, elem) {
		elem.focus()
		if (elem.setSelectionRange) elem.setSelectionRange(pos, pos)
		else if (elem.createTextRange) {
			var range = elem.createTextRange()
			range.collapse(true)
			range.moveEnd('character', pos)
			range.moveStart('character', pos)
			range.select()
		}
	}

	InputMask.prototype.setListeners = function () {
		this.el.addEventListener('input', this.mask.bind(this), false)
		this.el.addEventListener('focus', this.mask.bind(this), false)
		this.el.addEventListener(
			'click',
			this.preventCursorBeforePrefix.bind(this),
			false
		)
	}

	InputMask.prototype.preventCursorBeforePrefix = function (e) {
		const prefixLength = this.layout.indexOf('_')
		if (this.el.selectionStart < prefixLength) {
			this.setCursorPosition(prefixLength, this.el)
		}
	}

	InputMask.prototype.getElement = function (selector) {
		if (selector === undefined) return false
		if (this.isElement(selector)) return selector
		if (typeof selector == 'string') {
			var el = document.querySelector(selector)
			if (this.isElement(el)) return el
		}
		return false
	}

	InputMask.prototype.isElement = function (element) {
		return element instanceof Element || element instanceof HTMLDocument
	}
}
