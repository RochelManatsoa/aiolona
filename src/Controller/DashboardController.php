<?php

namespace App\Controller;

use App\Data\SeachData;
use App\Entity\Account;
use App\Entity\Compagny;
use App\Entity\Identity;
use App\Form\Search\AdvancedSearchType;
use App\Form\Search\SearchType;
use App\Repository\PostingRepository;
use App\Repository\IdentityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();

        if(!$identity instanceof Identity){
            return $this->redirectToRoute('app_account');
        }
        
        if($identity->getAccount()->getSlug() === Account::EXPERT) return $this->redirectToRoute('app_expert');
        
        return $this->render('dashboard/ressource.html.twig', [
            'identity' => $identity,
            'compagny' => $identity->getCompagny(),
        ]);
    }

    #[Route('/dashboard/posting', name: 'app_posting_dashboard')]
    public function posting(
        PostingRepository $repository,
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();

        if(!$identity instanceof Identity){
            return $this->redirectToRoute('app_account');
        }

        /** @var Compagny $compagny */
        $compagny = $identity->getCompagny();

        if(!$compagny instanceof Compagny){
            $this->redirectToRoute('app_dashboard');
        }
        
        if($identity->getAccount()->getSlug() === Account::EXPERT) return $this->render('dashboard/expert.html.twig', ['identity' => $identity]);
        
        return $this->render('dashboard/ressource/posting.html.twig', [
            'annonces' => $repository->findBy([
                'compagny' => $compagny
            ]),
            'compagny' => $compagny,
            'identity' => $identity,
        ]);
    }

    #[Route('/dashboard/canditates', name: 'app_canditates_dashboard')]
    public function canditates(
        PostingRepository $repository,
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();

        if(!$identity instanceof Identity){
            return $this->redirectToRoute('app_account');
        }

        /** @var Compagny $compagny */
        $compagny = $identity->getCompagny();

        if(!$compagny instanceof Compagny){
            $this->redirectToRoute('app_dashboard');
        }
        
        if($identity->getAccount()->getSlug() === Account::EXPERT) return $this->render('dashboard/expert.html.twig', ['identity' => $identity]);
        
        return $this->render('dashboard/ressource/candidates.html.twig', [
            'annonces' => $repository->findAll(),
            'compagny' => $compagny,
            'identity' => $identity,
        ]);
    }

    #[Route('/dashboard/search', name: 'app_search_dashboard')]
    public function search(
        IdentityRepository $identityRepository,
        PostingRepository $repository,
        Request $request,
        PaginatorInterface $paginatorInterface
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();

        if(!$identity instanceof Identity){
            return $this->redirectToRoute('app_account');
        }

        /** @var Compagny $compagny */
        $compagny = $identity->getCompagny();

        if(!$compagny instanceof Compagny){
            $this->redirectToRoute('app_dashboard');
        }
        
        if($identity->getAccount()->getSlug() === Account::EXPERT) return $this->render('dashboard/expert.html.twig', ['identity' => $identity]);
        
        $dataType = new SeachData();
        $form = $this->createForm(AdvancedSearchType::class, $dataType);
        $form->handleRequest($request);
        $data = $identityRepository->findSearch($dataType);
        $identities = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('dashboard/ressource/search.html.twig', [
            'annonces' => $repository->findAll(),
            'compagny' => $compagny,
            'searchForm' => $form->createView(),
            'identity' => $identity,
            'identities' => $identities,
        ]);
    }
}
