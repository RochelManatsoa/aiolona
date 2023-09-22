<?php

namespace App\Controller;

use DateTime;
use App\Entity\Sector;
use App\Entity\Posting;
use App\Entity\Application;
use App\Entity\PostingViews;
use App\Form\ApplicationType;
use App\Manager\PostingManager;
use App\Form\Posting\StepOneType;
use App\Form\Posting\StepTwoType;
use App\Service\User\UserService;
use App\Form\Posting\StepThreeType;
use App\Repository\PostingRepository;
use App\Repository\SectorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostingController extends AbstractController
{
    #[Route('/posting', name: 'app_posting')]
    public function index(): Response
    {
        return $this->render('posting/index.html.twig', [
            'controller_name' => 'PostingController',
        ]);
    }

    #[Route('/posting/new', name: 'app_posting_new')]
    public function new(
        PostingManager $postingManager,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        /** @var User $user */
        $user = $this->getUser();
        $posting = $postingManager->init($user->getIdentity()->getCompagny());
        $form = $this->createForm(StepOneType::class, $posting);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $posting = $form->getData();
            $em->persist($posting);
            $em->flush();

            return $this->redirectToRoute('app_posting_steptwo', [ 
                'jobId' => $posting->getJobId()
            ]);
        }

        return $this->render('posting/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/posting/edit/{jobId}', name: 'app_posting_edit')]
    public function edit(
        Posting $posting,
        PostingManager $postingManager,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(StepOneType::class, $posting);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $posting = $postingManager->saveForm($form);

            return $this->redirectToRoute('app_posting_steptwo', [ 
                'jobId' => $posting->getJobId()
            ]);
        }

        return $this->render('posting/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/posting/job-details/{jobId}', name: 'app_posting_steptwo')]
    public function details(
        Request $request,
        Posting $posting,
        EntityManagerInterface $em
    ): Response
    {
        $form = $this->createForm(StepTwoType::class, $posting);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $posting = $form->getData();
            $em->persist($posting);
            $em->flush();

            return $this->redirectToRoute('app_posting_stepthree', [ 
                'jobId' => $posting->getJobId()
            ]);
        }

        return $this->render('posting/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/posting/job-description/{jobId}', name: 'app_posting_stepthree')]
    public function description(
        Request $request,
        Posting $posting,
        EntityManagerInterface $em
    ): Response
    {
        $form = $this->createForm(StepThreeType::class, $posting);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $posting = $form->getData();
            $em->persist($posting);
            $em->flush();

            $this->addFlash('info', 'Votre annonce a été créée avec succès et est en attente de validation par l\'administrateur.');

            return $this->redirectToRoute('app_posting_dashboard', [ 
                'jobId' => $posting->getJobId()
            ]);
        }

        return $this->render('posting/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/posting/view/{jobId}', name: 'app_posting_view')]
    public function view(
        Request $request,
        Posting $posting,
        EntityManagerInterface $em,
        UserService $userService
    ): Response
    {
        $identity = $userService->getCurrentIdentity();
        $application = new Application();
        $application->setCreatedAt(new DateTime());
        $application->setPosting($posting);
        $application->setIdentity($identity);
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($application);
            $em->flush();
    
            $this->addFlash('success', "Candidature envoyé ");
        }

        if ($posting) {
            $ipAddress = $request->getClientIp();
            $viewRepository = $em->getRepository(PostingViews::class);
            $existingView = $viewRepository->findOneBy([
                'posting' => $posting,
                'ipAdress' => $ipAddress,
            ]);
    
            if (!$existingView) {
                $view = new PostingViews();
                $view->setPosting($posting);
                $view->setIpAdress($ipAddress);
    
                $em->persist($view);
                $posting->addView($view);
                $em->flush();
            }
        }

        return $this->render('posting/index.html.twig', [
            'posting' => $posting,
            'company' => $posting->getCompagny(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/posting/enable/{jobId}', name: 'app_posting_enable')]
    public function enable(
        Posting $posting,
        EntityManagerInterface $em,
        UserService $userService
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $identity = $userService->getCurrentIdentity();
        if($posting->isValid()){
            $posting->setValid(false);
            $message = "Fermer";
        }else{
            $posting->setValid(true);
            $message = "Ouvrir";
        }
        $em->persist($posting);
        $em->flush();

        return $this->json([
            'message' => $message,
        ], 200);
    }

    #[Route('/posting/boost/{jobId}', name: 'app_posting_boost')]
    public function boost(
        Request $request,
        Posting $posting,
        EntityManagerInterface $em,
        UserService $userService
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $identity = $userService->getCurrentIdentity();

        return $this->json([
            'message' => 'Tarif, ajout badge urgent sur les annonces',
        ], 200);
    }

    #[Route('/postings/{slug}', name: 'app_posting_sector')]
    public function sector(
        Sector $sector, 
        PostingRepository $postingRepository, 
        SectorRepository $sectorRepository,
        Request $request
    ): Response
    {
        $offset = $request->query->get('offset', 0);

        return $this->render('home/index.html.twig', [
            'postings' => $postingRepository->findBySector($sector->getId(), 10, $offset),
            'sectors' => $sectorRepository->findAll(),
        ]);
    }
}
