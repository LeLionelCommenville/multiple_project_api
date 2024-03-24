<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/category', name: 'admin.category')]
#[IsGranted('ROLE_ADMIN')]
class CategoryController extends AbstractController
{
    #[Route('/', name: '.index')]
    public function index(CategoryRepository $categoryRepository): Response {
        $categories = $categoryRepository->findAll();
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/create', name: '.create' )]
    public function create(CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em): Response {
        $category = new Category();
        $createForm = $this->createForm(CategoryType::class, $category);
        $createForm->handleRequest($request);

        if($createForm->isSubmitted() && $createForm->isValid()) {
            $category->setCreatedAt(new \DateTimeImmutable());
            $category->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/create.html.twig', [
            "form" => $createForm
        ]);
    }

    #[Route('/edit/{id}', name: '.edit', metods: ['GET', 'POST'])]
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response {
        $editForm = $this->createForm(CategoryType::class, $category);
        $editForm->handleRequest($request);
        if($editForm->isSubmitted() && $editForm->isValid()) {
            $category->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/category/edit.html.twig', [
            "category" => $category,
            "form" => $editForm
        ]);
    }
}