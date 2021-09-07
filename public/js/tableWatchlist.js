let table = document.querySelector('.watchlist');

table.addEventListener('click', function(e) {
    e.preventDefault();
    let url = this.href;
	console.log(url);
    const xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
			// console.log(this.response);
			let favoris = this.response;
			let rank = document.querySelector('.rank');
			rank.classList.remove("active");
			table.classList.add("active");
			let placementTab = document.querySelector('.table');
			placementTab.innerHTML = favoris;

		}
	};
	xhr.open("GET", url, true);
	xhr.responseType = "json";
	xhr.send();
})