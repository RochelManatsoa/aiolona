<?php

namespace App\Controller;

use App\Entity\Posting;
use App\Manager\PostingManager;
use App\Form\Posting\StepOneType;
use App\Form\Posting\StepTwoType;
use App\Form\Posting\StepThreeType;
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
        EntityManagerInterface $em
    ): Response
    {
        return $this->render('posting/index.html.twig', [
            'posting' => $posting,
        ]);
    }
}
