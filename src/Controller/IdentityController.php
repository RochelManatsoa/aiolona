<?php

namespace App\Controller;

use App\Form\AccType;
use App\Entity\Compagny;
use App\Entity\Expert;
use App\Entity\Identity;
use App\Form\CompanyType;
use App\Form\ExpertType;
use App\Manager\IdentityManager;
use App\Service\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IdentityController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private EntityManagerInterface $em,
    ){}

    #[Route('/identity/create', name: 'app_identity')]
    public function create(): Response
    {
        return $this->render('bootstrap/identity/index.html.twig', [
            'controller_name' => 'IdentityController',
        ]);
    }

    #[Route('/identity/account', name: 'app_identity_account')]
    public function account(
        IdentityManager $identityManager,
        Request $request
        ): Response
    {
        /** @var User $user  */
        $user = $this->getUser();
        $identity = $this->userService->getCurrentIdentity();
        if(!$identity instanceof Identity){
            $identity = $identityManager->init();
            $identity->setUser($this->getUser());
            $identity->setFirstName($user->getFirstName());
            $identity->setLastName($user->getLastName());
            $identityManager->save($identity);
        }

        $form = $this->createForm(AccType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);
            if($identity->getAccount()->getSlug() !== "expert" ) return $this->redirectToRoute('app_identity_company', []);
            
            return $this->redirectToRoute('app_identity_expert', []);
        }

        return $this->render('bootstrap/identity/account.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/identity/company', name: 'app_identity_company')]
    public function company(Request $request,IdentityManager $identityManager): Response
    {
        $identity = $this->userService->getCurrentIdentity();
        $compagny = $identity->getCompagny();

        if (!$compagny instanceof Compagny) {
            $compagny = $identityManager->createCompagny($identity);
        }

        $form = $this->createForm(CompanyType::class, $compagny, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $compagny = $form->getData();
            $this->em->persist($compagny);
            $this->em->flush();

            return $this->redirectToRoute('app_identity_confirmation', []);
        }

        return $this->render('bootstrap/identity/company.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/identity/expert', name: 'app_identity_expert')]
    public function expert(IdentityManager $identityManager, Request $request): Response
    {
        $identity = $this->userService->getCurrentIdentity();
        $expert = $identity->getExpert();

        if (!$expert instanceof Expert) {
            $expert = $identityManager->createExpert($identity);
        }

        $form = $this->createForm(ExpertType::class, $expert, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $expert = $form->getData();
            $this->em->persist($expert);
            $this->em->flush();

            return $this->redirectToRoute('app_identity_confirmation', []);
        }

        return $this->render('bootstrap/identity/expert.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/identity/confirmation', name: 'app_identity_confirmation')]
    public function confirmation(): Response
    {
        return $this->render('bootstrap/identity/confirmation.html.twig', [
            'controller_name' => 'IdentityController',
        ]);
    }
}
