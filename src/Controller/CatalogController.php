<?php

namespace App\Controller;

use App\Data\SeachData;
use App\Form\Search\SearchType;
use App\Manager\IdentityManager;
use App\Service\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatalogController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private EntityManagerInterface $em,
        private IdentityManager $identityManager
    ){}
    
    #[Route('/catalog/experts', name: 'app_catalog_experts')]
    public function experts(Request $request): Response
    {
        $dataType = new SeachData();
        $form = $this->createForm(SearchType::class, $dataType);
        $form->handleRequest($request);

        return $this->render('bootstrap/catalog/experts.html.twig', [
            'identities' => $this->identityManager->findSearch($dataType),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalog/posting', name: 'app_catalog_posting')]
    public function posting(): Response
    {
        return $this->render('bootstrap/catalog/posting.html.twig', [
            'controller_name' => 'CatalogController',
        ]);
    }

    #[Route('/catalog/ia', name: 'app_catalog_ia')]
    public function ia(): Response
    {
        return $this->render('bootstrap/catalog/ia.html.twig', [
            'controller_name' => 'CatalogController',
        ]);
    }

    #[Route('/catalog/formation', name: 'app_catalog_formation')]
    public function formation(): Response
    {
        return $this->render('bootstrap/catalog/formation.html.twig', [
            'controller_name' => 'CatalogController',
        ]);
    }
}
