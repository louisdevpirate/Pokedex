let captureInProcess = false;
let elem = document.querySelector(".pokeball-animate");
function backToPlace() {
    elem.style.bottom = '0px';
    elem.style.rotate = '0deg';
}

//Fonction de déplacement de la pokéball

//Bouton de capture

document.querySelector('.capture-poke-button').addEventListener("click", async function () {

    if (!captureInProcess) {

        captureInProcess = true;

        //Si il y'a deja un pokemon, on l'enleve
        let currentPoke = document.querySelector('.displayed-pokemon');
        if (currentPoke) {
            currentPoke.remove();
        }
        let currentShiny = document.querySelector('.shining-effect');
        if (currentShiny) {
            currentShiny.remove();
        }

        // <img src="{{ asset('images/gifs/charizard.gif') }}" alt="">
        let pokemonImage = document.createElement('img');
        let pokemonShining = document.createElement('img');
        pokemonImage.classList.add('displayed-pokemon');
        pokemonShining.classList.add('shining-effect');
        pokemonImage.alt = '';
        pokemonShining.alt = '';
        let pokemonGif;
        let pokemonShine;

        let animatePromise = new Promise((resolve, reject) => {

            (() => {
                let pos = 0;
                let angle = 0;
                let id = setInterval(frame, 5);
                clearInterval(id);
                id = setInterval(frame, 10);

                function frame() {

                    if (pos === 250) {

                        clearInterval(id);

                        resolve();

                    } else {

                        pos += 5;
                        angle += 22;
                        elem.style.bottom = pos + 'px';
                        elem.style.rotate = angle + 'deg';
                    }
                }
            })();

        });

        let getPokemonPromise = new Promise((resolve, reject) => {

            //Affichage du gif du pokémon
            fetch(capturedPageApi)
                .then((response) => response.json())
                .then((data) => {
                    //
                    // if(data.captured_pokemon.shiny){
                    //
                    //     pokemonGif = pokemonsGifDir + '/shiny-' + data.captured_pokemon.gif;
                    //
                    // }else{
                    //
                    //     pokemonGif = pokemonsGifDir + '/' + data.captured_pokemon.gif;
                    //
                    // }

                    pokemonGif = pokemonsGifDir + '/' + ((data.captured_pokemon.shiny) ? 'shiny-' : '') + data.captured_pokemon.gif;



                    console.log(data.captured_pokemon.rarity + '(' + data.captured_pokemon.rarityRandom + '%)');


                    if(data.captured_pokemon.shiny === true) {
                        pokemonShine = pokemonsShineDir + '/shiny-sparkle.gif';
                    }else if (data.captured_pokemon.rarity === 'TR'){
                        pokemonShine = pokemonsShineDir + '/sparkle.gif';
                    }else if(data.captured_pokemon.rarity === 'EX'){
                        pokemonShine = pokemonsShineDir + '/orange-sparkle.gif';
                    }else if(data.captured_pokemon.rarity === 'SR'){
                        pokemonShine = pokemonsShineDir + '/red-sparkle.gif';
                    }else{
                        pokemonShine = null;
                    }



                    resolve();

                    // alert( 'Vous avez capturé un ' + data.captured_pokemon.name + '!' );

                })
                ;

        });

        await animatePromise;
        await getPokemonPromise;

        pokemonImage.src = pokemonGif;
        pokemonShining.src = pokemonShine;
        document.querySelector('.view-pokemon').append(pokemonShining);
        document.querySelector('.view-pokemon').append(pokemonImage);



        backToPlace();

        setTimeout(() => {
            captureInProcess = false;
        }, 1000);

    }


});