<?php

namespace App\Controller;

use App\Entity\MiniTwits;
use App\Form\MiniTwitsType;
use App\Repository\MiniTwitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MiniTwitsController extends AbstractController
{
    #[Route('/minitwits', name: 'app_mini_twits')]
    public function index(Request $request, MiniTwitsRepository $miniTwits): Response
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
        return $this->render("main/index.html.twig",[
            'form'=>$form,
            'form1'=>$form,
            'miniTwits'=>$miniTwits->findAllWithLikes()
        ]);
    }
    #[Route('minitwits/minitwit/{id}', name: 'app_mini_twits_one')]
    public function showOne(MiniTwits $miniTwit, Request $request, MiniTwitsRepository $miniTwits): Response
    {
        $form = $this->createForm(MiniTwitsType::class, new MiniTwits);
        $form->handleRequest($request);
        $form1 = $this->createForm(MiniTwitsType::class, new MiniTwits);
        $form1->handleRequest($request);
        
        if($form->isSubmitted()&&$form->isValid()){
            $comment = $form->getData();
            $comment->setIsPublic(true);
            $comment->setTitle('fsasf');
            $comment->setAuthor($this->getUser());
            $comment->setCreated(new DateTime());
            $comment->setMiniTwit($miniTwit);
            $miniTwits->save($comment, true);

            $this->addFlash('success', 'Your comment have been added');

            return $this->redirectToRoute('app_mini_twits_one', ['id'=>$miniTwit->getId()]);
        }
        
        
        if ($form1->isSubmitted() && $form1->isValid()) {
            $miniTwitNew = $form1->getData();
            $miniTwitNew->setAuthor($this->getUser());
            $miniTwitNew->setCreated(new DateTime());
            $miniTwits->save($miniTwitNew,true);

            $this->addFlash('success', 'Your miniTwit has been added');

            return $this->redirectToRoute('app_mini_twits');
        }
        return $this->render('main/show_one.html.twig',[
            'form'=>$form,
            'form1'=>$form1,
            'miniTwit'=>$miniTwit,
            'comments'=>$miniTwits->findBy(['miniTwit'=>$miniTwit->getId()]),
        ]);
    }
}
