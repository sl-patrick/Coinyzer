let table = document.querySelector('.watchlist');

table.addEventListener('click', function(e) {
    e.preventDefault();
    let url = this.href;
    const xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
			console.log(this.response);
		}
	};
	xhr.open("POST", url, true);
	xhr.responseType = "json";
	xhr.send();
})