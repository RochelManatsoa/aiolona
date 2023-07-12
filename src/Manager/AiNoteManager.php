<?php
namespace App\Manager;

use App\Entity\AIcores;
use App\Entity\Identity;
use App\Entity\Note;
use App\Repository\AINoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment as Twig;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AiNoteManager
{
    public function __construct(
        EntityManagerInterface $em,
        Twig $twig,
        SluggerInterface $sluggerInterface,
        AINoteRepository $aINoteRepository,
        Security $security
    )
    {
        $this->em = $em;
        $this->twig = $twig;
        $this->sluggerInterface = $sluggerInterface;
        $this->security = $security;
        $this->aINoteRepository = $aINoteRepository;
    }

    public function init(Identity $identity, AIcores $aIcores){
        $note = new Note();
        $note->setIdentity($identity)->setAiCore($aIcores);

        return $note;
    }
}