<?php

namespace App\Controller;

use App\Data\SeachData;
use App\Entity\Sector;
use App\Entity\Identity;
use App\Entity\IdentityLike;
use App\Entity\IdentityViews;
use App\Form\Search\SearchType;
use App\Repository\IdentityLikeRepository;
use App\Repository\IdentityRepository;
use App\Service\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
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
    ): Response {

        $dataType = new SeachData();
        $form = $this->createForm(SearchType::class, $dataType);
        $form->handleRequest($request);
        $data = $identityRepository->findSearch($dataType);

        return $this->render('profile_catalog/catalog.html.twig', [
            'identities' => $data,
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
        UserService $userService,
        Request $request,
        IdentityRepository $repository,
        EntityManagerInterface $em
    ): Response
    {
        $profilesUnlocked = $userService->getProfilesUnlocked();
        $class = false;

        if(in_array($identity, $profilesUnlocked)){
            $class = true;
        }
        if ($identity) {
            $ipAddress = $request->getClientIp();
            $viewRepository = $em->getRepository(IdentityViews::class);
            $existingView = $viewRepository->findOneBy([
                'identity' => $identity,
                'ipAddress' => $ipAddress,
            ]);
    
            if (!$existingView) {
                $view = new IdentityViews();
                $view->setIdentity($identity);
                $view->setIpAddress($ipAddress);
    
                $em->persist($view);
                $identity->addIdentityView($view);
                $em->flush();
            }
        }
        $allIdentities = $repository->findTopRanked();  
        $rank = array_search($identity, $allIdentities) + 1; 
        
        return $this->render('profile_catalog/view.html.twig', [
            'identity' => $identity,
            'sectors' => $identity->getSectors(),
            'aicors' => $identity->getAicores(),
            'class' => $class,
            'rank' => $rank,
            'total' => count($allIdentities),
        ]);
    }

    #[Route('/experts/{username}/pdf', name: 'app_profile_pdf')]
    public function cvExpert(
        Identity $identity, 
    ): BinaryFileResponse
    {
        $file = $this->getParameter('cv_directory').'/chris5-1-64c8af46d4b45.pdf';
        // $file = $this->getParameter('cv_directory').'/'.$identity->getCv();

        return new BinaryFileResponse($file);
    }

    #[Route('/company/{username}', name: 'app_profile_company')]
    public function company(Sector $sector, IdentityRepository $identityRepository): Response
    {
        return $this->render('profile_catalog/sector.html.twig', [
            'identities' => $identityRepository->findBySector($sector->getSlug()),
        ]);
    }

    #[Route('/experts/{username}/like', name: 'app_profile_like')]
    public function like(
        Identity $identity, 
        IdentityLikeRepository $likeRepository,
        EntityManagerInterface $em,
        UserService $userService
    ): Response
    {
        $user = $userService->getCurrentUser();
        if($identity->isLikedByUser($user)){
            $like = $likeRepository->findOneBy([
                'identity' => $identity,
                'user' => $user,
            ]);

            $em->remove($like);
            $em->flush();

            return $this->json([
                'code' => 200,
                'message' => "Like removed",
                'likes' => $likeRepository->count(['identity' => $identity])
            ], 200);
        }

        $like = new IdentityLike();
        $like
            ->setIdentity($identity)
            ->setUser($user)
            ;
        $em->persist($like);
        $em->flush();

        return $this->json([
            'code' => 200,
            'message' => "Like added",
            'likes' => $likeRepository->count(['identity' => $identity])
        ], 200);
    }
}
