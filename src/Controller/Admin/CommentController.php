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
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('admin/comment', name: 'admin.comment')]
#[IsGranted('ROLE_ADMIN')]
class CommentController extends AbstractController
{
    #[Route('/', name: '.index')]
    public function index(CommentRepository $repository,Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $comments = $repository->paginateComment($page);
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments
        ]);
    }

    #[Route('/category/{id}', name: '.category')]
    public function categoryComments(CommentRepository $repository, Request $request, int $id): Response
    {
        $page = $request->query->getInt('page', 1);
        $comments = $repository->commentByCategory($id, $page);
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments
        ]); 
    } 

    #[Route('/edit/{id}', name: '.edit', methods: ['POST', 'GET'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Comment $comment, Request $request, EntityManagerInterface $em)
    {
        $editForm = $this->createForm(CommentType::class, $comment);
        $editForm->handleRequest($request);

        if($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin.comment.index');
        }
        return $this->render('admin/comment/edit.html.twig', [
            'form' => $editForm,
            'comment' => $comment

        ]); 
    }

    #[Route('/delete/{id}', name: '.delete', requirements: ['id' => Requirement::DIGITS])]
    public function delete(EntityManagerInterface $em, comment $comment)
    {
        $em->remove($comment);
        $em->flush();
        $this->addFlash('success', 'The comment has been deleted');
        return $this->redirectToRoute('admin.comment.index');
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