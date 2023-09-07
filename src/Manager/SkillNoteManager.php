<?php
namespace App\Manager;

use App\Entity\Identity;
use App\Entity\SkillNote;
use App\Entity\TechnicalSkill;
use App\Repository\AINoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment as Twig;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class SkillNoteManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private Twig $twig,
        private SluggerInterface $sluggerInterface,
        private AINoteRepository $aINoteRepository,
        private Security $security
    )
    {
        
    }

    public function init(Identity $identity, TechnicalSkill $technicalSkill){ 
        $notes = $identity->getSkillNotes(); 
        if($notes->isEmpty()){
            $note = new SkillNote();
        }else{
            foreach($notes as $value){
                if($value->getTechnicalSkill() == $technicalSkill){
                    $note = $value;
                }else{
                    $note = new SkillNote();
                }
            }
        }
        $note->setIdentity($identity)->setTechnicalSkill($technicalSkill);

        return $note;
    }

    public function getNoteOrNull(Identity $identity, TechnicalSkill $technicalSkill){ 
        $notes = $identity->getSkillNotes(); 
        if($notes->isEmpty()){
            return 0;
        }
        
        foreach($notes as $value){
            if($value->getTechnicalSkill() == $technicalSkill){
                return $value->getNote();
            }
        }
        
        return 0;
    }
}