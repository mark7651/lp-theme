class LPForm {
	static #originalToken = '9320087105434084715'

	static #getPassPhrase() {
		return this.#originalToken.split('').reverse().join('')
	}

	constructor(formID) {
		this.form = document.getElementById(formID)
		if (!this.form) return

		this.submitButton = this.form.querySelector('[type="submit"]')
		if (!this.submitButton) throw new Error('Submit button not found')

		this.notificationBox = this.form.querySelector('.notification-box')
		if (!this.notificationBox) throw new Error('Notification box not found')

		this.config = {
			url: this.form.action || window.location.href,
			responseTimeout: 5000,
			messages: {
				success: 'Успех',
				serverError: 'Mail server error. Please try again.',
				timeoutError: 'Timeout Error',
				spamError: 'SPAM',
			},
		}

		this.state = {
			processing: false,
			formFocused: false,
			errors: new Map(),
		}

		this.emailPattern =
			/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
		this.initialize()
	}

	initialize() {
		this.form.addEventListener('submit', this.handleSubmit.bind(this))
		this.notificationBox.addEventListener('click', () =>
			this.hideNotification()
		)
		this.setupFieldListeners()
	}

	setupFieldListeners() {
		Array.from(this.form.elements)
			.filter(el => el.type !== 'submit')
			.forEach(el => {
				el.addEventListener('focus', () => {
					this.state.formFocused = true
					this.clearFieldError(el)
				})
				el.addEventListener('blur', () => {
					this.validateAndStyleField(el)
				})
			})
	}

	validateAndStyleField(field) {
		const valid = this.validateField(field)

		if (valid) {
			field.classList.remove('error')
			field.classList.add('valid')
			field.setAttribute('aria-invalid', 'false')
			this.state.errors.delete(field.name)
		} else {
			field.classList.add('error')
			field.classList.remove('valid')
			field.setAttribute('aria-invalid', 'true')
			this.state.errors.set(field.name, field)
		}
	}

	setState(state) {
		this.state.processing = state === 'processing'
		this.updateClass(this.form, state)
		this.updateButtonState(state)
	}

	updateClass(element, state) {
		element.className =
			element.className.replace(/state-[a-z]+/gi, '').trim() + ` state-${state}`
	}

	updateButtonState(state) {
		if (state === 'processing') {
			this.submitButton.classList.add('loading')
			this.submitButton.disabled = true
		} else {
			this.submitButton.classList.remove('loading')
			this.submitButton.disabled = false
		}
	}

	notify(message, type = 'error') {
		if (!this.notificationBox) return
		this.notificationBox.innerHTML = message
		this.notificationBox.classList.add(`show-${type}`)
		this.scrollToElement(this.notificationBox)

		setTimeout(() => this.hideNotification(), 3000)
	}

	hideNotification() {
		this.notificationBox.classList.remove('show-error', 'show-success')
	}

	scrollToElement(element) {
		const rect = element.getBoundingClientRect()
		const viewportHeight = window.innerHeight
		const topOffset = Math.round(rect.top) - 5

		if (topOffset < 0) {
			window.scrollBy(0, topOffset)
		} else if (topOffset >= viewportHeight) {
			window.scrollBy(0, topOffset - viewportHeight + 30)
		}
	}

	validateField(field) {
		if (!field.required && !field.value) return true

		switch (field.type) {
			case 'checkbox':
				return field.checked
			case 'tel': {
				const cleaned = field.value.replace(/[^\d+]/g, '')
				const digitsOnly = cleaned.replace(/\D/g, '')

				return digitsOnly.length >= 10 && digitsOnly.length <= 15
			}
			case 'email':
				return this.emailPattern.test(field.value)
			case 'radio': {
				const radios = this.form.querySelectorAll(`[name="${field.name}"]`)
				return Array.from(radios).some(r => r.checked)
			}
			default:
				return !!field.value.trim()
		}
	}

	validateForm() {
		this.state.errors.clear()
		let isValid = true

		Array.from(this.form.elements).forEach(field => {
			if (field.type === 'submit') return

			this.validateAndStyleField(field)
			if (field.classList.contains('error')) {
				isValid = false
			}
		})

		if (!isValid) {
			this.setState('error')
			return false
		}

		if (!this.state.formFocused) {
			this.notify(this.config.messages.spamError)
			return false
		}

		this.addHiddenFields()
		return true
	}

	clearFieldError(field) {
		field.classList.remove('error')
		this.state.errors.delete(field.name)
		if (this.state.errors.size === 0) this.setState('initial')
	}

	addHiddenFields() {
		;['contact_secret', 'honey_field'].forEach(name => {
			if (!this.form.querySelector(`[name="${name}"]`)) {
				const input = document.createElement('input')
				input.type = 'hidden'
				input.name = name
				if (name === 'contact_secret') input.value = LPForm.#getPassPhrase()
				this.form.appendChild(input)
			}
		})
	}

	async sendForm(formData) {
		this.setState('processing')
		formData.append('action', 'lp_send_message')
		formData.append('nonce', wp_ajax?.nonce || '')

		try {
			const response = await fetch(wp_ajax?.ajax_url || this.config.url, {
				method: 'POST',
				body: formData,
			})

			if (!response.ok) {
				throw new Error(`HTTP error! status: ${response.status}`)
			}

			const data = await response.json()
			console.log('Server response:', data)

			if (data.code === 200) {
				this.handleSuccess(data.message || this.config.messages.success)
			} else {
				this.notify(data.message || this.config.messages.serverError)
			}
		} catch (error) {
			console.error('Form submission error:', error)
			this.notify(`${this.config.messages.serverError}: ${error.message}`)
		} finally {
			if (this.state.processing) this.setState('initial')
		}
	}

	handleSuccess(message) {
		this.form.classList.add('sent')
		this.setState('success')
		this.notify(message, 'success')
		setTimeout(() => this.resetForm(), 3000)
	}

	resetForm() {
		this.form.classList.remove('sent', 'completed')
		Array.from(this.form.elements)
			.filter(el => el !== this.submitButton)
			.forEach(el => {
				el.classList.remove('success', 'valid')
				el.value = ''
			})
		this.setState('initial')
	}

	handleSubmit(event) {
		event.preventDefault()
		if (this.validateForm()) {
			this.sendForm(new FormData(this.form))
		}
	}
}

export { LPForm }
