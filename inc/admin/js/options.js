document.addEventListener('DOMContentLoaded', () => {
	// meta counter
	let titleArea = document.querySelector('.meta-title input')
	let descriptionArea = document.querySelector('.meta-description textarea')
	let titleCounter = document.getElementById('char_count_title')
	let descriptionCounter = document.getElementById('char_count_description')

	if (titleCounter != null) {
		countTitleCharacters()
		countDescriptionCharacters()

		function countTitleCharacters() {
			let counter = titleArea.value.length
			titleCounter.textContent = counter
			if (counter > 60) {
				titleCounter.classList.add('warning')
				titleArea.classList.add('warning')
			} else {
				titleCounter.classList.remove('warning')
				titleArea.classList.remove('warning')
			}
		}

		function countDescriptionCharacters() {
			let counter = descriptionArea.value.length
			descriptionCounter.textContent = counter
			if (counter > 160) {
				descriptionCounter.classList.add('warning')
				descriptionArea.classList.add('warning')
			} else {
				descriptionCounter.classList.remove('warning')
				descriptionArea.classList.remove('warning')
			}
		}

		titleArea.addEventListener('keyup', countTitleCharacters)
		descriptionArea.addEventListener('keyup', countDescriptionCharacters)
	}

	initMenuToggle()
})

//menu swap
function initMenuToggle() {
	const menuList = document.getElementById('menu-to-edit')
	if (!menuList) return

	addToggleButtons()
	observeMenuChanges(menuList)
}

function addToggleButtons() {
	const parentItems = document.querySelectorAll(
		'#menu-to-edit .menu-item-depth-0'
	)

	parentItems.forEach(function (parentItem) {
		if (parentItem.querySelector('.menu-item-toggle')) return
		if (hasChildren(parentItem)) {
			addToggleButton(parentItem)
		}
	})

	const depth1Items = document.querySelectorAll(
		'#menu-to-edit .menu-item-depth-1'
	)
	depth1Items.forEach(function (item) {
		if (item.querySelector('.menu-item-toggle')) return
		if (hasChildren(item)) {
			addToggleButton(item)
		}
	})
}

function hasChildren(parentItem) {
	const nextItem = parentItem.nextElementSibling
	if (!nextItem || !nextItem.classList.contains('menu-item')) return false

	const parentDepth = getDepth(parentItem)
	const nextDepth = getDepth(nextItem)

	return nextDepth > parentDepth
}

function getDepth(item) {
	const classes = item.className.match(/menu-item-depth-(\d+)/)
	return classes ? parseInt(classes[1]) : 0
}

function addToggleButton(parentItem) {
	const menuBar = parentItem.querySelector('.menu-item-bar')
	if (!menuBar) return

	const toggleButton = document.createElement('button')
	toggleButton.className = 'menu-item-toggle'
	toggleButton.type = 'button'
	toggleButton.setAttribute('aria-expanded', 'true')
	toggleButton.innerHTML =
		'<span class="screen-reader-text">Свернуть подменю</span><span class="toggle-indicator" aria-hidden="true"></span>'
	toggleButton.addEventListener('click', function (e) {
		e.preventDefault()
		e.stopPropagation()
		toggleChildren(parentItem, toggleButton)
	})

	menuBar.appendChild(toggleButton)
}

function toggleChildren(parentItem, toggleButton) {
	const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true'

	toggleButton.setAttribute('aria-expanded', !isExpanded)
	toggleButton.classList.toggle('collapsed')

	const parentDepth = getDepth(parentItem)
	let currentItem = parentItem.nextElementSibling

	while (currentItem && currentItem.classList.contains('menu-item')) {
		const currentDepth = getDepth(currentItem)

		if (currentDepth <= parentDepth) {
			break
		}

		currentItem.classList.toggle('hidden-child')
		currentItem = currentItem.nextElementSibling
	}
}

function observeMenuChanges(menuList) {
	const observer = new MutationObserver(function (mutations) {
		mutations.forEach(function (mutation) {
			if (mutation.addedNodes.length > 0) {
				setTimeout(addToggleButtons, 100)
			}
		})
	})

	observer.observe(menuList, {
		childList: true,
		subtree: true,
	})
}
