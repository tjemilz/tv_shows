<?php

namespace App\Controller;

use App\Entity\OnlineCatalog;
use App\Entity\TvShow;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OnlineCatalogController extends AbstractController
{
    #[Route('/onlinecatalog', name: 'app_online_catalog')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager= $doctrine->getManager();
        $online_catalogs = $entityManager->getRepository(OnlineCatalog::class)->findAll();


        return $this->render('online_catalog/index.html.twig',
                [ 'online_catalogs' => $online_catalogs ]
            );
    }
    

    /**
     * Show a catalog
     *
     * @param Integer $id (note that the id must be an integer)
     */
    
    #[Route('/onlinecatalog/{id}', name: 'onlinecatalog_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id) : Response
    {
            $onlinecatRepo = $doctrine->getRepository(OnlineCatalog::class);
            $onlinecat = $onlinecatRepo->find($id);
       
            return $this->render('online_catalog/show.html.twig',
                    [ 'onlinecatalog' => $onlinecat ]);
    }

}
