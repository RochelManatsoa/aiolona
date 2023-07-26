<?php

namespace App\Manager;

use DateTime;
use App\Entity\Posting;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityManagerInterface;

use function PHPUnit\Framework\isEmpty;

class PostingManager
{
    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    public function init($compagny){
        $posting = new Posting();
        $posting
        ->setCreatedAt(new DateTime())
        ->setValid(false)
        ->setJobId(new Uuid(Uuid::v1()))
        ->setCompagny($compagny)
        ;

        return $posting;
    }

    public function save(Posting $posting)
    {
		$this->em->persist($posting);
        $this->em->flush();
    }

    public function saveForm(Form $form)
    {
        $posting = $form->getData();
        $posting->setUpdatedAt(new DateTime());
        $this->save($posting);

        return $posting;
    }

    public function checkInfo(Posting $posting) : bool
    {
        if(is_null($posting->getDesctiption())){
            return false;
        }

        if(is_null($posting->getSector())){
            return false;
        }
        
        if(is_null($posting->getTarif())){
            return false;
        }
        
        if(is_null($posting->getNumber())){
            return false;
        }
        
        if($posting->getSchedulePostings()->isEmpty()){
            return false;
        }
        
        if(is_null($posting->getTypePosting())){
            return false;
        }

        return true;
    }


}