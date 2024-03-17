<?php
//src/Controller/GalerieController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalerieController extends AbstractController
{
    #[Route('/Galerie', name: 'galerie')]
    public function index(): Response
    {
        $images = [
            'image1.jpg',
            'image2.jpg',
            'image3.jpg',
            'image4.jpg',
            'image5.jpg',
            'image6.jpg'
        ];

        return $this->render('galerie/index.html.twig', [
            'images' => $images,
        ]);
    }
}
