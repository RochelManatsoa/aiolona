<?php

namespace App\Controller;

use App\Data\SeachData;
use App\Entity\Sector;
use App\Entity\Identity;
use App\Form\Search\SearchType;
use App\Repository\IdentityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProfileCatalogController extends AbstractController
{
    #[Route('/experts', name: 'app_profile_catalog')]
    public function index(
        IdentityRepository $identityRepository,
        Request $request,
        PaginatorInterface $paginatorInterface
    ): Response {

        $dataType = new SeachData();
        $form = $this->createForm(SearchType::class, $dataType);
        $form->handleRequest($request);
        $data = $identityRepository->findSearch($dataType);
        $identities = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('profile_catalog/catalog.html.twig', [
            'identities' => $identities,
            'form' => $form->createView()
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
    public function expert(
        Identity $identity, 
    ): Response
    {
        return $this->render('profile_catalog/show.html.twig', [
            'identity' => $identity,
            'sectors' => $identity->getSectors(),
            'aicors' => $identity->getAicores(),
        ]);
    }

    #[Route('/experts/{username}/pdf', name: 'app_profile_pdf')]
    public function cvExpert(
        Identity $identity, 
    ): BinaryFileResponse
    {
        $file = $this->getParameter('cv_directory').'/Profile-64b7b55eae3fd.pdf';
        // $file = $this->getParameter('cv_directory').'/'.$identity->getCv();
        // dd($file);

        return new BinaryFileResponse($file);
    }
}
