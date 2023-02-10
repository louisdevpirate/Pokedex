<?php


namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\CapturedPokemon;
use App\Entity\Pokemon;
use App\Form\EditModifyProfilFormType;
use App\Entity\User;
use App\Form\RegistrationFormType;
use DateTime;
use App\Form\ModifyFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\NotBlank;


class HomeController extends AbstractController
{
    // Annotation qui permet à Symfony de retrouver quelle route correspond à quelle fonction 
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        $user = $this->getUser();
        if ($user) {
            $this->addFlash('success', sprintf('Bonjour %s', $user->getPseudonym()));
        }


        return $this->render('main/home.html.twig', [
        ]);
    }


    #[Route('/mon-profil/', name: 'app_profil')]
    #[IsGranted('ROLE_USER')]
    public function profil(ManagerRegistry $doctrine): Response
    {
        $userRepo = $doctrine->getRepository(User::class);
        $pokeRepo = $doctrine->getRepository(CapturedPokemon::class);
        $pokeRepo2 = $doctrine->getRepository(Pokemon::class);
        $user = $this->getUser();
        // Hydratation du nombre total de pokemon attrapés par l'utilisateur connecté.
        $capturedPokemon = $user->getCapturedPokemon();
        $pokemonIds = [];
        foreach ($capturedPokemon as $cp) {
            $pokemonIds[] = $cp->getPokemon()->getId();
        }
        // Hydratation des pokemon shiny et des pokemon uniques du pokedex attrapés par l'utilisateur.
        $uniquePokemonIds = array_unique($pokemonIds);

        $nbPokemonUnique = count($uniquePokemonIds);

        $nbPokemon = count($capturedPokemon);

//        Appel fonction pour connaitre le remplissage du pokedex de chaque utilisateur

        $allUsersSpeciesSeen = $userRepo->top10TotalSpeciesSeen();

        dump($allUsersSpeciesSeen);

//        Pour avoir le nombre de pokémons présents dans le pokedex

        $allPokemon = $pokeRepo2->findAll();

        $pokedexSize = count($allPokemon);

        $i = 0;



        $capturedPokemonShiny = $pokeRepo->findBy(['owner' => $user, 'shiny' => true]);
        $nbShiny = count($capturedPokemonShiny);




        return $this->render('main/profil.html.twig', [
            'nbPokemon' => $nbPokemon,
            'nbPokemonUnique' => $nbPokemonUnique,
            'nbShiny' => $nbShiny,
            'topUserSpeciesSeen' => $allUsersSpeciesSeen,
            'pokedexSize' => $pokedexSize,
            'num' => $i
        ]);


    }


    #[Route('/capture/', name: 'app_capture')]
    #[IsGranted('ROLE_USER')]
    public function capture(): Response
    {
        return $this->render('main/capture.html.twig', [

        ]);
    }

    #[Route('/capture-api/', name: 'app_capture_api')]
    #[IsGranted('ROLE_USER')]
    public function captureApi(ManagerRegistry $doctrine): Response
    {

        if($this->getUser()->getLaunchs() < 1){
            return $this->json([
                'error' => 'Vous n\'avez plus de lancers disponibles, veuillez réessayer plus tard !'
            ]);
        }


        $pokeRepo = $doctrine->getRepository(Pokemon::class);
        $userRepo = $doctrine->getRepository(User::class);


        //Calcul des probabilités

        $randomRarity = rand(10, 1000) / 10;


        //45%
        if ($randomRarity < 45) {

            $rarity = 'C';
        //30%
        } elseif ($randomRarity > 44.9 && $randomRarity < 74.9) {

            $rarity = 'PC';
        //15%
        } elseif ($randomRarity > 74.8 && $randomRarity < 89.8) {

            $rarity = 'R';
        //8.5%
        } elseif ($randomRarity > 89.7 && $randomRarity < 98.4) {

            $rarity = 'TR';
        //1%
        } elseif ($randomRarity > 98.3 && $randomRarity < 99.5) {

            $rarity = 'EX';
        //0.5%
        } else {

            $rarity = 'SR';

        }


        $pokemons = $pokeRepo->findByRarity($rarity);

        $randomPoke = rand(0, count($pokemons) - 1);

        $pokemonSpeciesCaptured = $pokemons[$randomPoke];

        $pokemonCaptured = new CapturedPokemon();

        //Calculs shiny


        $shinyTest = rand(1, 250);


        if ($shinyTest == 1) {

            $isShiny = true;
        } else {

            $isShiny = false;
        }


        //Hydratation BDD
        $pokemonCaptured
            ->setPokemon($pokemonSpeciesCaptured)
            ->setOwner($this->getUser())
            ->setCaptureDate(new DateTime())
            ->setShiny($isShiny);

        $em = $doctrine->getManager();

        $em->persist($pokemonCaptured);

        $this->getUser()->setLaunchs($this->getUser()->getLaunchs()-1);

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
    #[IsGranted('ROLE_USER')]
    public function pokedex(Pokemon $pokemon, ManagerRegistry $doctrine): Response
    {

        $pokeRepo = $doctrine->getRepository(Pokemon::class);


        $pokemonBefore = $pokeRepo->findPreviousSpecieEncounter($pokemon, $this->getUser());
        $pokemonNext = $pokeRepo->findNextSpecieEncounter($pokemon, $this->getUser());

        $pokemons = $pokeRepo->findBy([], ['pokeId' => 'ASC']);
        $pokemonsCaptured = $pokeRepo->getSpeciesEncounter($this->getUser());



        return $this->render('main/pokedex.html.twig', [
            'pokemonBefore' => $pokemonBefore,
            'currentPokemon' => $pokemon,
            'pokemonAfter' => $pokemonNext,
            'pokemons' => $pokemons,
            'pokemonsCaptured' => $pokemonsCaptured,
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
    //             'shiny' => $pokemon->getShiny(),
    //         ];

    //     }


    //     // Renvoi de la réponse HTTP
    //     return $this->json([
    //         'pokemons' => $pokemonsToReturn,
    //     ]);
    // }


    #[Route('/modify-profil/', name: 'app_modify')]
    #[IsGranted('ROLE_USER')]
    public function modifyProfil(UserPasswordHasherInterface $encoder, Request $request, ManagerRegistry $doctrine,): Response
    {
        /**
         * page de modification du profile de l'utilisateur (pseudonym et mot de passe).
         */

        $connectedUser = $this->getUser();


        $form = $this->createForm(EditModifyProfilFormType::class, $connectedUser);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // vérifier si le mot de passe actuel est correct

            $currentPassword = $form->get('currentPassword')->getData();

            if (!$encoder->isPasswordValid($connectedUser, $currentPassword)) {

                $form->get('currentPassword')->addError( new FormError('Mauvais mot de passe !') );


            } else {

                // Modification du profil
                $newPassword = $form->get('plainPassword')->getData();
                $hashNewPassword = $encoder->hashPassword($connectedUser, $newPassword);
                $connectedUser->setPassword($hashNewPassword);

                $em = $doctrine->getManager();
                $em->flush();

                // message flash de succès
                $this->addFlash('success', 'Votre profil a été modifié avec succès');

                return $this->redirectToRoute('app_profil');
            }
        }

        return $this->render('main/modify_profil.html.twig', [
            'editModifyProfilForm' => $form->createView(),
        ]);
    }



    #[Route('/types/', name: 'app_types')]
    public function types(): Response
    {
        return $this->render('main/types.html.twig', [
        ]);
    }

    #[Route('/about/', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('main/about.html.twig', [
        ]);
    }

    #[Route('/project/', name: 'app_project')]
    public function project(): Response
    {
        return $this->render('main/project.html.twig', [
        ]);
    }

    #[Route('/mentions-legales/', name: 'app_legals')]
    public function legals(): Response
    {
        return $this->render('main/legals.html.twig', [

        ]);
    }






}


