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
    private $em;
    private $twig;
    private $sluggerInterface;
    private $security;
    private $aINoteRepository;
    
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
        $notes = $identity->getNotes(); 
        if($notes->isEmpty()){
            $note = new Note();
        }else{
            foreach($notes as $value){
                if($value->getAiCore() == $aIcores){
                    $note = $value;
                }else{
                    $note = new Note();
                }
            }
        }
        $note->setIdentity($identity)->setAiCore($aIcores);

        return $note;
    }

    public function getNoteOrNull(Identity $identity, AIcores $aIcores){ 
        $notes = $identity->getNotes(); 
        if($notes->isEmpty()){
            return 0;
        }
        
        foreach($notes as $value){
            if($value->getAiCore() == $aIcores){
                return $value->getNote();
            }
        }
        
        return 0;
    }
}