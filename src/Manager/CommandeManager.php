<?php

namespace App\Manager;

use DateTime;
use App\Entity\Commande;
use App\Entity\CommandeItems;
use App\Repository\CommandeRepository;
use App\Repository\IdentityRepository;
use App\Service\User\UserService;
use Twig\Environment as Twig;
use Doctrine\ORM\EntityManagerInterface;

class CommandeManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private IdentityRepository $identityRepository,
        private CommandeRepository $commandeRepository,
        private UserService $userService
    )
    {
        $this->em = $em;
        $this->identityRepository = $identityRepository;
        $this->commandeRepository = $commandeRepository;
        $this->userService = $userService;
    }

    public function init()
    {
        $commande = new Commande();
        $commande->setCreatedAt(new DateTime());
        $commande->setStatus(Commande::STATUS_OPEN);
        $commande->setIdentity($this->userService->getCurrentIdentity());

        return $commande;
    }

    public function save(Commande $commande)
    {
        $this->em->persist($commande);
        $this->em->flush();
    }

    public function saveJson($json, $total)
    {
        $commande = $this->init();
        foreach ($json as $key => $value) {
            $cartItem = new CommandeItems();
            $cartItem->setProfiles($this->identityRepository->find($key));
            $cartItem->setQuantity($value);
            $cartItem->setUnitPrice(99);
            $cartItem->setCommande($commande);
            $this->em->persist($cartItem);
            $commande->addItem($cartItem);
        }
        $commande->setTotalAmount($total);
        $this->save($commande);

        return $commande->getId();
    }

    public function getById(int $id)
    {
        $commande = $this->commandeRepository->findOneBy([
            'id' => $id
        ]);

        if(!$commande instanceof Commande){
            $commande = $this->init();
        }

        return $commande;
    }
}