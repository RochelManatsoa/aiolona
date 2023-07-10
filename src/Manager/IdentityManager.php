<?php

namespace App\Manager;

use DateTime;
use App\Entity\Identity;
use Twig\Environment as Twig;
use Symfony\Component\Form\Form;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class IdentityManager
{
    public function __construct(
        EntityManagerInterface $em,
        Twig $twig,
        SluggerInterface $sluggerInterface,
        AccountRepository $accountRepository,
        Security $security
    )
    {
        $this->em = $em;
        $this->twig = $twig;
        $this->sluggerInterface = $sluggerInterface;
        $this->security = $security;
        $this->accountRepository = $accountRepository;
    }

    public function init()
    {
        $identity = new Identity();
        $identity->setCreatedAt(new DateTime());
        $identity->setAvatar("https://www.jea.com/cdn/images/avatar-gray.png");
        $identity->setUser($this->security->getUser());
        $identity->setFirstName($this->security->getUser()->getFirstName());
        $identity->setLastName($this->security->getUser()->getLastName());
        $identity->setUsername($this->sluggerInterface->slug(strtolower($this->security->getUser()->getLastName().'-'.$this->security->getUser()->getFirstName().'-'.$this->security->getUser()->getId())));
        $identity->setAccount($this->accountRepository->findOneBySomeField(2));

        return $identity;
    }

    public function save(Identity $identity)
    {
		$this->em->persist($identity);
        $this->em->flush();
    }

    public function saveForm(Form $form)
    {
        $identity = $form->getData();
        $this->save($identity);

        return $identity;

    }
}