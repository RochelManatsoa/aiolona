<?php

namespace App\Controller;

use App\Data\SeachData;
use App\Entity\Account;
use App\Entity\Compagny;
use App\Entity\Identity;
use App\Form\CompagnyType;
use App\Manager\IdentityManager;
use App\Service\Cart\CartService;
use App\Service\User\UserService;
use App\Repository\PostingRepository;
use App\Repository\IdentityRepository;
use App\Form\Search\AdvancedSearchType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompanyController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private UserService $userService
    )
    {
        $this->cartService = $cartService;
        $this->userService = $userService;
    }

    #[Route('/company/profile', name: 'app_company_profile')]
    public function profile(
        IdentityManager $identityManager,
        EntityManagerInterface $em,
        Request $request
    ): Response
    {        
        $identity = $this->userService->getCurrentIdentity();
        /** @var Compagny $compagny */
        $compagny = $identity->getCompagny();

        if (!$compagny instanceof Compagny) {
            $compagny = $identityManager->createCompagny($identity);
        }

        $form = $this->createForm(CompagnyType::class, $compagny, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $compagny = $form->getData();
            $em->persist($compagny);
            $em->flush();

            return $this->redirectToRoute('app_dashboard', []);
        }

        return $this->render('compagny/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/dashboard/company', name: 'app_dashboard')]
    public function index(): Response
    {
        $identity = $this->userService->getCurrentIdentity();
        if(!$identity instanceof Identity) return $this->redirectToRoute('app_account');
        if($identity->getAccount()->getSlug() === Account::EXPERT) return $this->redirectToRoute('app_expert');

        return $this->render('company/index.html.twig', $this->checkUserInfo());
    }

    #[Route('/dashboard/company/posting', name: 'app_posting_dashboard')]
    public function posting(
        PostingRepository $repository,
    ): Response
    {
        $params = $this->checkUserInfo();
        $params += ['annonces' => $repository->findBy(['compagny' => $params['company']])];

        return $this->render('company/posting.html.twig', $params);
    }

    #[Route('/dashboard/company/canditates', name: 'app_canditates_dashboard')]
    public function canditates(
        PostingRepository $postingRepository,
        UserService $userService
    ): Response
    {
        $params = $this->checkUserInfo();
        $params += [
            'annonces' => $postingRepository->findAll(),
            'commandes' => $userService->getUserCommande(),
            'identities' => $userService->getProfilesUnlocked(),
        ];
        
        return $this->render('company/candidates.html.twig', $params);
    }

    #[Route('/dashboard/search', name: 'app_search_dashboard')]
    public function search(
        IdentityRepository $identityRepository,
        PostingRepository $repository,
        Request $request,
        PaginatorInterface $paginatorInterface,
    ): Response
    {
        $params = $this->checkUserInfo();
        $dataType = new SeachData();
        $form = $this->createForm(AdvancedSearchType::class, $dataType);
        $form->handleRequest($request);
        $data = $identityRepository->findSearch($dataType);
        $identities = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            8
        );
        $params += [
            'annonces' => $repository->findAll(),
            'searchForm' => $form->createView(),
            'identities' => $identities,
            'unlocked' => $this->userService->getProfilesUnlocked(),
        ];

        return $this->render('company/search.html.twig', $params);
    }

    #[Route('/dashboard/company/cart', name: 'app_cart_dashboard')]
    public function cart(): Response
    {
        $params = $this->checkUserInfo();
        $params += [
            'items' => $this->cartService->getFullCart(),
            'total' => $this->cartService->getTotal(),
            'json' => json_encode($this->cartService->getCartSession()),
        ];

        return $this->render('company/cart.html.twig', $params);
    }

    private function checkUserInfo()
    {
        $identity = $this->userService->getCurrentIdentity();
        $company = $identity->getCompagny();

        return [
            'identity' => $identity,
            'company' => $company,
            'count' => $this->cartService->getCount(),
        ];
    }

}
