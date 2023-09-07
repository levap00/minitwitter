<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\MiniTwits;
use App\Form\ProfileType;
use App\Form\MiniTwitsType;
use League\Flysystem\Filesystem;
use League\Flysystem\AdapterInterface;
use App\Repository\UserRepository;
use App\Repository\MiniTwitsRepository;
use App\Repository\UserProfileRepository;
use Oneup\FlysystemBundle\DependencyInjection\Factory\AdapterFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfileController extends AbstractController
{   

    #[Route('/profile/edit', name: 'app_mini_twits_profile_edit')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function editUserProfile(Request $request, UserRepository $users, SluggerInterface $slugger, MiniTwitsRepository $miniTwits, Filesystem $filesystem): Response
    {   
        /** @var User $user */
        $user=$this->getUser();
        $userProfile = $user->getUserProfile();

        $form = $this->createForm(
            ProfileType::class, $userProfile
        );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $userProfile=$form->getData();
            $profileImageFile = $form->get('avatar')->getData();
            $backgroundImageFile = $form->get('backgroundImage')->getData();
            

            if($profileImageFile){
                $stream = fopen($profileImageFile -> getPathname(), 'r');
                $originalAvatarName = pathinfo(
                    $profileImageFile->getClientOriginalName(),
                    PATHINFO_FILENAME
                );
                $safeAvatarName = $slugger->slug($originalAvatarName);
                $newAvatarName = $safeAvatarName.'-'.uniqid().'.'.$profileImageFile->guessExtension();

                try {
                    $filesystem->writeStream(
                        'avatars/'.$newAvatarName,
                        $stream,
                        [
                            'visibility'=> 'public'
                        ],
                    );
                } catch (FileException $e1) {
                }

                $userProfile->setAvatar($newAvatarName);
            }

            if($backgroundImageFile){
                $backgroundStream = fopen($backgroundImageFile -> getPathname(), 'r');
                $originalBackgroundName = pathinfo(
                    $backgroundImageFile->getClientOriginalName(),
                    PATHINFO_FILENAME
                );
                $safeBackgroundName = $slugger->slug($originalBackgroundName);
                $newBackgroundName = $safeBackgroundName.'-'.uniqid().'.'.$backgroundImageFile->guessExtension();

                try {
                    $filesystem->writeStream(
                        'backgroundImages/'.$newBackgroundName,
                        $backgroundStream,
                        [
                            'visibility'=> 'public'
                        ],
                    );
                } catch (FileException $e2) {
                }

                $userProfile->setBackgroundImage($newBackgroundName);
            }

            $user->setUserProfile($userProfile);
            $users->save($user, true);
            $this->addFlash(
               'success',
               'Your user profile has been Updated'
            );
            return $this->redirectToRoute('app_mini_twits_other_profile',['id'=>$user->getId()] );
        }
        $form1 = $this->createForm(MiniTwitsType::class, new MiniTwits);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $miniTwitNew = $form1->getData();
            $miniTwitNew->setAuthor($this->getUser());
            $miniTwitNew->setCreated(new DateTime());
            $miniTwits->save($miniTwitNew,true);

            $this->addFlash('success', 'Your miniTwit has been added');

            return $this->redirectToRoute('app_mini_twits');
        }

        return $this->render('profile/edit_profile.html.twig',[
            'form1'=>$form1,
            'profileForm'=>$form->createView(),
        ]);
    }
    #[Route('/profile/{id}', name: 'app_mini_twits_other_profile')]
    public function showOtherUserProfile(User $user, MiniTwitsRepository $miniTwits, Request $request): Response
    {
        $form1 = $this->createForm(MiniTwitsType::class, new MiniTwits);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $miniTwitNew = $form1->getData();
            $miniTwitNew->setAuthor($this->getUser());
            $miniTwitNew->setCreated(new DateTime());
            $miniTwits->save($miniTwitNew,true);

            $this->addFlash('success', 'Your miniTwit has been added');

            return $this->redirectToRoute('app_mini_twits');
        }

        return $this->render('profile/profile.html.twig', [
            'form1'=>$form1,
            'miniTwits'=> $miniTwits->findByAuthor($user),
            'user'=> $user,
        ]);
    }
    #[Route('/profile/{id}/replies', name: 'app_mini_twits_other_profile_replies')]
    public function showOtherUserProfileTwitsAndReplies(User $user, MiniTwitsRepository $miniTwits, Request $request): Response
    {   
        $form1 = $this->createForm(MiniTwitsType::class, new MiniTwits);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $miniTwitNew = $form1->getData();
            $miniTwitNew->setAuthor($this->getUser());
            $miniTwitNew->setCreated(new DateTime());
            $miniTwits->save($miniTwitNew,true);

            $this->addFlash('success', 'Your miniTwit has been added');

            return $this->redirectToRoute('app_mini_twits');
        }
        return $this->render('profile/profile_replies.html.twig', [
            'form1'=>$form1,
            'miniTwits'=> $miniTwits->findByAuthor($user, true),
            'user'=> $user,
        ]);
    }
    #[Route('/profile/{id}/media', name: 'app_mini_twits_other_profile_media')]
    public function showOtherUserProfileMedia(User $user, MiniTwitsRepository $miniTwits, Request $request): Response
    {
        $form1 = $this->createForm(MiniTwitsType::class, new MiniTwits);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $miniTwitNew = $form1->getData();
            $miniTwitNew->setAuthor($this->getUser());
            $miniTwitNew->setCreated(new DateTime());
            $miniTwits->save($miniTwitNew,true);

            $this->addFlash('success', 'Your miniTwit has been added');

            return $this->redirectToRoute('app_mini_twits');
        }
        return $this->render('profile/profile_media.html.twig', [
            'form1'=>$form1,
            'user'=> $user,
        ]);
    }
    #[Route('/profile/{id}/likes', name: 'app_mini_twits_other_profile_likes')]
    public function showOtherUserProfileLikes(User $user, MiniTwitsRepository $miniTwits, Request $request): Response
    {
        $form1 = $this->createForm(MiniTwitsType::class, new MiniTwits);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $miniTwitNew = $form1->getData();
            $miniTwitNew->setAuthor($this->getUser());
            $miniTwitNew->setCreated(new DateTime());
            $miniTwits->save($miniTwitNew,true);

            $this->addFlash('success', 'Your miniTwit has been added');

            return $this->redirectToRoute('app_mini_twits');
        }
        return $this->render('profile/profile_likes.html.twig', [
            'form1'=>$form1,
            'miniTwits'=> $miniTwits->findLikedByUser($user),
            'user'=> $user,
        ]);
    }
    #[Route('/profile/{id}/followers', name: 'app_mini_twits_other_profile_followers')]
    public function showOtherUserProfileFollowers(User $user, MiniTwitsRepository $miniTwits, Request $request): Response
    {
        $form1 = $this->createForm(MiniTwitsType::class, new MiniTwits);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $miniTwitNew = $form1->getData();
            $miniTwitNew->setAuthor($this->getUser());
            $miniTwitNew->setCreated(new DateTime());
            $miniTwits->save($miniTwitNew,true);

            $this->addFlash('success', 'Your miniTwit has been added');

            return $this->redirectToRoute('app_mini_twits');
        }
        return $this->render('profile/profile_followers.html.twig', [
            'form1'=>$form1,
            'user'=> $user,
        ]);
    }
    #[Route('/profile/{id}/following', name: 'app_mini_twits_other_profile_following')]
    public function showOtherUserProfileFollowing(User $user, MiniTwitsRepository $miniTwits, Request $request): Response
    {   $form1 = $this->createForm(MiniTwitsType::class, new MiniTwits);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $miniTwitNew = $form1->getData();
            $miniTwitNew->setAuthor($this->getUser());
            $miniTwitNew->setCreated(new DateTime());
            $miniTwits->save($miniTwitNew,true);

            $this->addFlash('success', 'Your miniTwit has been added');

            return $this->redirectToRoute('app_mini_twits');
        }
        return $this->render('profile/profile_following.html.twig', [
            'form1'=>$form1,
            'user'=> $user,
        ]);
    }
}
