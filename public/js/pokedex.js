
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

console.log('hello');

let pokedexNav = document.querySelector('.pokedex-nav');

let buttonToDisplay = pokedexNav.querySelector('[data-current]');

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
const buttonA = document.querySelector(".gb-button-a");
const pokeGif = document.querySelector(".poke-gif");

const originalSrc = pokeGif.src;
const shinySrc = pokeShineDir;

buttonA.addEventListener("click", function () {
    if (pokeGif.src === originalSrc) {
        pokeGif.src = shinySrc;
    } else {
        pokeGif.src = originalSrc;
    }
});

// Animation du bouton Shiny sur mobile
const shinyButton = document.querySelector(".shiny-button-mobile");
const pokeShinyGif = document.querySelector(".poke-gif");

const firstSrc = pokeGif.src;
const secondSrc = pokeShineDir;

shinyButton.addEventListener("click", function () {
    if (pokeShinyGif.src === firstSrc) {
        pokeShinyGif.src = secondSrc;
    } else {
        pokeShinyGif.src = firstSrc;
    }
});






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