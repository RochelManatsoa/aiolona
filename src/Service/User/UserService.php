<?php

namespace App\Service\User;

use App\Entity\Identity;
use App\Repository\IdentityRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class UserService
{
    public function __construct(
        private Security $security,
        private IdentityRepository $identityRepository,
    )
    {
        $this->security = $security;
        $this->identityRepository = $identityRepository;
    }

    public function getCurrentUser()
    {
        return $this->security->getUser();
    }

    public function getCurrentIdentity(): ?Identity
    {
        return $this->identityRepository->findOneBy(['user' => $this->security->getUser()]);
    }
}