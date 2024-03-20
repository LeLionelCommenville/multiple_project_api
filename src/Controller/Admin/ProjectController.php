<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\User;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/project', name: 'admin.project')]
#[IsGranted('ROLE_ADMIN')]
class ProjectController extends AbstractController
{
    #[Route('/', name: '.index')]
    public function index(ProjectRepository $repository): Response
    {
        $projects = $repository->findAll();
        return $this->render('admin/project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/{id}', name: '.edit', methods: ['GET', 'POST'], requirements: ['id' =>'\d+'])]
    public function edit(ProjectRepository $repository, Project $project, Request $request, EntityManagerInterface $em): Response
    {
        $editForm = $this->createForm(ProjectType::class, $project);
        $editForm->handleRequest($request);
        if($editForm->isSubmitted() && $editForm->isValid()) {
            $project->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('success', 'Project updated');
            return $this->redirectToRoute('admin.project.index'); 
        }
        return $this->render('admin/project/edit.html.twig', [
            'project' => $project,
            'form' => $editForm
        ]);
    }

    #[Route('/create', name: '.create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $project = new Project();
        $createForm = $this->createForm(ProjectType::class, $project);
        $createForm->handleRequest($request);

        if($createForm->isSubmitted() && $createForm->isValid()) {
            $project->setCreatedAt(new \DateTimeImmutable());
            $project->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($project);
            $em->flush();
            $this->addFlash('success', 'Project created');
            return $this->redirectToRoute('admin.project.index');
        }
        return $this->render('admin/project/edit.html.twig', [
            'form' => $createForm
        ]);
    }
}
