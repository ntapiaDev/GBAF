<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PartnerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PartnerRepository $partnerRepository): Response
    {
        $partners = $partnerRepository->findAll();

        return $this->render('home/index.html.twig', [
            'partners' => $partners
        ]);
    }

    #[Route('/partner/{slug}', name: 'partner')]
    public function show(PartnerRepository $partnerRepository, $slug): Response
    {
        $partner = $partnerRepository->findOneBySlug($slug);
        $user = $this->getUser();

        $comment = new Comment($partner, $user);
        $commentForm = $this->createForm(CommentType::class, $comment);

        return $this->render('home/partner.html.twig', [
            'partner' => $partner,
            'commentForm' => $commentForm->createView()
        ]);
    }
}
