<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginTestController extends AbstractController
{
    #[Route('/', name: 'app_login_test')]
    public function index(): Response
    {
        return $this->render('login_test/index.html.twig', [
            'controller_name' => 'LoginTestController',
        ]);
    }
}
