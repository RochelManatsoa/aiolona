<?php

namespace App\Manager;

use App\Entity\Posting;
use DateTime;
use Symfony\Component\Uid\Uuid;

class PostingManager
{
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
}