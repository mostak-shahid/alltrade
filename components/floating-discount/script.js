document.addEventListener('DOMContentLoaded', () => {
    const floatingSale = document.querySelector('.ya-floating-discount');
    const floatingSaleExitBtn = document.querySelector('.ya-floating-discount-exit');

    if (!floatingSale) return;

    floatingSaleExitBtn.addEventListener('click', () => {
        floatingSale.classList.add('ya-floating-sale__removed');
    });
});
