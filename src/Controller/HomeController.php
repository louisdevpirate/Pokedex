<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    // Annotation qui permet à Symfony de retrouver quelle route correspond à quelle fonction 
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig', [
        ]);
    }

    #[Route('/register/', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('main/register.html.twig',[

        ]);
    }

    #[Route('/connexion/', name: 'app_connexion')]
    public function connexion(): Response
    {
        return $this->render('main/connexion.html.twig',[

        ]);
    }
    
    #[Route('/mon-profil/', name: 'app_profil')]
    public function profil(): Response
    {
        return $this->render('main/profil.html.twig',[

        ]);
    }

    

//    #[Route('/capture/', name: 'app_capture')]
//    public function capture(): Response
//    {
//        return $this->render('main/capture.html.twig',[
//
//        ]);
//    }

//    #[Route('/pokedex/', name: 'app_pokedex')]
//    public function pokedex(): Response
//    {
//        return $this->render('main/pokedex.html.twig',[
//
//        ]);
//    }


}