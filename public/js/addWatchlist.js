let buttonWatchlist = document.querySelectorAll('.add-watchlist');
let errorMessage = document.querySelector('.error-favorite');
let closeErrorMessage = document.querySelector('.error-close');

closeErrorMessage.addEventListener('click', function() {
    errorMessage.classList.add("hidden");   
});

buttonWatchlist.forEach(element => {
    element.addEventListener('click', function(e) {
        e.preventDefault();
        let url = this.href;
        
        fetch(url, {
            method: "GET"
        })
        .then(function(response) {
            if (response.status !== 200) {
    
                errorMessage.classList.remove("hidden");
            
            } else if (response.status === 200) {
                return response.text().then(function(text) {

                    let value = JSON.parse(text);
                    let state = element.children[0].classList;

                    if (value.message === 'add' || value.message === 'create and add' ) {
                        state.replace("far", "fas");
                    } else if(value.message === 'remove') {
                        state.replace("fas", "far");
                    }
                });
            }
        })
    })
}); 