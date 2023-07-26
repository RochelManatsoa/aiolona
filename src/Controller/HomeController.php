<?php

namespace App\Controller;

use App\Form\IdentityType;
use App\Entity\AIcores;
use App\Manager\IdentityManager;
use App\Repository\AccountRepository;
use App\Repository\PostingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use League\Csv\Reader;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        PostingRepository $postingRepository
    ): Response
    {
        return $this->render('home/index.html.twig', [
            'postings' => $postingRepository->findValid(),
        ]);
    }

    #[Route('/import', name: 'app_import')]
    public function importCsvAction(Request $request, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface)
    {
        $form = $this->createFormBuilder()
            ->add('csvFile', FileType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('csvFile')->getData();

            // Chemin complet vers le fichier CSV téléchargé
            $csvFilePath = $csvFile->getPathname();

            // Lire le fichier CSV avec la bibliothèque league/csv
            $csvReader = Reader::createFromPath($csvFilePath);
            $csvReader->setHeaderOffset(0); // Définir la ligne d'en-tête du CSV
            
            foreach ($csvReader as $row) {
                dump($row);
                // Créer une nouvelle instance de votre entité
                $entity = new AIcores();

                // Affecter les valeurs du CSV aux propriétés de l'entité
                $entity->setName($row['Name']);
                $entity->setSlug($sluggerInterface->slug(strtolower($row['Name'])));
                $entity->setType($row['Type']);
                $entity->setUrl($row['External URL']);
                $entity->setDescription($row['Button text']);
                $entityManager->persist($entity);
            }
            
            $entityManager->flush();
            die;
        }

        return $this->render('home/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
