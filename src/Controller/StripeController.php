<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Repository\PackNameRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }


    #[Route('/create-session-stripe', name: 'app_stripe_create_session')]
    public function createCheckoutSession(Request $request): Response
    {
        $stripe = new StripeClient('sk_test_51MKofmKp6gApFus8kcy3FoWI6d6mo8v7zPg9JY6L65ScWUnTE4bSQO7YMyuup3qH6aEgwUwedsoXheaUU6xLhmWe00QYTJWK4i');

        $data = $request->request->all();        
        $product = $data['product'] ?? 'Postin Expert PRO';
        $quantity = $data['quantity'] ?? 1;
        $price = $data['price'] ?? 100;
        $success = $data['success'] ?? '/success';
        $cancel = $data['cancel'] ?? '/cancel';

        $checkout_session = $stripe->checkout->sessions->create([
            'customer_email' => $this->getUser()->getUserIdentifier(),
            'mode' => 'payment',
            'line_items' => [
                [
                    'quantity' => $quantity,
                    'price_data' => [
                        'currency' => 'EUR',
                        'product_data' => [
                            'name' => $product
                        ],
                        'unit_amount' => $price
                    ],
                ]
            ],
            'success_url' => 'https://'.$_SERVER['HTTP_HOST'].$success,
            'cancel_url' => 'https://'.$_SERVER['HTTP_HOST'].$cancel,
        ]);

        return $this->redirect($checkout_session->url);
    }

    #[Route('/success', name: 'app_stripe_success')]
    public function success(PackNameRepository $packNameRepository, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var Identity $identity */
        $identity = $user->getIdentity();

        /** @var PackName $packName */
        $packName = $packNameRepository->findBy(['id' => 1]);
        $name = $packName[0]->getName();
        
        /** @var Pack $pack */
        $pack = new Pack();
        $pack
            ->setCreatedAt(new DateTime())
            ->setPackName($packName[0])
            ->setIdentity($user->getIdentity())
            ->setName($name)
        ;
        $em->persist($pack);
        $em->flush();

        return $this->render('stripe/index.html.twig', [
            'success' => true,
        ]);
    }

    #[Route('/cancel', name: 'app_stripe_cancel')]
    public function cancel(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'success' => false,
        ]);
    }
}
