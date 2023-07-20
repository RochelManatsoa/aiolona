<?php

namespace App\Controller;

use App\Entity\Compagny;
use App\Entity\Identity;
use App\Form\CompagnyType;
use App\Form\CompagnyIdType;
use App\Manager\IdentityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompagnyController extends AbstractController
{
    #[Route('/company', name: 'app_company')]
    public function index(): Response
    {
        return $this->render('compagny/index.html.twig', [
            'controller_name' => 'CompagnyController',
        ]);
    }

    #[Route('/company/profile', name: 'app_company_profile')]
    public function profile(
        IdentityManager $identityManager,
        EntityManagerInterface $em,
        Request $request
    ): Response
    {
        /** @var User $user  */
        $user = $this->getUser();

        /** @var Identity $identity */
        $identity = $user->getIdentity();

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
}
