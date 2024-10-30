<?php

namespace App\Controller;

use App\Entity\BestOnes;
use App\Entity\Member;
use App\Form\BestOnesType;
use App\Repository\BestOnesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TvShow;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;



#[Route('/bestones')]
final class BestOnesController extends AbstractController
{
    #[Route(name: 'app_best_ones_index', methods: ['GET'])]
    public function index(BestOnesRepository $bestOnesRepository): Response
    {
        return $this->render('best_ones/index.html.twig', [
            'best_ones' => $bestOnesRepository->findBy(['published' => true]),
        ]);
    }

    #[Route('/new/{id}', name: 'app_best_ones_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Member $member): Response
    {
        $bestOne = new BestOnes();
        $bestOne->setCreator($member);

        $form = $this->createForm(BestOnesType::class, $bestOne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bestOne);
            $entityManager->flush();

            return $this->redirectToRoute('app_member_show', ['id' => $member->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('best_ones/new.html.twig', [
            'best_one' => $bestOne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_best_ones_show', methods: ['GET'])]
    public function show(BestOnes $bestOne): Response
    {
        return $this->render('best_ones/show.html.twig', [
            'best_one' => $bestOne,
        ]);
    }

      /*
     * Show a tv show 
     *
     * @param Integer $id (note that the id must be an integer)
     
    

     public function TvshowShow(ManagerRegistry $doctrine, $id) : Response
     {
        
        $tvshows = $doctrine->getRepository(TvShow::class);
        $tvshow = $tvshows->find($id);

        return $this->render('tv_show/show.html.twig', [
            'tvshow' => $tvshow,
        ]);
     }
    */



    #[Route('/{id}/edit', name: 'app_best_ones_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BestOnes $bestOne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BestOnesType::class, $bestOne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bestOne);
            $entityManager->flush();

            return $this->redirectToRoute('app_member_show', ['id' => $bestOne->getCreator()->getId() ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('best_ones/edit.html.twig', [
            'best_one' => $bestOne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_best_ones_delete', methods: ['POST'])]
    public function delete(Request $request, BestOnes $bestOne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bestOne->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bestOne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_member_show', ['id' => $bestOne->getCreator()->getId() ],Response::HTTP_SEE_OTHER);
    }



    #[Route('/{bestones_id}/tvshow/{tvshow_id}', methods: ['GET'], name: 'app_bestones_tvshow_show',requirements: ['bestones_id' => '\d+','tvshow_id' => '\d+'])] 
       public function TvshowShow(#[MapEntity(id: 'bestones_id')] 
       BestOnes $bestones,
       #[MapEntity(id: 'tvshow_id')]
       TvShow $tvshow): Response
    {
        if(! $bestones->getTvshows()->contains($tvshow)) {
                throw $this->createNotFoundException("Couldn't find such a tvshow in those bestones!");
        }

        // if(! $bestones->isPublished()) {
        //   throw $this->createAccessDeniedException("You cannot access the requested ressource!");
        //}

        return $this->render('best_ones/Tvshowshow.html.twig', [
                'tvshow' => $tvshow,
                  'bestones' => $bestones
          ]);
    }
}
