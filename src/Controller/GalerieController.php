<?php
// src/Controller/GalerieController.php
namespace App\Controller;

use App\Entity\Image;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalerieController extends AbstractController
{
    #[Route('/Galerie/{page<\d+>?1}', name: 'galerie')]
    public function index(ManagerRegistry $doctrine, $page = 1): Response
    {
        $imagesPerPage = 6;
        $repository = $doctrine->getRepository(Image::class);

        $images = $repository->findBy([], ['id' => 'ASC'], $imagesPerPage, ($page - 1) * $imagesPerPage);

        return $this->render('galerie/index.html.twig', [
            'images' => $images,
            'page' => $page,
        ]);
    }

    #[Route('/Galerie/add', name: 'galerie_add')]
    public function add(ManagerRegistry $doctrine): Response
    {
        $image = new Image();
        $image->setTitre('Lion');
        $image->setAuteur('Photographe');
        $image->setUrl('chemin/vers/limage.jpg');

        $em = $doctrine->getManager();
        $em->persist($image);
        $em->flush(); 

        return $this->redirectToRoute('accueil_galerie', ['page' => 1]);
    }
}