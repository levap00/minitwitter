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

class TopController extends AbstractController
{
    #[Route('/minitwits/top', name: 'app_mini_twits_top')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function showTop(MiniTwitsRepository $miniTwits, Request $request): Response
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

        //dd($miniTwits->findTopTwits(10));

        return $this->render('top/index.html.twig', [
            'form'=>$form,
            'form1'=>$form,
            'miniTwits' => $miniTwits->findTopTwits(10),
        ]);
    }
}
