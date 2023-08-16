<?php

namespace App\Service\Stripe;

use Stripe\Stripe;
use App\Entity\User;
use App\Manager\CommandeManager;
use Psr\Log\LoggerInterface;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StripeApi
{
    const STRIPE_PAYMENT_SECRET_KEY = 'sk_test_51MKofmKp6gApFus8kcy3FoWI6d6mo8v7zPg9JY6L65ScWUnTE4bSQO7YMyuup3qH6aEgwUwedsoXheaUU6xLhmWe00QYTJWK4i'; 

    public function __construct(
        private string $secretKey, 
        private string $webhookSecret = '',
        private CommandeManager $commandeManager
    )
    {
        $this->commandeManager = $commandeManager;
        $this->secretKey = $secretKey;
        $this->webhookSecret = $webhookSecret;
        Stripe::setApiKey($secretKey);
        Stripe::setApiVersion('2022-11-15');
    }

    public function startPayment(Request $request)
    {
        $data = $request->request->all();        
        $product = $data['product'] ?? 'Postin Expert PRO';
        $quantity = $data['quantity'] ?? 1;
        $price = $data['price'] ?? 100;
        $total = $data['total'] ?? 0;
        $email = $data['email'] ?? '';
        $json = json_decode($data['json'], true) ?? '';
        $orderId = $data['orderid'] ?? '';
        $success = $data['success'] ?? '/success';
        $cancel = $data['cancel'] ?? '/cancel';

        $id = $this->commandeManager->saveJson($json, $total);

        $sessionStripe = Session::create([
            'customer_email' => $email,
            'mode' => 'payment',
            'line_items' => [
                [
                    'quantity' => $quantity,
                    'price_data' => [
                        'currency' => 'EUR',
                        'product_data' => [
                            'name' => $product,
                        ],
                        'unit_amount' => $price
                    ],
                ]
            ],
            'success_url' => 'https://'.$_SERVER['HTTP_HOST'].$success,
            'cancel_url' => 'https://'.$_SERVER['HTTP_HOST'].$cancel,
            'metadata' => [
                'order_id' => $id
            ],
        ]);

        // $params['customData'] = $sessionStripe->id;

        return $sessionStripe->url;
    }
}