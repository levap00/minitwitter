<?php

namespace App\Controller;

use App\Entity\MiniTwits;
use App\Repository\MiniTwitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Required;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_mini_twits_like')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function like(MiniTwits $miniTwit, MiniTwitsRepository $miniTwits, Request $request): Response
    {
        $currUser = $this->getUser();
        $miniTwit->addLikedBy($currUser);
        $miniTwits->save($miniTwit, true);

        return $this->redirect($request->headers->get('referer'));
    }
    #[Route('/unlike/{id}', name: 'app_mini_twits_unlike')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function unlike(MiniTwits $miniTwit, MiniTwitsRepository $miniTwits, Request $request): Response
    {   
        $currUser = $this->getUser();
        $miniTwit->removeLikedBy($currUser);
        $miniTwits->save($miniTwit, true);

        return $this->redirect($request->headers->get('referer'));
    }
}
