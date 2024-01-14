const is10MineutsPassed = (givenTimestamp) => {
    var currentTimestamp = Date.now();
    var tenMinutesInMilliseconds = 10 * 60 * 1000;
    var timeDifference = currentTimestamp - givenTimestamp;

    return timeDifference <= tenMinutesInMilliseconds;
};

document.addEventListener('DOMContentLoaded', () => {
    const floatingSale = document.querySelector('.ya-floating-sale');
    const floatingSaleExitBtn = document.querySelector('.ya-floating-sale-exit');

    const isClosed = sessionStorage.getItem('isModalClosed');
    const closedPeriod = sessionStorage.getItem('ClosingPeriod');

    const isPeroidPassed = is10MineutsPassed(closedPeriod);

    if (isClosed === 'true' && isPeroidPassed) {
        floatingSale.remove();
    }

    if (!floatingSale) return;

    floatingSaleExitBtn.addEventListener('click', () => {
        floatingSale.classList.add('ya-floating-sale__removed');
        sessionStorage.setItem('isModalClosed', true);
        sessionStorage.setItem('ClosingPeriod', Date.now());
    });
});
