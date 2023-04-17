let captureInProcess = false;
let elem = document.querySelector(".pokeball-animate");
function backToPlace() {
    elem.style.bottom = '0px';
    elem.style.rotate = '0deg';
}


const typesBackgroundArray = {
    eau: 'sea-background',
    feu: 'fire-background',
    plante: 'forest2-background',
    insecte: 'cabane-background',
    poison: 'poison-background',
    vol: 'montagne-background',
    sol: 'desert-background',
    roche: 'cave-background',
    'ténèbres' : 'cimetière-background',
    normal: 'forest2-background',
    combat: 'pourpre-background',
    psy: 'montagne-background',
    'fée': 'night-background',
    spectre: 'cimetière-background',
    dragon: 'dragon-background',
    acier: 'forest-background',
    electrik: 'pourpre-background',
    glace: 'neige-background',

};


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

        let currentNew = document.querySelector('.logo-new');
        if (currentNew){
            currentNew.remove();
        }

        let currentPokeDiv = document.querySelector('.poke-capture-div')
        if (currentPokeDiv){
            currentPokeDiv.remove();
        }


        let launchs = document.querySelector('.launch-items').textContent;

        launchs = parseInt(launchs);

        if (launchs > 0 ) {

            document.querySelector('.launch-items').textContent = (launchs - 1);

        }


        let pokemonImage = document.createElement('img');
        let pokemonShining = document.createElement('img');
        let pokemonInfo = document.createElement('p');
        let pokemonNewLogo = document.createElement('img');
        let pokemonDiv = document.createElement('div');
        pokemonImage.classList.add('displayed-pokemon');
        pokemonShining.classList.add('shining-effect');
        pokemonInfo.classList.add('pokemon-captured-infos');
        pokemonImage.alt = '';
        pokemonShining.alt = '';
        let pokemonGif;
        let pokemonShine;
        let pokemonIsNew;


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



                        document.querySelector('.view-pokemon').classList.remove(window.pokemonBackground);

                        window.pokemonBackground = typesBackgroundArray[data.captured_pokemon.type];

                        document.querySelector('.view-pokemon').classList.add(typesBackgroundArray[data.captured_pokemon.type]);


                        pokemonInfo.innerHTML = '';


                        //Affichage des infos du pokemon libéré

                        pokemonInfo.innerHTML = 'Vous avez libéré <span class="text-capitalize">' + data.captured_pokemon.name + '</span>'+ ((data.captured_pokemon.shiny) ? ' Shiny' : '') + ' (' + data.captured_pokemon.rarity + ') ! ';

                        console.log(data.captured_pokemon.rarity, data.captured_pokemon.rarityRandom);

                        document.querySelector('.pokeball-animate').classList.remove('pokeball-animated');

                        if (data.captured_pokemon.new === true){

                            pokemonIsNew = true;

                        }




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
            pokemonNewLogo.src = newLogo;

            pokemonDiv.classList.add('poke-capture-div');

            pokemonDiv.append(pokemonShining, pokemonImage);

            if (pokemonIsNew === true){

                pokemonNewLogo.classList.add('logo-new');
                pokemonDiv.append(pokemonNewLogo);

            }




            document.querySelector('.view-pokemon').append(pokemonDiv);





        }

        document.querySelector('.description-poke-capture').append(pokemonInfo);




        backToPlace();



        setTimeout(() => {
            captureInProcess = false;
        }, 1000);

    }


});