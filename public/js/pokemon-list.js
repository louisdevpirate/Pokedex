
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


pokedexNav.scrollTop = buttonToDisplay.offsetTop - 555;