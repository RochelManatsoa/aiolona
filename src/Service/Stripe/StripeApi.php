<?php

namespace App\Service\Stripe;

use Stripe\Stripe;
use App\Manager\CommandeManager;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Request;

class StripeApi
{
    public function __construct(
        private CommandeManager $commandeManager,
        private string $secretKey, 
        private string $webhookSecret = ''
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

        return $sessionStripe->url;
    }
}