let refreshButton = document.querySelector('.refresh');

refreshButton.addEventListener('click', function(e) {
    e.preventDefault();
    const xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
			let array = this.response;
			let placement = document.querySelector('.table');
			placement.innerHTML = array;
		}
	};
	xhr.open("GET", '/cryptocurrencies/refresh', true);
	xhr.responseType = "json";
	xhr.send();
});




