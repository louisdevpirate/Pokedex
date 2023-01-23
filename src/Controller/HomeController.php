<?php


namespace App\Controller;


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
    public function capture(): Response
    {
        return $this->render('main/capture.html.twig',[

        ]);
    }

   #[Route('/pokedex/', name: 'app_pokedex')]
   public function pokedex(): Response
   {
       return $this->render('main/pokedex.html.twig',[

       ]);
   }


}