<?php

namespace App\Service\Cart;

use App\Repository\IdentityRepository;
use App\Repository\PackNameRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(
        private RequestStack $requestStack,
        private PackNameRepository $pack,
        private IdentityRepository $identityRepository
    )
    {
        $this->requestStack = $requestStack;
        $this->pack = $pack;
        $this->identityRepository = $identityRepository;
    }

    public function add(int $id): void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $cart[$id] = 1;

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function remove(int $id): void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if(!empty($cart[$id])){
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function getFullCart(): array
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $fullCart = [];
        foreach ($cart as $id => $quantity) {
            $fullCart[] = [
                'identity' => $this->identityRepository->find($id),
                'quantity' => $quantity
            ];
        }

        return $fullCart;
    }

    public function getTotal(): float
    {
        $total = 0;
        $pack = $this->pack->find(0);
        $price = !is_null($pack) ? $pack->getPrice() : 99;
        foreach ($this->getFullCart() as $item) {
            $total += $price * $item['quantity'];
        }

        return $total / 100; 
    }

    public function getCount(): int
    {
        $count = 0;
        foreach ($this->getFullCart() as $item) {
            $count ++;
        }

        return $count; 
    }
}