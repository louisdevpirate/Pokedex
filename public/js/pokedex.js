
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
const buttonB = document.querySelector(".gb-button-b");

buttonB.addEventListener("click", animate);

const pokeGifB = document.querySelector(".poke-gif");


function animate() {
    pokeGifB.classList.add("jump-animation", "scale");
}

pokeGifB.addEventListener("animationend", removeClass);
function removeClass() {
    pokeGifB.classList.remove("jump-animation", "scale");
}



// Animation du bouton A 
// const buttonA = document.querySelector(".gb-button-a");

// buttonA.addEventListener("click", animate);

// const pokeGifA = document.querySelector(".poke-gif");


// function animate() {
//     pokeGifA.classList.add("shaking-animation", "scale");
// }

// pokeGifA.addEventListener("animationend", removeClass);
// function removeClass() {
//     pokeGifA.classList.remove("shaking-animation", "scale");
// }




// Overlay du bouton start 
const startSelectButton = document.querySelector(".start");
const overlay = document.querySelector(".overlay");

startSelectButton.addEventListener("click", function () {
    if (overlay.style.display === "flex") {
        overlay.style.display = "none";
    } else {
        overlay.style.display = "flex";
        overlay.style.flexDirection = "column";
        overlay.style.justifyContent = "center";
        overlay.style.alignItems = "center";
    }
});