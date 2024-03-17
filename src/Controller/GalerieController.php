<?php
// src/Controller/GalerieController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GalerieController extends AbstractController
{
    public function index($page = 1): Response
    {
        $imagesPerPage = 6;
        $startIndex = ($page - 1) * $imagesPerPage;

        $allImages = [];
        for ($i = 1; $i <= 18; $i++) {
            $allImages[] = "image$i.jpg";
        }

        $images = array_slice($allImages, $startIndex, $imagesPerPage);

        return $this->render('galerie/index.html.twig', [
            'images' => $images,
            'page' => $page,
        ]);
    }
}