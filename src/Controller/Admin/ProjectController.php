<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/admin/project", name="admin_project")
     */
    public function index()
    {
        return $this->render('admin/project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }
}
