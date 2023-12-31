<?php

namespace App\Controller;

use League\Csv\Reader;
use App\Data\SeachData;
use App\Entity\AIcores;
use App\Data\ImportData;
use App\Entity\AIcategory;
use App\Form\IdentityType;
use App\Service\WooCommerce;
use App\Form\Import\ImportType;
use App\Form\Search\SearchType;
use App\Manager\IdentityManager;
use App\Repository\SectorRepository;
use App\Repository\AccountRepository;
use App\Repository\AIcoresRepository;
use App\Repository\PostingRepository;
use App\Repository\IdentityRepository;
use App\Repository\AIcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        PostingRepository $postingRepository,
        SectorRepository $sectorRepository,
        Request $request,
        PaginatorInterface $paginatorInterface
    ): Response
    {
        $offset = $request->query->get('offset', 0);
        $sectors = $sectorRepository->findAll();
        $postings = $postingRepository->findValid(10, $offset);
        // $data = $paginatorInterface->paginate(
        //     $postingRepository->findValid(),
        //     $request->query->getInt('page', 1),
        //     6
        // );

        return $this->render('home/index.html.twig', [
            'postings' => $postings,
            'sectors' => $sectors,
        ]);
    }

    #[Route('/home', name: 'app_homepage')]
    public function homePage(
        PostingRepository $postingRepository,
        IdentityRepository $identityRepository,
        SectorRepository $sectorRepository,
        Request $request,
        PaginatorInterface $paginatorInterface
    ): Response
    {
        $offset = $request->query->get('offset', 0);
        $sectors = $sectorRepository->findAll();
        $postings = $paginatorInterface->paginate(
            $postingRepository->findValid(6, $offset),
            $request->query->getInt('page', 1),
            6
        );
        $dataType = new SeachData();
        $form = $this->createForm(SearchType::class, $dataType);
        $form->handleRequest($request);
        $experts = $paginatorInterface->paginate(
            $identityRepository->findSearch($dataType),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('home/home.html.twig', [
            'postings' => $postings,
            'experts' => $experts,
            'sectors' => $sectors,
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

                $entity = $aIcoresRepository->findOneBy(['slug' => $product['slug']]);

                if(!$entity instanceof AIcores){
                    $entity = new AIcores();
                }

                $entity->setName($product['name']);
                $entity->setSlug($sluggerInterface->slug(strtolower($product['name'])));
                $entity->setType($product['status']);
                $entity->setUrl($product['external_url']);
                $entity->setImage($product['images'][0]->src);
                $entity->setDescription($product['short_description']);
                $entity->setSlogan($product['slogan']);

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
