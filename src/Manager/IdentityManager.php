<?php

namespace App\Manager;

use App\Entity\Identity;
use App\Repository\AccountRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment as Twig;

class IdentityManager
{
    public function __construct(
        EntityManagerInterface $em,
        Twig $twig,
        AccountRepository $accountRepository,
        Security $security
    )
    {
        $this->em = $em;
        $this->twig = $twig;
        $this->security = $security;
        $this->accountRepository = $accountRepository;
    }

    public function init()
    {
        $identity = new Identity();
        $identity->setCreatedAt(new DateTime());
        $identity->setAvatar("https://www.jea.com/cdn/images/avatar-gray.png");
        $identity->setUser($this->security->getUser());
        $identity->setAccount($this->accountRepository->findOneBySomeField(2));

        return $identity;
    }

    public function save(Identity $identity)
    {
		$this->em->persist($identity);
        $this->em->flush();
    }
}