<?php

namespace App\Controller;

use App\Data\SeachData;
use App\Entity\Account;
use App\Entity\Compagny;
use App\Entity\Identity;
use App\Service\User\UserService;
use App\Repository\PostingRepository;
use App\Repository\IdentityRepository;
use App\Form\Search\AdvancedSearchType;
use App\Service\Cart\CartService;
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
        PostingRepository $repository,
    ): Response
    {
        $params = $this->checkUserInfo();
        $params += ['annonces' => $repository->findAll()];
        
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
        ];

        return $this->render('company/search.html.twig', $params);
    }

    #[Route('/dashboard/company/cart', name: 'app_cart_dashboard')]
    public function cart(): Response
    {
        $params = $this->checkUserInfo();
        $params += [
            'items' => $this->  cartService->getFullCart(),
            'total' => $this->  cartService->getTotal()
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
