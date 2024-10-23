<?php

namespace App\Controller;

use App\Entity\BestOnes;
use App\Form\BestOnesType;
use App\Repository\BestOnesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bestones')]
final class BestOnesController extends AbstractController
{
    #[Route(name: 'app_best_ones_index', methods: ['GET'])]
    public function index(BestOnesRepository $bestOnesRepository): Response
    {
        return $this->render('best_ones/index.html.twig', [
            'best_ones' => $bestOnesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_best_ones_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bestOne = new BestOnes();
        $form = $this->createForm(BestOnesType::class, $bestOne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bestOne);
            $entityManager->flush();

            return $this->redirectToRoute('app_best_ones_index', [], Response::HTTP_SEE_OTHER);
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

    #[Route('/{id}/edit', name: 'app_best_ones_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BestOnes $bestOne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BestOnesType::class, $bestOne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_best_ones_index', [], Response::HTTP_SEE_OTHER);
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

        return $this->redirectToRoute('app_best_ones_index', [], Response::HTTP_SEE_OTHER);
    }
}
