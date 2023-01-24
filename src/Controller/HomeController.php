<?php


namespace App\Controller;


use App\Entity\Pokemon;
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

//    #[IsGranted('ROLE_USER')]
    public function capture(): Response
    {
        return $this->render('main/capture.html.twig',[

        ]);
    }

    #[Route('/capture-api/', name: 'app_capture_api')]
//    #[IsGranted('ROLE_USER')]
    public function captureApi(ManagerRegistry $doctrine): Response
    {

        $pokeRepo = $doctrine->getRepository(Pokemon::class);
        $pokemons = $pokeRepo->findAll();

        $randomPoke = rand(0, count($pokemons) - 1);

        $pokemonCaptured = $pokemons[$randomPoke];

        return $this->json([
            'captured_pokemon' => [
                'id' => $pokemonCaptured->getId(),
                'name' => $pokemonCaptured->getName(),
                'gif' => $pokemonCaptured->getGif(),
                'type' => $pokemonCaptured->getType(),
                'type2' => $pokemonCaptured->getType2(),
                'description' => $pokemonCaptured->getDescription(),
            ],
        ]);
    }


   #[Route('/pokedex/', name: 'app_pokedex')]
   public function pokedex(): Response
   {
       return $this->render('main/pokedex.html.twig',[

       ]);

   }

    #[Route('/modify-profil/', name: 'app_modify')]
    #[IsGranted('ROLE_USER')]
    public function modifyProfil(Request $request, ManagerRegistry $doctrine): Response
    {

        // Creation du formulaire de modification des information du profil
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