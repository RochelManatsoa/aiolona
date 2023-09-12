<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\IaType;
use App\Form\AccType;
use App\Form\ExpType;
use App\Form\LangType;
use App\Form\RateType;
use App\Entity\Compagny;
use App\Entity\Identity;
use App\Form\AvatarType;
use App\Form\SecteurType;
use App\Form\OverviewType;
use App\Manager\IdentityManager;
use App\Service\User\UserService;
use App\Repository\AIcoresRepository;
use App\Repository\LanguageRepository;
use App\Repository\ExperienceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfileController extends AbstractController
{
    public function __construct(private UserService $userService)
    {
        $this->userService = $userService;
    }
    
    #[Route('/create-profile', name: 'app_profile')]
    public function index(): Response
    {
        /** @var User $user  */
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/create-profile/account', name: 'app_profile_account')]
    public function account(
        Request $request,
        IdentityManager $identityManager,
    ): Response {
        /** @var User $user  */
        $user = $this->getUser();

        /** @var Identity $identity */
        $identity = $user->getIdentity();

        if (!$identity instanceof Identity) {
            $identity = $identityManager->init();
            $identity->setUser($this->getUser());
            $identity->setFirstName($user->getFirstName());
            $identity->setLastName($user->getLastName());
        }

        if ($identity->getCompagny() instanceof Compagny) return $this->redirectToRoute('app_dashboard', []);
        if ($identity->getAccount() instanceof Account && $identity->getAccount()->getSlug() === "ressource" ) return $this->redirectToRoute('app_company_profile', []);
        if ($identity->getAccount() instanceof Account && $identity->getAccount()->getSlug() === "expert" ) return $this->redirectToRoute('app_profile_sector', []);

        $form = $this->createForm(AccType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);
            if($identity->getAccount()->getSlug() !== "expert" ) return $this->redirectToRoute('app_company_profile', []);
            
            return $this->redirectToRoute('app_profile_sector', []);
        }

        return $this->render('profile/account.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create-profile/sector', name: 'app_profile_sector')]
    public function sector(
        Request $request,
        IdentityManager $identityManager,
    ): Response {
        $identity = $this->userService->getCurrentIdentity();

        if (!$identity instanceof Identity) {
            $identity = $identityManager->init();
            $identity->setUser($this->getUser());
        }
        $form = $this->createForm(SecteurType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_profile_ia', []);
        }

        return $this->render('profile/sector.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create-profile/ia', name: 'app_profile_ia')]
    public function ia(
        Request $request,
        IdentityManager $identityManager,
        AIcoresRepository $aIcoresRepository
    ): Response {
        /** @var User $user  */
        $user = $this->getUser();

        /** @var Identity $identity */
        $identity = $user->getIdentity();

        $form = $this->createForm(IaType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_profile_experience', []);
        }

        return $this->render('profile/ia.html.twig', [
            'form' => $form->createView(),
            'identity' => $identity,
        ]);
    }

    #[Route('/create-profile/experience', name: 'app_profile_experience')]
    public function experience(
        Request $request,
        IdentityManager $identityManager,
        ExperienceRepository $experienceRepository
    ): Response {
        $identity = $this->userService->getCurrentIdentity();
        $expriences = $experienceRepository->findBy(['identity' => $identity]);

        $form = $this->createForm(ExpType::class, $identity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_profile_experience', []);
        }

        return $this->render('profile/experience.html.twig', [
            'form' => $form->createView(),
            'experiences' => $expriences,
            'identity' => $identity
        ]);
    }

    #[Route('/create-profile/language', name: 'app_profile_language')]
    public function language(
        Request $request,
        IdentityManager $identityManager,
        LanguageRepository $languageRepository
    ): Response {
        $identity = $this->userService->getCurrentIdentity();

        $languages = $languageRepository->findBy(['identity' => $identity]);
        $form = $this->createForm(LangType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_profile_language', []);
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
    ): Response {
        $identity = $this->userService->getCurrentIdentity();
        $form = $this->createForm(RateType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_profile_overview', []);
        }

        return $this->render('profile/rate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create-profile/overview', name: 'app_profile_overview')]
    public function overview(
        Request $request,
        IdentityManager $identityManager,
        SluggerInterface $slugger
    ): Response {
        $identity = $this->userService->getCurrentIdentity();
        $form = $this->createForm(OverviewType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $form->getData();
            $cvFile = $form->get('cv')->getData();
            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();
                try {
                    $cvFile->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $identity->setCv($newFilename);
            }
            $identity = $identityManager->save($identity);

            return $this->redirectToRoute('app_profile_avatar', []);
        }

        return $this->render('profile/overview.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create-profile/avatar', name: 'app_profile_avatar')]
    public function avatar(
        Request $request,
        IdentityManager $identityManager,
    ): Response {
        $identity = $this->userService->getCurrentIdentity();

        $form = $this->createForm(AvatarType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_account', []);
        }

        return $this->render('profile/avatar.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
