<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Image;
use App\Form\ImageType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class GalerieController extends AbstractController
{
    #[Route('/Galerie/{page<\d+>?1}', name: 'galerie')]
    public function index(ManagerRegistry $doctrine, Request $request, $page = 1): Response
    {
        $imagesPerPage = 6;
        $repository = $doctrine->getRepository(Image::class);
        $images = $repository->findBy([], ['id' => 'ASC'], $imagesPerPage, ($page - 1) * $imagesPerPage);

        $searchForm = $this->createFormBuilder()
            ->add('auteur', TextType::class, ['required' => false])
            ->add('date', DateType::class, ['required' => false, 'widget' => 'single_text'])
            ->add('rechercher', SubmitType::class, ['label' => 'Rechercher'])
            ->getForm();

        $searchForm->handleRequest($request);

        // La logique de recherche peut être ajoutée ici en fonction des données $data obtenues.

        return $this->render('galerie/index.html.twig', [
            'searchForm' => $searchForm->createView(),
            'images' => $images,
            'page' => $page,
        ]);
    }

    #[Route('/Galerie/add', name: 'galerie_add')]
    public function add(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('fichier')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $logger->info('Probleme upload');
                }

                $image->setUrl($newFilename); 
            }

            $em = $doctrine->getManager();
            $em->persist($image);
            $em->flush();

            $this->addFlash('success', 'Image ajoutée avec succès!');
            return $this->redirectToRoute('galerie');
        }

        return $this->render('galerie/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
