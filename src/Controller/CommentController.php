<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PartnerRepository;
use App\Repository\UserRepository;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/ajax/comment', name: 'comment_add')]
    public function index(Request $request, CommentRepository $commentRepository, PartnerRepository $partnerRepository, UserRepository $userRepository, EntityManagerInterface $manager): Response
    {
        $commentData = $request->request->all('comment');

        if(!$this->isCsrfTokenValid('comment-add', $commentData['_token'])) {
            return $this->json([
                'code' => 'INVALID_CSRF_TOKEN'
            ], Response::HTTP_BAD_REQUEST);
        }

        $partner = $partnerRepository->findOneBy(['id' => $commentData['partner']]);
        if(!$partner) {
            return $this->json([
                'code' => 'ARTICLE_NOT_FOUND'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->findOneBy(['id' => $commentData['user']]);
        $comment = new Comment($partner, $user);
        $comment->setComment($commentData['comment']);
        $comment->setCreatedAt(new \DateTimeImmutable());

        $existingComment = $commentRepository->findOneBy([
            'user' => $user,
            'partner' => $partner
        ]);
        if(!$existingComment) {
            $manager->persist($comment);
            $message = 'COMMENT_ADDED_SUCCESSFULLY';
            $commentId = null;
        } else {
            $existingComment->setComment($commentData['comment']);
            $existingComment->setCreatedAt(new \DateTimeImmutable());
            $commentId = $existingComment->getId();
            $comment->setId($commentId);
            $message = 'COMMENT_EDITED_SUCCESSFULLY';
        }

        $manager->flush();

        $html = $this->renderView('comment/index.html.twig', [
            'comment' => $comment,
        ]);

        return $this->json([
            'code' => $message,
            'message' => $html,
            'id' => $commentId,
            'commentsNumber' => $commentRepository->count(['partner' => $partner])
        ]);
    }
}
