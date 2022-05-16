<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'profile')]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Vos informations ont bien été mises à jour'
            );


        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
