<?php

namespace App\Components;

use App\Entity\Posting;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('simple_posting')]
class SimplePostingComponent
{
    public Posting $posting;
}

