document.addEventListener('DOMContentLoaded', function () {
	const btnAdd = document.querySelector('.btn-add-quantity');
	const btnRemove = document.querySelector('.btn-remove-quantity');
	const input = document.querySelector('.input-text.qty.text');

	if (!btnAdd) return;

	btnAdd.addEventListener('click', (e) => {
		e.preventDefault();
		const currentValue = +input.value;
		const newValue = currentValue + 1;
		input.value = newValue;
	});

	btnRemove.addEventListener('click', (e) => {
		e.preventDefault();
		const currentValue = +input.value;
		if (currentValue <= 0) return;
		const newValue = currentValue - 1;
		input.value = newValue;
	});
});
