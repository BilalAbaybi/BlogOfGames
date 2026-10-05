<?php
// src/Controller/IndexController.php
namespace App\Controller;

use App\Repository\JeuxRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route('/ ', name: 'app_index')]
    public function index(JeuxRepository $jeuxRepository): Response
    {
        $jeuxRepository = $jeuxRepository->findAll();
        return $this->render('default.html.twig', ['jeux' => $jeuxRepository]);
    }
    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }
}