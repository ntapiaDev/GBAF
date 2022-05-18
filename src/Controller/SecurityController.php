<?php

namespace App\Controller;

use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $resetPasswordForm = $this->createForm(ResetPasswordType::class);
        $resetPasswordForm->handleRequest($request);

        if($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()) {
            dd($request->request);
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'pwForm' => $resetPasswordForm->createView()
        ]);
    }

    #[Route('/connexion/reset', name: 'reset')]
    public function like(Request $request, UserRepository $userRepository,EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $resetData = $request->request->All('reset_password');
        
        $user = $userRepository->findOneBy(['username' => $resetData['username']]);

        if(!$user) {
            return $this->json([
                'code' => 'USER_NOT_FOUND',
                'message' => 'Cet utilisateur n\'existe pas'
            ], Response::HTTP_BAD_REQUEST); 
        } 
        
        $question = $user->getQuestion();
        $answer = $user->getAnswer();
        if($question != $resetData['question'] || $answer != $resetData['answer']) {
            return $this->json([
                'code' => 'BAD_VERIFICATION',
                'message' => 'Vous avez mal répondu à votre question secrète'
            ], Response::HTTP_BAD_REQUEST); 
        }

        $password = $resetData['new_password']['first'];
        if($password !== $resetData['new_password']['second']) {
            return $this->json([
                'code' => 'BAD_PASSWORD',
                'message' => 'Vos mots de passes ne sont pas identiques'
            ], Response::HTTP_BAD_REQUEST); 
        } elseif(strlen($password) < 8) {
            return $this->json([
                'code' => 'SHORT_PASSWORD',
                'message' => 'Votre mot de passe est trop petit (8 caractères minimum)'
            ], Response::HTTP_BAD_REQUEST); 
        } elseif (strlen($password) > 4096) {
            return $this->json([
                'code' => 'LONG_PASSWORD',
                'message' => 'Votre mot de passe est trop long (4096 caractères maximum)'
            ], Response::HTTP_BAD_REQUEST); 
        }

        $newPassword = $hasher->hashPassword($user, $password);
        $user->setPassword($newPassword);
        $manager->flush();

        return $this->json([
            'code' => 'PASSWORD CHANGED',
            'message' => 'Votre mot de passe a bien été mis à jour'
        ]);        
    }

    #[Route(path: '/deconnexion', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
