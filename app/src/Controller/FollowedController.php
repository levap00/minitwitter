<?php

namespace App\Controller;

use DateTime;
use App\Entity\MiniTwits;
use App\Form\MiniTwitsType;
use App\Repository\MiniTwitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FollowedController extends AbstractController
{
    #[Route('/minitwits/followed', name: 'app_mini_twits_followed')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function showFollowed(MiniTwitsRepository $miniTwits, Request $request): Response
    {   
        $form = $this->createForm(MiniTwitsType::class, new MiniTwits);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $miniTwit = $form->getData();
            $miniTwit->setAuthor($this->getUser());
            $miniTwit->setCreated(new DateTime());
            $miniTwits->save($miniTwit,true);

            $this->addFlash('success', 'Your miniTwit has been added');

            return $this->redirectToRoute('app_mini_twits');
        }

        /** @var User $currUser */
        $currUser = $this->getUser();
        return $this->render('followed/index.html.twig', [
            'form' => $form,
            'form1' => $form,
            'miniTwits' => $miniTwits->findFollowedTwits($currUser->getFollows()),
        ]);
    }
}
