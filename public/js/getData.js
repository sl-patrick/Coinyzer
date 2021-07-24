let refreshButton = document.querySelector('.refresh');

refreshButton.addEventListener('click', function(e) {
    e.preventDefault();
    const xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
			console.log(this.response);
		}
	};
	xhr.open("POST", '/cryptocurrencies/refresh', true);
	xhr.responseType = "json";
	xhr.send();
});




