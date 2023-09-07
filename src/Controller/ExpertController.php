<?php

namespace App\Controller;

use App\Entity\Identity;
use App\Data\SearchPostData;
use App\Entity\Account;
use App\Service\User\UserService;
use App\Form\Search\SearchPostType;
use App\Repository\IdentityRepository;
use App\Repository\PostingRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExpertController extends AbstractController
{
    public function __construct(private UserService $userService)
    {
        $this->userService = $userService;
    }
    
    #[Route('/dashboard/expert', name: 'app_expert')]
    public function index(
        Request $request,
        PostingRepository $postingRepository,
        PaginatorInterface $paginatorInterface
    ): Response
    {        
        $identity = $this->userService->getCurrentIdentity();

        if(!$identity instanceof Identity){
            return $this->redirectToRoute('app_account');
        }

        $postSearch = new SearchPostData();
        $form = $this->createForm(SearchPostType::class, $postSearch);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data = $postingRepository->findSearch($postSearch);
        }else{
            $data = $postingRepository->findBySkills($identity->getAicores());
            foreach ($identity->getExperiences() as $value) {
                $data += $postingRepository->findBySkills($value->getSkills());
            }
        }

        $postings = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );


        return $this->render('expert/index.html.twig', [
            'identity' => $identity,
            'postings' => $postings,
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/dashboard/expert/profile', name: 'app_account')]
    public function profile(
        Request $request,
        PostingRepository $postingRepository,
        IdentityRepository $repository,
        PaginatorInterface $paginatorInterface
    ): Response
    {        
        $identity = $this->userService->getCurrentIdentity();
        if(!$identity instanceof Identity) return $this->redirectToRoute('app_profile');
        /** @var $account */
        $account = $identity->getAccount();
        if(!$account instanceof Account) return $this->redirectToRoute('app_profile_account');
        
        if($identity->getSectors()->isEmpty()){
            $this->addFlash('danger', 'Selectionnez votre secteur d\'activité');
            return $this->redirectToRoute('app_profile_sector');
        } 

        if($identity->getAicores()->isEmpty()){
            $this->addFlash('danger', 'Veuillez informer vos outils favoris');
            return $this->redirectToRoute('app_profile_ia');
        } 

        if($identity->getBio() === null){
            $this->addFlash('danger', 'Parlez nous de vous');
            return $this->redirectToRoute('app_profile_overview');
        } 

        if($identity->getCountry() === null && $identity->getPhone()){
            $this->addFlash('danger', 'Merci de renseigner ces informations');
            return $this->redirectToRoute('app_profile_avatar');
        } 

        $allIdentities = $repository->findTopRanked();  
        $rank = array_search($identity, $allIdentities) + 1; 

        $postSearch = new SearchPostData();
        $form = $this->createForm(SearchPostType::class, $postSearch);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data = $postingRepository->findSearch($postSearch);
        }else{
            $data = $postingRepository->findBySkills($identity->getAicores());
            foreach ($identity->getExperiences() as $value) {
                $data += $postingRepository->findBySkills($value->getSkills());
            }
        }

        $postings = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );


        return $this->render('expert/profile.html.twig', [
            'identity' => $identity,
            'postings' => $postings,
            'form' => $form->createView(),
            'rank' => $rank,
            'total' => count($allIdentities),
        ]);
    }
}
