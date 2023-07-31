<?php

namespace App\Controller;

use App\Data\ImportData;
use App\Entity\AIcategory;
use App\Form\IdentityType;
use App\Entity\AIcores;
use App\Form\Import\ImportType;
use App\Manager\IdentityManager;
use App\Repository\AccountRepository;
use App\Repository\AIcategoryRepository;
use App\Repository\AIcoresRepository;
use App\Repository\PostingRepository;
use App\Service\WooCommerce;
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
    public function importCsvAction(
        Request $request, 
        AIcoresRepository $aIcoresRepository,
        AIcategoryRepository $aIcategoryRepository,
        EntityManagerInterface $entityManager, 
        SluggerInterface $sluggerInterface,
        WooCommerce $woocommerce
    )
    {
        $importType = new ImportData();
        $formImport = $this->createForm(ImportType::class, $importType);
        $formImport->handleRequest($request);
        $products = $woocommerce->importProduct($importType);
            
            foreach ($products as $product) {

                // if($product['status'] !== 'publish'){
                //     return;
                // }

                $entity = $aIcoresRepository->findOneBy(['slug' => $product['slug']]);

                if(!$entity instanceof AIcores){
                    $entity = new AIcores();
                }

                $entity->setName($product['name']);
                $entity->setSlug($sluggerInterface->slug(strtolower($product['name'])));
                $entity->setType($product['status']);
                $entity->setUrl($product['external_url']);
                $entity->setDescription($product['short_description']);

                foreach ($product['categories'] as $category) {

                    $aIcategory = $aIcategoryRepository->findOneBy(['slug' => $category->slug]);

                    if(!$aIcategory instanceof AIcategory){
                        $aIcategory = new AIcategory();
                    }

                    $aIcategory->setName($category->name);
                    $aIcategory->setSlug($category->slug);

                    $entityManager->persist($aIcategory);
                    $entity->addAIcategory($aIcategory);
                }

                $entityManager->persist($entity);
            }
            $entityManager->flush();
            $this->addFlash('success', 'Les produits sont bien importés');

        return $this->render('home/import.html.twig', [
            'formImport' => $formImport->createView(),
            'products' => $products
        ]);
    }
}
