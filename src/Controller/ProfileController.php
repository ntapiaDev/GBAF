<?php

namespace App\Controller;

use App\Form\EditPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'profile')]
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($hasher->isPasswordValid($user, $form->get('password')->getData())) {

                $user = $form->getData();
                // $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'edit_success',
                    'Vos informations ont bien été mises à jour'
                );

            } else {
                $this->addFlash(
                    'edit_warning',
                    'Votre mot de passe est incorrect'
                );
            }
        }

        $pwForm = $this->createForm(EditPasswordType::class);
        $pwForm->handleRequest($request);

        if($pwForm->isSubmitted() && $pwForm->isValid()) {
            if ($hasher->isPasswordValid($user, $pwForm->get('old_password')->getData())) {

                $new_pwd = $pwForm->get('new_password')->getData();
                $password = $hasher->hashPassword($user, $new_pwd);
                
                $user->setPassword($password);
                $manager->flush();
                $this->addFlash(
                    'pw_success',
                    'Votre mot de passe a bien été mis à jour'
                );
            } else {
                $this->addFlash(
                    'pw_warning',
                    'Votre mot de passe est incorrect'
                );
            }
        }

        $comments = $user->getComments();
        $notes = $user->getNotes();

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'pwForm' => $pwForm->createView(),
            'comments' => $comments,
            'notes' => $notes
        ]);
    }
}
