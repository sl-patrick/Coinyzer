// const apiKey = '252f639021da21a5be8d9e776b68725b83c70f76d7b3b037cbd23239f0aa0647';
//Je crée un tableau vide pour y stocker tout les noms des cryptomonnaies.
let data= [];
let names = document.querySelectorAll('.name');
// console.log(names);
names.forEach(element => {
    data.push(element.innerHTML.toUpperCase())
});
//Je transforme le tableau en string
data = data.join(",");
// console.log(data);

function async() {
    fetch(`https://min-api.cryptocompare.com/data/pricemultifull?fsyms=${data}&tsyms=EUR&api_key=${apiKey}`)
    .then((response) => response.json())
    .then(function(data) {
        // return data.RAW;
        let array = data.RAW;
    })
    .catch(function(error) {
        console.log(error);
    })

}

async();

