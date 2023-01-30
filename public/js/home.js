window.addEventListener('load', () => {
    const element = document.querySelector('.sacha');
    element.classList.add('fade-up');
});

window.addEventListener('load', () => {
    const element = document.querySelector('.project');
    element.classList.add('fade-up');
});



// Cibler l'élément chen-border
const chenBorder = document.querySelector('.chen-border');

// Ajouter un écouteur d'événement scroll sur la fenêtre
window.addEventListener('scroll', function () {
    // Récupérer la position du scroll
    const scrollPosition = window.scrollY;

    // Si la position du scroll est supérieure à la position de l'élément chen-border
    if (scrollPosition > chenBorder.offsetTop) {
        console.log("avant le if chen border");
        // Vérifier que l'élément chen-border existe avant de continuer
        if (chenBorder) {

            console.log("ajout de la classe fade-up");
            // Ajouter la classe fade-up à l'élément chen-border
            chenBorder.classList.add('fade-up');
            chenBorder.classList.remove('hide');
        }
    }
});