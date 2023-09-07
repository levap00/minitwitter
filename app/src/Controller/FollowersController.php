<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class FollowersController extends AbstractController
{
    #[Route('/follow/{id}', name: 'app_mini_twits_follow')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function follow(User $user, ManagerRegistry $doctrine, Request $request): Response
    {
        /** @var User $currUser */
        $currUser=$this->getUser();

        if($currUser->getId() !== $user->getId()){
            $currUser->addFollow($user);
            $doctrine->getManager()->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }
    #[Route('/unfollow/{id}', name: 'app_mini_twits_unfollow')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function unfollow(User $user, ManagerRegistry $doctrine, Request $request): Response
    {
        /** @var User $currUser */
        $currUser=$this->getUser();

        if($currUser->getId() !== $user->getId()){
            $currUser->removeFollow($user);
            $doctrine->getManager()->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }
    
}
