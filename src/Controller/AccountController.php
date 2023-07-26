<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\NoteType;
use App\Entity\AIcores;
use App\Entity\Identity;
use App\Entity\Language;
use App\Entity\Experience;
use App\Form\LanguageType;
use App\Form\OverviewType;
use App\Form\EditResumeType;
use App\Form\ExperienceType;
use App\Form\EditContactType;
use App\Manager\AiNoteManager;
use App\Manager\IdentityManager;
use App\Repository\AINoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        /** @var User $user  */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();

        if($identity instanceof Identity){
            /** @var Account $account */
            $account = $identity->getAccount();
            
            if($account instanceof Account && $account->getSlug() !== $account::EXPERT){
                return $this->redirectToRoute('app_dashboard');
            }
            
            return $this->render('account/index.html.twig', [
                'identity' => $identity,
            ]);
        }

        return $this->redirectToRoute('app_profile');
        
    }

    #[Route('/account/edit/contact', name: 'app_edit_contact')]
    public function editContact(Request $request, IdentityManager $identityManager): Response
    {
        /** @var User $user  */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();
        $form = $this->createForm(EditContactType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_resume', [
                'identity' => $identity
            ]);            
        }

        return $this->render('account/edit_resume.html.twig', [
            'identity' => $identity,
            'title' => 'Coordonées',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/edit/bio', name: 'app_edit_bio')]
    public function editBio(Request $request, IdentityManager $identityManager): Response
    {
        /** @var User $user  */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();
        $form = $this->createForm(OverviewType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_resume', [
                'identity' => $identity
            ]);            
        }

        return $this->render('account/edit_resume.html.twig', [
            'identity' => $identity,
            'title' => 'Overview',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/edit/experience/{id}', name: 'app_edit_experience')]
    public function editExperience(
        Request $request,
        Experience $experience,
        EntityManagerInterface $em
    ): Response
    {
        /** @var User $user  */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();
        $form = $this->createForm(ExperienceType::class, $experience, ['edit' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute('app_resume', [
                'identity' => $identity
            ]);            
        }

        return $this->render('account/edit_resume.html.twig', [
            'identity' => $identity,
            'title' => 'Experience',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/edit/language/{id}', name: 'app_edit_language')]
    public function editLanguage(
        Request $request, 
        Language $language, 
        EntityManagerInterface $em
    ): Response
    {
        /** @var User $user  */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();
        $form = $this->createForm(LanguageType::class, $language, ['edit' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
            
            return $this->redirectToRoute('app_resume', [
                'identity' => $identity
            ]);          
        }

        return $this->render('account/edit_resume.html.twig', [
            'identity' => $identity,
            'title' => 'Language',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/edit/skills/{slug}/', name: 'app_edit_skills')]
    public function editSkills(
        Request $request, 
        AIcores $aIcores, 
        AiNoteManager $aiNoteManager,
        AINoteRepository $aINoteRepository,
        EntityManagerInterface $em
    ): Response
    {
        /** @var User $user  */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();
        $note = $aiNoteManager->init($identity, $aIcores);
        $aicoreNote = $aiNoteManager->getNoteOrNull($identity, $aIcores);

        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute('app_resume', [
                'identity' => $this->getUser()->getIdentity()
            ]);            
        }

        return $this->render('account/edit_resume.html.twig', [
            'identity' => $identity,
            'title' => 'AI Tools',
            'aIcores' => $aIcores,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/edit/resume', name: 'app_edit_resume')]
    public function editResume(Request $request, IdentityManager $identityManager): Response
    {
        /** @var User $user  */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();
        $form = $this->createForm(EditResumeType::class, $identity, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_resume', [
                'identity' => $identity
            ]);            
        }

        return $this->render('account/edit_resume.html.twig', [
            'identity' => $identity,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/resume', name: 'app_resume')]
    public function resume(
        Request $request, 
        EntityManagerInterface $em,
        IdentityManager $identityManager
    ): Response
    {
        /** @var User $user  */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();
        $form = $this->createForm(EditResumeType::class, $identity, []);
        $formLang = $this->createForm(LanguageType::class, new Language(), []);
        $formExp = $this->createForm(ExperienceType::class, new Experience());
        $form->handleRequest($request);
        $formLang->handleRequest($request);
        $formExp->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $identity = $identityManager->saveForm($form);

            return $this->redirectToRoute('app_account', [
                'identity' => $identity
            ]);            
        }
        if ($formLang->isSubmitted() && $formLang->isValid()) {
            $identity->addLanguage($formLang->getData());
            $em->persist($identity);
            $em->flush();

            return $this->redirectToRoute('app_resume', [
                'identity' => $identity
            ]);            
        }
        if ($formExp->isSubmitted() && $formExp->isValid()) {
            $identity->addExperience($formExp->getData());
            $em->persist($identity);
            $em->flush();

            return $this->redirectToRoute('app_resume', [
                'identity' => $identity
            ]);            
        }

        return $this->render('account/resume.html.twig', [
            'identity' => $identity,
            'form' => $form->createView(),
            'formLang' => $formLang->createView(),
            'formExp' => $formExp->createView(),
        ]);
    }
}
