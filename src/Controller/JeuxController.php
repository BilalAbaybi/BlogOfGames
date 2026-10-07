<?php

namespace App\Controller;

use App\Entity\Jeux;
use App\Entity\Utilisateur;
use App\Form\JeuxType;
use App\Repository\AvisRepository;
use App\Repository\JeuxRepository;
use App\Service\JeuxImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/jeux')]
final class JeuxController extends AbstractController
{
    #[Route(name: 'app_jeux_index', methods: ['GET'])]
    public function index(JeuxRepository $jeuxRepository): Response
    {
        return $this->render('jeux/index.html.twig', [
            'jeuxes' => $jeuxRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_jeux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, JeuxImageUploader $imageUploader): Response
    {
        $jeux = new Jeux();
        $form = $this->createForm(JeuxType::class, $jeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jeux->setGalerie($imageUploader->uploadAll($form->get('galerieFichiers')->getData() ?? []));
            $entityManager->persist($jeux);
            $entityManager->flush();

            return $this->redirectToRoute('app_jeux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jeux/new.html.twig', [
            'jeux' => $jeux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jeux_show', methods: ['GET'])]
    public function show(Jeux $jeux, AvisRepository $avisRepository): Response
    {
        $avisDejaDepose = false;
        $utilisateur = $this->getUser();
        if ($utilisateur instanceof Utilisateur) {
            $avisDejaDepose = null !== $avisRepository->findOneBy([
                'utilisateur' => $utilisateur,
                'jeux' => $jeux,
            ]);
        }

        return $this->render('jeux/show.html.twig', [
            'jeux' => $jeux,
            'avis_deja_depose' => $avisDejaDepose,
            'youtube_embed_url' => $this->youtubeEmbedUrl($jeux->getYoutubeUrl()),
        ]);
    }

    private function youtubeEmbedUrl(?string $url): ?string
    {
        if (null === $url || '' === trim($url)) {
            return null;
        }

        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');
        if (!in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be', 'www.youtu.be'], true)) {
            return null;
        }

        $videoId = null;
        if ('youtu.be' === $host || 'www.youtu.be' === $host) {
            $videoId = trim($parts['path'] ?? '', '/');
        } else {
            parse_str($parts['query'] ?? '', $query);
            $videoId = $query['v'] ?? null;

            if (null === $videoId && preg_match('~/(?:embed|shorts|live)/([^/]+)~', $parts['path'] ?? '', $matches)) {
                $videoId = $matches[1];
            }
        }

        if (!is_string($videoId) || !preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId)) {
            return null;
        }

        return 'https://www.youtube-nocookie.com/embed/'.$videoId;
    }

    #[Route('/{id}/edit', name: 'app_jeux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Jeux $jeux, EntityManagerInterface $entityManager, JeuxImageUploader $imageUploader): Response
    {
        $form = $this->createForm(JeuxType::class, $jeux, [
            'images_existantes' => $jeux->getGalerie(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagesASupprimer = array_values(array_intersect(
                $jeux->getGalerie(),
                $form->get('galerieASupprimer')->getData() ?? [],
            ));
            $jeux->setGalerie(array_values(array_diff($jeux->getGalerie(), $imagesASupprimer)));
            $imagesAjoutees = $imageUploader->uploadAll($form->get('galerieFichiers')->getData() ?? []);
            $jeux->setGalerie([...$jeux->getGalerie(), ...$imagesAjoutees]);
            $entityManager->flush();
            $imageUploader->deleteAll($imagesASupprimer);

            return $this->redirectToRoute('app_jeux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jeux/edit.html.twig', [
            'jeux' => $jeux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jeux_delete', methods: ['POST'])]
    public function delete(Request $request, Jeux $jeux, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jeux->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($jeux);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_jeux_index', [], Response::HTTP_SEE_OTHER);
    }
}
