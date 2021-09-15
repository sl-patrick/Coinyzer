let btn = document.querySelectorAll('.add-watchlist');

btn.forEach(element => {
    element.addEventListener('click', function(e) {
        e.preventDefault();
        let url = this.href;
        
        fetch(url, {
            method: "GET"
        })
        .then(function(response) {
            if (response.status !== 200) {
                return response.text().then(function(text) {

                    let value = JSON.parse(text);
                    console.log(value.message);
                });
                // alert('Connectez-vous');

            } else if (response.status === 200) {
                return response.text().then(function(text) {

                    let value = JSON.parse(text);
                    console.log(value.message);
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