let captureInProcess = false;
let elem = document.querySelector(".pokeball-animate");
function backToPlace() {
    elem.style.bottom = '0px';
    elem.style.rotate = '0deg';
}


document.querySelector('.capture-poke-button').addEventListener("click", async function () {


    // document.querySelector('.multiple').after(launchCount);
    // launchCount.classList.add('count-pokeball');


    if (!captureInProcess) {

        captureInProcess = true;



        //Si il y'a deja un pokemon, on l'enleve
        let currentPoke = document.querySelector('.displayed-pokemon');
        if (currentPoke){
            currentPoke.remove();
        }
        let currentShiny = document.querySelector('.shining-effect');
        if (currentShiny){
            currentShiny.remove();
        }
        let currentInfo = document.querySelector('.pokemon-captured-infos');
        if (currentInfo){
            currentInfo.remove();
        }

        let launchs = document.querySelector('.launch-items').textContent;

        launchs = parseInt(launchs);

        if (launchs > 0 ) {

            document.querySelector('.launch-items').textContent = (launchs - 1);

        }


        let pokemonImage = document.createElement('img');
        let pokemonShining = document.createElement('img');
        let pokemonInfo = document.createElement('p');
        pokemonImage.classList.add('displayed-pokemon');
        pokemonShining.classList.add('shining-effect');
        pokemonInfo.classList.add('pokemon-captured-infos');
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

                        document.querySelector('.pokeball-animate').classList.add('pokeball-animated');

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

        let pokemonIsCaptured = false;

        let getPokemonPromise = new Promise((resolve, reject) => {

            //Affichage du gif du pokémon
            fetch(capturedPageApi)
                .then((response) => response.json())
                .then((data) => {

                    //Vérification du nombre de lancers

                    if(data.error != null){

                        pokemonInfo.innerHTML = data.error;

                    }else{

                        pokemonIsCaptured = true;


                        //Si le pokemon est shiny on change la route du gif
                        pokemonGif = pokemonsGifDir + '/' + ((data.captured_pokemon.shiny) ? 'shiny-' : '') + data.captured_pokemon.gif;


                        console.log(data.captured_pokemon.rarity + '(' + data.captured_pokemon.rarityRandom + '%)' + data.captured_pokemon.new);

                        //Effets en fonction de la rareté
                        if(data.captured_pokemon.shiny === true) {
                            pokemonShine = pokemonsShineDir + '/shiny-sparkle.gif';
                        }else if (data.captured_pokemon.rarity === 'TR'){
                            pokemonShine = pokemonsShineDir + '/sparkle.gif';
                        }else if(data.captured_pokemon.rarity === 'EX'){
                            pokemonShine = pokemonsShineDir + '/orange-sparkle.gif';
                        }else if(data.captured_pokemon.rarity === 'SR'){
                            pokemonShine = pokemonsShineDir + '/red-sparkle.gif';
                        }else{
                            pokemonShine = pokemonsShineDir + '/invisible-sparkle.gif';
                        }

                        //Fonds en fonction des types

                        document.querySelector('.view-pokemon').classList.remove('sea-background');
                        document.querySelector('.view-pokemon').classList.remove('cave-background');
                        document.querySelector('.view-pokemon').classList.remove('cave2-background');
                        document.querySelector('.view-pokemon').classList.remove('forest-background');
                        document.querySelector('.view-pokemon').classList.remove('forest2-background');
                        document.querySelector('.view-pokemon').classList.remove('night-background');

                        if( data.captured_pokemon.type === 'eau' ||
                            data.captured_pokemon.type === 'vol' ||
                            data.captured_pokemon.type === 'dragon'||
                            data.captured_pokemon.type === 'glace')
                        {

                            document.querySelector('.view-pokemon').classList.add('sea-background');

                        }


                        if (data.captured_pokemon.type === 'combat' ||
                            data.captured_pokemon.type === 'electrik' ||
                            data.captured_pokemon.type === 'feu')
                        {
                            document.querySelector('.view-pokemon').classList.add('forest-background');
                        }


                        if (data.captured_pokemon.type === 'plante' ||
                            data.captured_pokemon.type === 'insecte' ||
                            data.captured_pokemon.type === 'normal')
                        {
                            document.querySelector('.view-pokemon').classList.add('forest2-background');
                        }


                        if (data.captured_pokemon.type === 'roche' ||
                            data.captured_pokemon.type === 'acier' ||
                            data.captured_pokemon.type === 'fée' ||
                            data.captured_pokemon.type === 'sol')
                        {
                            document.querySelector('.view-pokemon').classList.add('cave-background');
                        }


                        if (data.captured_pokemon.type === 'psy' ||
                            data.captured_pokemon.type === 'spectre' ||
                            data.captured_pokemon.type === 'ténèbres' ||
                            data.captured_pokemon.type === 'poison')
                        {
                            document.querySelector('.view-pokemon').classList.add('night-background');
                        }


                        pokemonInfo.innerHTML = '';


                        //Affichage des infos du pokemon libéré

                        pokemonInfo.innerHTML = 'Vous avez libéré <span class="text-capitalize">' + data.captured_pokemon.name + '</span>'+ ((data.captured_pokemon.shiny) ? ' Shiny' : '') + ' (' + data.captured_pokemon.rarity + ') ! ' + ((data.captured_pokemon.new) ? '' : ' (New!) ');

                        document.querySelector('.pokeball-animate').classList.remove('pokeball-animated');


                    }
                    //Resolve de la promesse
                    resolve();


                })
            ;

        });

        await animatePromise;
        await getPokemonPromise;

        if(pokemonIsCaptured){
            pokemonImage.src = pokemonGif;
            pokemonShining.src = pokemonShine;
            document.querySelector('.view-pokemon').append(pokemonShining);
            document.querySelector('.view-pokemon').append(pokemonImage);
        }

        document.querySelector('.description-poke-capture').append(pokemonInfo);




        backToPlace();



        setTimeout(() => {
            captureInProcess = false;
        }, 1000);

    }


});