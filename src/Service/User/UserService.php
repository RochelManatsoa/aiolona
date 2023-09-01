<?php

namespace App\Service\User;

use App\Entity\Compagny;
use App\Entity\Identity;
use App\Repository\CommandeItemsRepository;
use App\Repository\CommandeRepository;
use App\Repository\IdentityRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class UserService
{
    public function __construct(
        private Security $security,
        private IdentityRepository $identityRepository,
        private UserRepository $userRepository,
        private CommandeRepository $commandeRepository,
        private CommandeItemsRepository $itemsRepository,
    )
    {
        $this->security = $security;
        $this->identityRepository = $identityRepository;
        $this->userRepository = $userRepository;
        $this->commandeRepository = $commandeRepository;
        $this->itemsRepository = $itemsRepository;
    }

    public function getCurrentUser()
    {
        return $this->security->getUser();
    }

    public function getCurrentIdentity(): ?Identity
    {
        return $this->identityRepository->findOneBy(['user' => $this->security->getUser()]);
    }

    public function getAdminMails(): array
    {
        return $this->userRepository->findAdminEmails();
    }

    public function getProfilesUnlocked(): array
    {
        $profiles = [];
        $identities = [];
        
        if($this->getCurrentIdentity() instanceof Identity && $this->getCurrentIdentity()->getCompagny() instanceof Compagny){
            foreach ($this->getUserCommande() as $commande) {
                $items = $this->itemsRepository->findBy([
                    'commande' => $commande,
                ]);
                $identities = array_merge($identities, $items);
            }
            
            foreach ($identities as $identity) {
                $commandeItems = $identity->getProfiles();
                $profiles[] = $commandeItems;
            }
        }

        return $profiles;
    }

    public function getUserCommande(): array
    {
        return $this->commandeRepository->findBy(['identity' => $this->getCurrentIdentity()]);
    }
}