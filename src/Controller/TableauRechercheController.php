<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TableauRechercheController extends AbstractController
{
    #[Route('/tableau/recherche', name: 'app_tableau_recherche')]
    public function index(): Response
    {
        return $this->render('tableau_recherche/index.html.twig', [
            'controller_name' => 'TableauRechercheController',
        ]);
    }
}
