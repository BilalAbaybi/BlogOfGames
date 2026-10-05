<?php

namespace App\Controller;

use App\Entity\Present;
use App\Form\PresentType;
use App\Repository\PresentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/present')]
final class PresentController extends AbstractController
{
    #[Route(name: 'app_present_index', methods: ['GET'])]
    public function index(PresentRepository $presentRepository): Response
    {
        return $this->render('present/index.html.twig', [
            'presents' => $presentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_present_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $present = new Present();
        $form = $this->createForm(PresentType::class, $present);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($present);
            $entityManager->flush();

            return $this->redirectToRoute('app_present_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('present/new.html.twig', [
            'present' => $present,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_present_show', methods: ['GET'])]
    public function show(Present $present): Response
    {
        return $this->render('present/show.html.twig', [
            'present' => $present,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_present_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Present $present, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PresentType::class, $present);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_present_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('present/edit.html.twig', [
            'present' => $present,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_present_delete', methods: ['POST'])]
    public function delete(Request $request, Present $present, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$present->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($present);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_present_index', [], Response::HTTP_SEE_OTHER);
    }
}
