<?php

namespace App\Controller\Admin;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;

#[Route('admin/comment', name: 'admin.comment')]
#[IsGranted('ROLE_ADMIN')]
class CommentController extends AbstractController
{
    #[Route('/', name: '.index')]
    public function index(CommentRepository $repository): Response
    {
        $comments = $repository->findAll();
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments
        ]);
    }

    #[Route('/create', name: '.create')]
    public function create(EntityManagerInterface $em,Request $request)
    {
        $comment = new Comment();   
        $createForm = $this->createForm(CommentType::class, $comment);
        $createForm->handleRequest($request);

        if($createForm->isSubmitted() && $createForm->isValid()) {
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'A new comment has been added successfully');
            return $this->redirectToRoute('admin.comment.index');
        }
        return $this->render('admin/comment/create.html.twig', [
            'form' => $createForm
        ]);
    }

}