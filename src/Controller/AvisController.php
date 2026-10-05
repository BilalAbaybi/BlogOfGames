<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Jeux;
use App\Entity\Utilisateur;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Security\AvisVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AvisController extends AbstractController
{
    #[Route('/jeux/{id}/avis/nouveau', name: 'app_avis_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Jeux $jeux,
        Request $request,
        AvisRepository $avisRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        $utilisateur = $this->getUser();
        if (!$utilisateur instanceof Utilisateur) {
            throw $this->createAccessDeniedException();
        }

        if ($avisRepository->findOneBy(['utilisateur' => $utilisateur, 'jeux' => $jeux])) {
            $this->addFlash('info', 'Tu as déjà publié un avis pour ce jeu.');

            return $this->redirectToRoute('app_jeux_show', ['id' => $jeux->getId()]);
        }

        $avis = (new Avis())
            ->setUtilisateur($utilisateur)
            ->setJeux($jeux);

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avis);
            $entityManager->flush();

            return $this->redirectToRoute('app_jeux_show', ['id' => $jeux->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/new.html.twig', [
            'jeux' => $jeux,
            'form' => $form,
        ]);
    }

    #[Route('/avis/{id}/modifier', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function edit(Avis $avis, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(AvisVoter::EDIT, $avis);

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_jeux_show', ['id' => $avis->getJeux()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/edit.html.twig', [
            'avis' => $avis,
            'jeux' => $avis->getJeux(),
            'form' => $form,
        ]);
    }

    #[Route('/avis/{id}/supprimer', name: 'app_avis_delete', methods: ['POST'])]
    public function delete(Avis $avis, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(AvisVoter::DELETE, $avis);

        if ($this->isCsrfTokenValid('delete-avis-'.$avis->getId(), $request->getPayload()->getString('_token'))) {
            $jeuxId = $avis->getJeux()->getId();
            $entityManager->remove($avis);
            $entityManager->flush();

            return $this->redirectToRoute('app_jeux_show', ['id' => $jeuxId], Response::HTTP_SEE_OTHER);
        }

        throw $this->createAccessDeniedException('Le jeton de sécurité est invalide.');
    }
}
