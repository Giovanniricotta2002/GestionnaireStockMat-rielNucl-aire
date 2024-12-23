<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task', name: 'app_task')]
class TaskController extends AbstractController
{
    #[Route('/{taskId}', name: '_index')]
    public function index(int $taskId): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    #[Route('/new', name: '_new', methods: ['POST'])]
    public function newTask(Request $request): Response
    {
        $content = $request->getContent();

        return $this->json([$content]);
    }
}
