<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OnlineCatalogController extends AbstractController
{
    #[Route('/online/catalog', name: 'app_online_catalog')]
    public function index(): Response
    {
        return $this->render('online_catalog/index.html.twig', [
            'controller_name' => 'OnlineCatalogController',
        ]);
    }
}
