<?php

namespace App\Controller;

use App\Entity\Commande;
use DateTime;
use App\Entity\Pack;
use Stripe\StripeClient;
use App\Manager\CommandeManager;
use App\Entity\StripeTransaction;
use App\Repository\PackNameRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Manager\StripeTransactionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Stripe\StripeApi as StripeStripeApi;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/payment', name: 'app_stripe_payment')]
    public function payment(
        StripeStripeApi $stripeApi,
        Request $request,
        CommandeManager $commandeManager
    ): Response
    {
        $response = $stripeApi->startPayment($request, $commandeManager);

        return new RedirectResponse($response);
    }

    #[Route('/webhook/stripe', name: 'app_stripe_response')]
    public function stripeWebhookAction(
        StripeTransactionManager $stripeTransactionManager,
        CommandeManager $commandeManager
    ): JsonResponse
    {
        
        // $endpoint_secret = $_ENV['STRIPE_WEBHOOK_SECRET_KEY'];
        $endpoint_secret = 'whsec_2fdc95c60df6a37f0bdd6deea58867148cb7b32ff144a9d301024620192192f8';
        $payload = file_get_contents('php://input');
        $event = json_decode($payload);
        \Stripe\Stripe::setApiKey($endpoint_secret);

        switch ($event->type) {
            case 'charge.succeeded':
                $charge = $event->data->object;
                $transaction = $stripeTransactionManager->getOrCreate($charge->payment_intent);
                $transaction
                    ->setIntentId($charge->payment_intent)
                    ->setPaymentMethod($charge->payment_method)
                    ->setReceiptUrl($charge->receipt_url)
                    ;
                $stripeTransactionManager->save($transaction);

                echo 'Charge avec transaction ' . $charge->payment_intent ;
                break;
                
            case 'payment_intent.created':
                $paymentIntent = $event->data->object;
                $transaction = $stripeTransactionManager->getByIntent($paymentIntent->id);
                if(!$transaction instanceof StripeTransaction){
                    echo 'payment_intent.created sans transaction ' . $paymentIntent->id ;
                    break;
                }
                $transaction->setCustomerId($paymentIntent->client_secret);
                $stripeTransactionManager->save($transaction);

                echo 'Transaction mis à jour  ' . $transaction->getIntentId(). ' - Status : ' . $paymentIntent->status;
                break;
                
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $transaction = $stripeTransactionManager->getByIntent($paymentIntent->id);
                if(!$transaction instanceof StripeTransaction){
                    echo 'payment_intent.succeeded sans transaction ' . $paymentIntent->id ;
                    break;
                }
                $transaction
                    ->setCustomerId($paymentIntent->client_secret)
                    ->setStatus($paymentIntent->status)
                    ;
                $stripeTransactionManager->save($transaction);

                echo 'Transaction créé  ' . $transaction->getIntentId() . ' - Status : ' .$paymentIntent->status. ' - ';
                break;
                
            case 'checkout.session.completed':
                $session = $event->data->object;
                $commande = $commandeManager->getById($session->metadata->order_id);
                $commande
                    ->setPaymentIntent($session->payment_intent)
                    ->setStatus(Commande::STATUS_SUCCEEDED)
                    ;
                $commandeManager->save($commande);
                $transaction = $stripeTransactionManager->getOrCreate($session->payment_intent);
                $transaction
                    ->setIntentId($session->payment_intent)
                    ->setAmount($session->amount_total)
                    ->setCurrency($session->currency)
                    ->setCommande($commande)
                    ;
                $stripeTransactionManager->save($transaction);

                echo 'Session complète, commande n°' . $session->metadata->order_id . ' - ';
                break;
                
            default:
                echo 'Fin  ' . $event->type . ' - ';
                break;
        }

        http_response_code(200);

        return new JsonResponse(['status' => 'success']);
    }
}
