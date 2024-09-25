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
    #[Route('/onlinecatalog', name: 'app_online_catalog', methods:['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $res = '<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Liste des catalogue</title>
        </head>
        <body>
            <h1>Catalogues</h1>
            <p>Liste des catalogues de tous les membres</p>
            <ul>';
            
        $entityManager= $doctrine->getManager();
        $catalogs = $entityManager->getRepository(OnlineCatalog::class)->findAll();

        $count = 1;
        foreach($catalogs as $catalog) {

            $res .= '<li>
            <a href="">'. "Catalogue numéro ". $count .'</a></li>';
            $count = $count + 1;

        }
        $res .= '</ul>';
        $res .= '</body></html>';
        
        return new Response(
            $res,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
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
            $onlinecatRepo = $doctrine->getRepository(TvShow::class);
            $onlinecat = $onlinecatRepo->find($id);

            if (!$onlinecat) {
                    throw $this->createNotFoundException('The catalog does not exist');
            }

            $res = '<h2>Tv Show Details :</h2>
            <ul>
            <dl>';
            
            $res .= '<dt>Name of the Tv Show</dt><dd>' . $onlinecat->getName() . '</dd>';
            $res .= '<dt>First episode release</dt> <dd> ' . $onlinecat->getYear(). '</dd>';
            $res .= '<dt>DIrector</dt> <dd> '. $onlinecat->getDirector() . '</dd>';
            $res .= '<dt>Note (/20)</dt><dd>' . $onlinecat->getNote() . '</dd>';
            $res .= '</dl>';
            $res .= '</ul>';
            

            $res .= '<p/><a href="">Back</a>';

            return new Response('<html><body>'. $res . '</body></html>');
    }

}
