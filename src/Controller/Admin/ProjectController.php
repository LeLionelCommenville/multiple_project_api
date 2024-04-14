<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/project', name: 'admin.project')]
#[IsGranted('ROLE_ADMIN')]
class ProjectController extends AbstractController
{
    #[Route('/', name: '.index')]
    public function index(ProjectRepository $repository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $projects = $repository->paginateProjects($page); 
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
            $em->persist($project);
            $em->flush();
            $this->addFlash('success', 'Project created');
            return $this->redirectToRoute('admin.project.index');
        }
        return $this->render('admin/project/edit.html.twig', [
            'form' => $createForm
        ]);
    }

    #[Route('/delete/{id}', name: '.delete', methods:['GET'], requirements: ['id' => '\d+'])]
    public function delete(Project $project, EntityManagerInterface $em): Response
    {
        $project_name = $project->getName();
        $em->remove($project);
        $em->flush();
        $this->addFlash('success', 'Project ' . $project_name . ' deleted');
        return $this->redirectToRoute('admin.project.index');
    }
}
