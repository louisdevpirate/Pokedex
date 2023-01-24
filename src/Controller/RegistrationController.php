<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;


class RegistrationController extends AbstractController
{
    /**
     * Contrôleur de la page d'inscription
     */
    #[Route('/register/', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // si l'utilisateur est deja connecter, on le redirige de force sur la page d'acceuil du site

        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        // Création d'un nouvel objet utilisateur
        $user = new User();

        // Création d'un nouveau formulaire de création de compte, "branché" sur $user (pour l'hydrater)
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Remplissage du formulaire avec les données POST (qui sont dans request)
        $form->handleRequest($request);

        //Si le formulaire a bien été envoyé
        if ($form->isSubmitted()) {
            //TODO a faire le captcha


            if ($form->isValid()){
                // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            //hydratation de la date d'inscription du nouvel utilisateur

                $user->setCreationDate(new \DateTime);

                $entityManager->persist($user);
                $entityManager->flush();

            //TODO message flash de succès


            return $this->redirectToRoute('app_home');
        }
    }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


}
