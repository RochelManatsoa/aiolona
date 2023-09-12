<?php

namespace App\Manager;

use App\Entity\Account;
use DateTime;
use App\Entity\Compagny;
use App\Entity\Identity;
use Twig\Environment as Twig;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Form\Form;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;

class IdentityManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private Twig $twig,
        private SluggerInterface $sluggerInterface,
        private AccountRepository $accountRepository,
        private RequestStack $requestStack,
        private Security $security
    )
    {
        
    }

    public function init()
    {
        $identity = new Identity();
        $identity->setCreatedAt(new DateTime());
        $identity->setAccount($this->getTypologie());
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

    private function getTypologie(): ?Account
    {
        $typology = $this->requestStack->getSession()->get('typology');
        $account = $this->accountRepository->findOneBy([ 'slug' => $typology]);
        if($account instanceof Account) return $account;
        return null;
    }
}