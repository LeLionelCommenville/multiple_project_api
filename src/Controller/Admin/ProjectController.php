<?php

namespace App\Controller\Admin;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/project', name: 'admin.project')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProjectRepository $repository): Response
    {
        $projects = $repository->findAll();
        return $this->render('admin/project/index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
