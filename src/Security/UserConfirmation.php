<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserConfirmation implements UserCheckerInterface
{
    public function __construct(
        private RequestStack $requestStack,
    )
    {
        
    }
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (!$user->isIsVerified()) {
            $message = "Votre compte n'est pas vérifié, merci de le confirmer avant le {$user->getTokenLifeTime()->format('d M Y à H:i')}";
            $this->requestStack->getSession()->getFlashBag()->add('warning', $message);
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException("Votre compte n'est pas vérifié, merci de le confirmer avant le {$user->getTokenLifeTime()->format('d M Y à H:i')}");
        }
    }
}