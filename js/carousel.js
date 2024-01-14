document.addEventListener('DOMContentLoaded', () => {
    const thumbnailWrapper = document.querySelectorAll('.thumbnail-list-wrapper');
    if (thumbnailWrapper.length === 0) return;

    thumbnailWrapper[1].addEventListener('click', function (e) {
        if (this.contains(event.target)) {
            let id;
            let target = event.target;

            if (target.nodeName === 'BUTTON') return;

            while (target.nodeName !== 'LI') {
                target = target.parentNode;
            }
            id = target.dataset.id;

            target.parentNode.childNodes.forEach((node) => (node.style.borderColor = '#eee'));
            target.style.borderColor = '#ADD136';

            thumbnailWrapper[0].childNodes.forEach((node) => {
                if (id === node.dataset.id) {
                    var positionTop = node.offsetTop;
                    var positionLeft = node.offsetLeft;

                    thumbnailWrapper[0].scrollTo({
                        top: positionTop,
                        left: positionLeft,
                        behavior: 'smooth'
                    });
                }
            });
        }
    });

    const varitaionForm = document.querySelector('.variations_form.cart');

    const observer = new MutationObserver((mutationsList, observer) => {
        for (let mutation of mutationsList) {
            if (mutation.attributeName === 'current-image') {
                const id = mutation.target.getAttribute('current-image');
                if (id) {
                    thumbnailWrapper[0].childNodes.forEach((node) => {
                        if (id === node.dataset.id) {
                            var positionTop = node.offsetTop;
                            var positionLeft = node.offsetLeft;

                            thumbnailWrapper[0].scrollTo({
                                top: positionTop,
                                left: positionLeft,
                                behavior: 'smooth'
                            });
                        }
                    });

                    thumbnailWrapper[1].childNodes.forEach((node) => {
                        if (id === node.dataset.id) {
                            node.style.borderColor = '#ADD136';
                        } else {
                            node.style.borderColor = '#eee';
                        }
                    });
                } else {
                }
            }
        }
    });

    if (varitaionForm !== null) {
        observer.observe(varitaionForm, { attributes: true });
    }

    const singleProductRightArrow = document.querySelector('.ya-single-product__right-arrow');
    const singleProductLeftArrow = document.querySelector('.ya-single-product__left-arrow');

    const scrollingContainer = document.querySelector('.thumbnail-list-wrapper');
    const thumnailsCount = scrollingContainer.childNodes;
    let currentCount = 0;

    const activeCurrentAndDeactiveOthers = (current, otherList) => {
        thumbnailWrapper[1].childNodes.forEach((node) => {
            if (current.dataset.id === node.dataset.id) {
                node.style.borderColor = '#ADD136';
            } else {
                node.style.borderColor = '#eee';
            }
        });
    };

    singleProductRightArrow.addEventListener('click', () => {
        if (currentCount === 0) {
            currentCount = thumnailsCount.length - 1;
        } else {
            currentCount -= 1;
        }

        var positionTop = thumnailsCount[currentCount].offsetTop;
        var positionLeft = thumnailsCount[currentCount].offsetLeft;

        thumbnailWrapper[0].scrollTo({
            top: positionTop,
            left: positionLeft,
            behavior: 'smooth'
        });

        activeCurrentAndDeactiveOthers(thumnailsCount[currentCount], thumnailsCount);
    });

    singleProductLeftArrow.addEventListener('click', () => {
        currentCount += 1;
        if (thumnailsCount.length === currentCount) {
            currentCount = 0;
        }
        var positionTop = thumnailsCount[currentCount].offsetTop;
        var positionLeft = thumnailsCount[currentCount].offsetLeft;

        thumbnailWrapper[0].scrollTo({
            top: positionTop,
            left: positionLeft,
            behavior: 'smooth'
        });
        activeCurrentAndDeactiveOthers(thumnailsCount[currentCount], thumnailsCount);
    });
});
