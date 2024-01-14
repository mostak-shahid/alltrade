const menuItemElement = document.querySelectorAll('.menu-item-has-children');
document.addEventListener('DOMContentLoaded', () => {
    menuItemElement.forEach((menuItem) => {
        let isInArea;

        menuItem.addEventListener('mouseenter', (e) => {
            if (!e.target.children[1]) return;

            let subMenuItem = e.target.children[1];

            if (e.target.children[1].className === 'sub-menu-container' && e.target.parentNode.className !== 'sub-menu') {
                setTimeout(() => {
                    e.target.children[1].classList.add('sub-menu-active');
                }, 200);

                subMenuItem.addEventListener('mouseenter', () => {
                    isInArea = true;
                });
                subMenuItem.addEventListener('mouseleave', () => {
                    isInArea = false;
                });
            }
        });

        menuItem.addEventListener('mouseleave', (e) => {
            setTimeout(() => {
                if (isInArea) return;
                e.target.children[1].classList.remove('sub-menu-active');
            }, 200);
        });
    });
});
