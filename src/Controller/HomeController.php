<?php


namespace App\Controller;


use App\Entity\CapturedPokemon;
use App\Entity\Pokemon;
use DateTime;
use App\Form\ModifyFormType;
use App\Form\RegistrationFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class HomeController extends AbstractController
{
    // Annotation qui permet à Symfony de retrouver quelle route correspond à quelle fonction 
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig',[
        ]);
    }

    
    #[Route('/mon-profil/', name: 'app_profil')]
    #[IsGranted('ROLE_USER')]
    public function profil(): Response
    {
        return $this->render('main/profil.html.twig',[

        ]);
    }




    #[Route('/capture/', name: 'app_capture')]
    #[IsGranted('ROLE_USER')]
    public function capture(): Response
    {
        return $this->render('main/capture.html.twig',[

        ]);
    }

    #[Route('/capture-api/', name: 'app_capture_api')]
    #[IsGranted('ROLE_ADMIN')]
    public function captureApi(ManagerRegistry $doctrine): Response
    {

        $pokeRepo = $doctrine->getRepository(Pokemon::class);

        $randomRarity = rand(10,1000)/10;


        if($randomRarity < 40 ){

            $rarity = 'C';

        }elseif ($randomRarity > 39.9 && $randomRarity < 64.9){

            $rarity = 'PC';

        }elseif($randomRarity > 64.8 && $randomRarity < 84.8){

            $rarity = 'R';

        }elseif($randomRarity > 84.7 && $randomRarity < 94.7){

            $rarity = 'TR';

        }elseif($randomRarity > 94.6 && $randomRarity < 99.6){

            $rarity = 'EX';

        }else{

            $rarity = 'SR';

        }


        $pokemons = $pokeRepo->findByRarity($rarity);

        $randomPoke = rand(0, count($pokemons) - 1);

        $pokemonSpeciesCaptured = $pokemons[$randomPoke];


        $pokemonCaptured = new CapturedPokemon();

        //Calculs shiny

        $shinyTest = rand(1,200);

        if ($shinyTest == 1){

            $isShiny = true;
        }else{

            $isShiny = false;
        }


        //Hydratation BDD
        $pokemonCaptured
            ->setPokemon($pokemonSpeciesCaptured)
            ->setOwner($this->getUser())
            ->setCaptureDate(new DateTime())
            ->setShiny($isShiny)

        ;

        $em = $doctrine->getManager();

        $em->persist($pokemonCaptured);

        $em->flush();


        //Retour des informations a Javascript
        return $this->json([
            'captured_pokemon' => [
                'id' => $pokemonCaptured->getPokemon()->getId(),
                'name' => $pokemonCaptured->getPokemon()->getName(),
                'gif' => $pokemonCaptured->getPokemon()->getGif(),
                'type' => $pokemonCaptured->getPokemon()->getType(),
                'type2' => $pokemonCaptured->getPokemon()->getType2(),
                'description' => $pokemonCaptured->getPokemon()->getDescription(),
                'shiny' => $pokemonCaptured->getShiny(),
                'rarity' => $rarity,
                'rarityRandom' => $randomRarity,
            ],
        ]);
    }


   #[Route('/pokedex/{pokeId}/', name: 'app_pokedex')]
   public function pokedex(Pokemon $pokemon, ManagerRegistry $doctrine): Response
   {

        $pokeRepo = $doctrine->getRepository(Pokemon::class);

        $pokemonBefore = $pokeRepo->findPrev($pokemon);
        $pokemonNext = $pokeRepo->findNext($pokemon);

        $pokemons = $pokeRepo->findBy([], ['pokeId' => 'ASC']);
       
       return $this->render('main/pokedex.html.twig',[
           'pokemonBefore' => $pokemonBefore,
           'currentPokemon' => $pokemon,
           'pokemonAfter' => $pokemonNext,
           'pokemons' => $pokemons,
        ]);
        
   }

    // #[Route('/pokedex-api/', name: 'app_pokedex_api')]
    // public function pokedexApi(ManagerRegistry $doctrine): Response
    // {
    //     // Récupération du gestionnaire d'entités
    //     $pokeRepo = $doctrine->getRepository(Pokemon::class);

    //     // Récupération des données de la base de données
    //     $pokemons = $pokeRepo->findAll();

    //     $pokemonsToReturn = [];


    //     foreach($pokemons as $pokemon){

    //         $pokemonsToReturn[] = [
    //             'name' => $pokemon->getName(),
    //             'description' => $pokemon->getDescription(),
    //         ];
            
    //     }


    //     // Renvoi de la réponse HTTP
    //     return $this->json([
    //         'pokemons' => $pokemonsToReturn,
    //     ]);
    // }





    #[Route('/modify-profil/', name: 'app_modify')]
    #[IsGranted('ROLE_USER')]
    public function modifyProfil(Request $request, ManagerRegistry $doctrine): Response
    {

        // Creation du formulaire de modification des informations du profil
        $form = $this->createForm(RegistrationFormType::class, $this->getUser());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $doctrine->getManager();
            $em->flush();

        }

        return $this->render('main/modify_profil.html.twig',[
            'modifyform' => $form->createView(),
        ]);
    }


   
}




