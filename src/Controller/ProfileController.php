<?php

namespace App\Controller;

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
                    'success',
                    'Vos informations ont bien été mises à jour'
                );
                
            } else {
                $this->addFlash(
                    'warning',
                    'Votre mot de passe est incorrecte'
                );
            }
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
