
let lastMove = 0;





function myMove() {
    let elem = document.querySelector(".pokeball-animate");
    let pos = 0;
    let angle = 0;
    let id = setInterval(frame, 5);
    clearInterval(id);
    id = setInterval(frame, 10);
    setTimeout(backToPlace, 5000);
    function backToPlace() {
        elem.style.bottom = '0px';
        elem.style.rotate = '0deg';
    }

    function frame() {

        if (pos === 250){

            clearInterval(id);

        }else if (pos !== 250) {
            pos+=5;
            angle += 22;
            elem.style.bottom = pos + 'px';
            elem.style.rotate = angle + 'deg';

        } else {


        }
        // backToPlace();
    }

}






document.querySelector('.capture-poke-button').addEventListener("click", function (){

    //Si il y'a deja un pokemon, on l'enleve
    let currentPoke = document.querySelector('.displayed-pokemon');
    if(currentPoke){
        currentPoke.remove();
    }

    fetch(capturedPageApi)
        .then((response) => response.json())
        .then((data) => {
            // console.log(data);

            // <img src="{{ asset('images/gifs/charizard.gif') }}" alt="">
            let pokemonImage = document.createElement('img');
            pokemonImage.classList.add('displayed-pokemon');
            pokemonImage.alt = '';
            pokemonImage.src = pokemonsGifDir + '/' + data.captured_pokemon.gif;

            document.querySelector('.view-pokemon').append(pokemonImage);

            // alert( 'Vous avez capturé un ' + data.captured_pokemon.name + '!' );

        })
    ;

    //Si pokemon apparu il disparait

    //Pokebal se lance
    if(Date.now() - lastMove > 3000) {
        // Do stuff
        lastMove = Date.now();

        myMove('.pokeball-animate');
    }

    //Pokeball disparait et se remet dans son bouton

    //pokemon apparait + son background






});



