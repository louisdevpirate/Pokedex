<?php

namespace App\Event\Listener;


use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class UpdateLaunchListener{

    private ManagerRegistry $doctrine;
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage, ManagerRegistry $doctrine)
    {
        $this->tokenStorage = $tokenStorage;
        $this->doctrine = $doctrine;
    }

    public function onKernelRequest(RequestEvent $event): void
    {

        if(!$event->isMainRequest() || $this->tokenStorage->getToken() == null){
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        if ($user->getLaunchs() >= 100 ){
            return;
        }

        $lastObtainedLaunchTimestamp = $user->getLastObtainedLaunch()->format('U');

        $currentTimestamp = time();

        $difference = $currentTimestamp - $lastObtainedLaunchTimestamp;


        if($difference >= 1800){

            $numberOfLaunchs = (int) floor($difference / 1800);

            $secondsLeft = $difference % 1800;

            if ($numberOfLaunchs + $user->getLaunchs() > 100){

                $numberOfLaunchs = 100 - $user->getLaunchs();

                $secondsLeft = 0;

            }

            $user->setLaunchs( $user->getLaunchs() + $numberOfLaunchs );

            $user->setLastObtainedLaunch( new \DateTime('- ' . $secondsLeft . ' seconds') );

            $this->doctrine->getManager()->flush();

        }

    }

}