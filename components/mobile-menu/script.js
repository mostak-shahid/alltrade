document.addEventListener('DOMContentLoaded', () => {
	let childContainerWithChildrens;
	const firstParentElementLink = document.querySelectorAll(
		'.mobile__ya__link-first_parent'
	);

	const menuItemWithoutChildren = document.querySelectorAll(
		'.mobile_ya__no-children'
	);

	const removeAllParentsExceptThis = (current) => {
		firstParentElementLink.forEach((elementItem) => {
			if (elementItem !== current) {
				elementItem.parentNode.classList.toggle('mobile-menu__remove');
			}
		});

		menuItemWithoutChildren.forEach((elementItem) => {
			elementItem.classList.toggle('mobile-menu__remove');
		});
	};

	firstParentElementLink.forEach((elementItem) => {
		elementItem.addEventListener('click', function () {
			childContainerWithChildrens = this.parentNode;
			const openArrowElement = this.children[1];

			removeAllParentsExceptThis(this);
			childContainerWithChildrens.classList.toggle(
				'mobile-menu__open-child'
			);
			childContainerWithChildrens.classList.toggle(
				'mobile-menu__closed-after-open'
			);

			document
				.querySelector('.mobile-menu__header')
				.classList.toggle('mobile-menu__remove');

			openArrowElement.classList.toggle('close');
			this.classList.toggle('mobile__menu-link-open');
		});
	});

	// CHILDREN MENU

	const secondParentMenu = document.querySelectorAll(
		'.mobile__ya__link-child-with-childs'
	);

	const closeAllChildParents = (current) => {
		secondParentMenu.forEach((elementItem) => {
			if (elementItem !== current) {
				elementItem.parentNode.children[1].classList.remove(
					'mobile-menu__open-child'
				);
			}
		});
	};

	secondParentMenu.forEach((elementItem) => {
		elementItem.addEventListener('click', function () {
			childContainerWithChildrens = this.parentNode.children[1];

			childContainerWithChildrens.classList.toggle(
				'mobile-menu__open-child'
			);
			closeAllChildParents(this);
		});
	});

	// TOGGLE MENU HAMBURGER

	const hamburgerMenu = document.querySelector('.menu-icon__toggle');
	const mobileMenuContainer = document.querySelector(
		'.mobile-menu__container'
	);

	hamburgerMenu.addEventListener('click', () => {
		hamburgerMenu.classList.toggle('mobile-menu__open-hamburger');
		mobileMenuContainer.classList.toggle('mobile-menu__open-container');
		document.body.classList.toggle('mobile__menu-avoid-scrolling');
	});
});
