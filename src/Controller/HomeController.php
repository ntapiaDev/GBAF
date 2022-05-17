<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Note;
use App\Form\CommentType;
use App\Repository\NoteRepository;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/partner/{slug}/note', name: 'note')]
    public function like(Request $request, NoteRepository $noteRepository, PartnerRepository $partnerRepository, EntityManagerInterface $manager, $slug): Response
    {
        $noteData = $request->request->get('note');
        $user = $this->getUser();
        $partner = $partnerRepository->findOneBySlug($slug);

        $note = new Note();
        $note->setUser($user);
        $note->setPartner($partner);
        $note->setNote($noteData);

        $existingNote = $noteRepository->findOneBy([
            'user' => $user,
            'partner' => $partner
        ]);
        if(!$existingNote) {
            $manager->persist($note);
        } else {
            $existingNote->setNote($noteData);
        }

        $manager->flush();
        
        return $this->json([
            'code' => 'NOTE_ADDED_SUCCESSFULLY',
            'likeNumber' => $noteRepository->count(['partner' => $partner, 'note' => 1]),
            'dislikeNumber' => $noteRepository->count(['partner' => $partner, 'note' => -1])
        ]);
    }
}
