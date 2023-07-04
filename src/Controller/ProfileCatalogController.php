<?php

namespace App\Controller;

use App\Entity\Sector;
use App\Entity\Identity;
use App\Repository\IdentityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileCatalogController extends AbstractController
{
    #[Route('/experts', name: 'app_profile_catalog')]
    public function index(
        IdentityRepository $identityRepository,
        Request $request,
        PaginatorInterface $paginatorInterface
    ): Response {
        $data = $identityRepository->findAll();
        $identities = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('profile_catalog/index.html.twig', [
            'identities' => $identities,
        ]);
    }

    #[Route('/experts/{sector}', name: 'app_profile_sector')]
    public function sector(Sector $sector, IdentityRepository $identityRepository): Response
    {
        return $this->render('profile_catalog/sector.html.twig', [
            'identities' => $identityRepository->findBySector($sector->getSlug()),
        ]);
    }

    #[Route('/experts/{username}', name: 'app_profile_expert')]
    public function expert(Identity $identity, Sector $sector, IdentityRepository $identityRepository): Response
    {
        return $this->render('profile_catalog/show.html.twig', [
            'identities' => $identityRepository->findBySector($sector->getSlug()),
            'identity' => $identityRepository->findByUsername($identity->getUsername()),
        ]);
    }
}
