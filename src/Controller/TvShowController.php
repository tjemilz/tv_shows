<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\OnlineCatalog;
use App\Entity\TvShow;

class TvShowController extends AbstractController
{

    /**
     * Show a tv show 
     *
     * @param Integer $id (note that the id must be an integer)
     */
    
     #[Route('/{id}', name: 'tvshow_display', requirements: ['id' => '\d+'])]
     public function show(ManagerRegistry $doctrine, $id) : Response
     {
        
        $tvshows = $doctrine->getRepository(TvShow::class);
        $tvshow = $tvshows->find($id);

        return $this->render('tv_show/show.html.twig', [
            'tvshow' => $tvshow,
        ]);
     }
}
