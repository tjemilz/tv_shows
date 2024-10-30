<?php

namespace App\Controller;

use App\Entity\OnlineCatalog;
use App\Entity\TvShow;
use App\Form\TvShowType;
use App\Repository\TvShowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tvshow')]
final class TvShowController extends AbstractController
{
    #[Route(name: 'app_tv_show_index', methods: ['GET'])]
    public function index(TvShowRepository $tvShowRepository): Response
    {
        return $this->render('tv_show/index.html.twig', [
            'tv_shows' => $tvShowRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_tv_show_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, OnlineCatalog $onlineCatalog): Response
    {
        $tvShow = new TvShow();
        $tvShow->setOnlineCatalog($onlineCatalog);

        $form = $this->createForm(TvShowType::class, $tvShow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tvShow);
            $entityManager->flush();

            return $this->redirectToRoute('app_online_catalog', ['id' => $onlineCatalog->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tv_show/new.html.twig', [
            'tv_show' => $tvShow,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tv_show_show', methods: ['GET'])]
    public function show(TvShow $tvShow): Response
    {
        return $this->render('tv_show/show.html.twig', [
            'tv_show' => $tvShow,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tv_show_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TvShow $tvShow, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TvShowType::class, $tvShow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('onlinecatalog_show', ['id' => $tvShow->getOnlineCatalog()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tv_show/edit.html.twig', [
            'tv_show' => $tvShow,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tv_show_delete', methods: ['POST'])]
    public function delete(Request $request, TvShow $tvShow, EntityManagerInterface $entityManager): Response
    {
        $catalog = $tvShow->getOnlineCatalog();
        if ($this->isCsrfTokenValid('delete'.$tvShow->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tvShow);
            $entityManager->flush();
        }

        $catalog = $tvShow->getOnlineCatalog();
        if ($catalog) {
            return $this->redirectToRoute('onlinecatalog_show', ['id' => $catalog->getId()], Response::HTTP_SEE_OTHER);
        }
    }
}
