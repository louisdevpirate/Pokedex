// fetch('/pokedex-api/')
//     .then(response => response.json())
//     .then(data => {
//         const pokemonListContainer = document.getElementById('pokemon-list-container');
//         data.pokemons.forEach(pokemon => {
//             const div = document.createElement('div');
//             div.innerHTML = `<a href="{{ 'app_pokedex' }}">${pokemon.name} #${pokemon.id}</a>`;
//             pokemonListContainer.appendChild(div);
//         });
//     });

let pokedexNav = document.querySelector('.pokedex-nav');

let buttonToDisplay = pokedexNav.querySelector('[data-current]');


pokedexNav.scrollTop = buttonToDisplay.offsetTop - 900;


//Animation du bouton B
const button = document.querySelector(".gb-button-b");

button.addEventListener("click", animate);

const pokeGif = document.querySelector(".poke-gif");


function animate() {
    pokeGif.classList.add("jump-animation", "scale");
}


pokeGif.addEventListener("animationend", removeClass);
function removeClass() {
    pokeGif.classList.remove("jump-animation", "scale");
}