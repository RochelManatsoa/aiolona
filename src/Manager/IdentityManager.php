<?php

namespace App\Manager;

use DateTime;
use App\Entity\Compagny;
use App\Entity\Identity;
use Twig\Environment as Twig;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Form\Form;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class IdentityManager
{
    private $em;
    private $twig;
    private $sluggerInterface;
    private $accountRepository;
    private $security;

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
        $identity->setUsername(new Uuid(Uuid::v1()));
        $identity->setAvatar("https://www.jea.com/cdn/images/avatar-gray.png");
        
        return $identity;
    }

    public function createCompagny($identity)
    {
        $compagny = new Compagny();
        $compagny->setIdentity($identity);

        return $compagny;
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