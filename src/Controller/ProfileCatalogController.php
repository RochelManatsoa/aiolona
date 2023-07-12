<?php

namespace App\Controller;

use App\Entity\Sector;
use App\Entity\Identity;
use App\Repository\IdentityRepository;
use App\Repository\SectorRepository;
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
        $data = $identityRepository->findAllValid();
        $identities = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            8
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

    #[Route('/experts/{username}/identity', name: 'app_profile_expert')]
    public function expert(Identity $identity, SectorRepository $sectorRepository, IdentityRepository $identityRepository): Response
    {
        return $this->render('profile_catalog/show.html.twig', [
            'identity' => $identity,
            'sectors' => $identity->getSectors(),
            'aicors' => $identity->getAicores(),
        ]);
    }
}
