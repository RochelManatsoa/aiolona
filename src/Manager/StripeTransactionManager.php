<?php

namespace App\Manager;

use Twig\Environment as Twig;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\StripeTransaction;
use App\Repository\StripeTransactionRepository;
use DateTime;

class StripeTransactionManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private StripeTransactionRepository $stripeTransactionRepository
    )
    {
        $this->em = $em;
        $this->stripeTransactionRepository = $stripeTransactionRepository;
    }

    public function init()
    {
        $stripeTransaction = new StripeTransaction();
        $stripeTransaction->setCreatedAt(new DateTime());
        $stripeTransaction->setStatus(StripeTransaction::STATUS_PENDING);

        return $stripeTransaction;
    }

    public function save(StripeTransaction $stripeTransaction)
    {
        $this->em->persist($stripeTransaction);
        $this->em->flush();
    }

    public function getByIntent(string $intent)
    {
        $stripeTransaction = $this->stripeTransactionRepository->findOneBy([
            'intentId' => $intent
        ]);

        return $stripeTransaction;
    }
}