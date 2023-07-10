<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Form\AvatarType;
use App\Form\ExpType;
use App\Form\IaType;
use App\Form\LangType;
use App\Form\LanguagesType;
use App\Form\OverviewType;
use App\Form\RateType;
use App\Form\SecteurType;
use App\Manager\IdentityManager;
use App\Repository\ExperienceRepository;
use App\Repository\LanguageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/create-profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/create-profile/sector', name: 'app_profile_sector')]
    public function sector(
        Request $request,
        IdentityManager $identityManager,
    ): Response
    {
        $user = $this->getUser();

        $identity = $identityManager->init();
        $form = $this->createForm(SecteurType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_profile_ia', [
                'identity' => $identity
            ]);
            
        }

        return $this->render('profile/sector.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create-profile/ia', name: 'app_profile_ia')]
    public function ia(
        Request $request,
        IdentityManager $identityManager,
    ): Response
    {
        $identity = $this->getUser()->getIdentity();

        $form = $this->createForm(IaType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_profile_experience', [
                'identity' => $identity
            ]);
            
        }

        return $this->render('profile/ia.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create-profile/experience', name: 'app_profile_experience')]
    public function experience(
        Request $request,
        IdentityManager $identityManager,
        ExperienceRepository $experienceRepository
    ): Response
    {
        $identity = $this->getUser()->getIdentity();
        $expriences = $experienceRepository->findBy(['identity' => $identity]);

        $form = $this->createForm(ExpType::class, $identity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_profile_experience', [
                'identity' => $identity,
            ]);
            
        }

        return $this->render('profile/experience.html.twig', [
            'form' => $form->createView(),
            'experiences' => $expriences
        ]);
    }

    #[Route('/create-profile/language', name: 'app_profile_language')]
    public function language(
        Request $request,
        IdentityManager $identityManager,
        LanguageRepository $languageRepository
    ): Response
    {
        $identity = $this->getUser()->getIdentity();
        $languages = $languageRepository->findBy(['identity' => $identity]);

        $form = $this->createForm(LangType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_profile_language', [
                'identity' => $identity
            ]);
            
        }

        return $this->render('profile/languages.html.twig', [
            'form' => $form->createView(),
            'languages' => $languages
        ]);
    }

    #[Route('/create-profile/rate', name: 'app_profile_rate')]
    public function rate(
        Request $request,
        IdentityManager $identityManager,
    ): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(RateType::class, $user = $this->getUser()->getIdentity(), []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_profile_overview', [
                'identity' => $identity
            ]);            
        }

        return $this->render('profile/rate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create-profile/overview', name: 'app_profile_overview')]
    public function overview(
        Request $request,
        IdentityManager $identityManager,
    ): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(OverviewType::class, $user = $this->getUser()->getIdentity(), []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_profile_avatar', [
                'identity' => $identity
            ]);            
        }

        return $this->render('profile/overview.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create-profile/avatar', name: 'app_profile_avatar')]
    public function avatar(
        Request $request,
        IdentityManager $identityManager,
    ): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(AvatarType::class, $user = $this->getUser()->getIdentity(), []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_account', [
                'identity' => $identity
            ]);            
        }

        return $this->render('profile/avatar.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
