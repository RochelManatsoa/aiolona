<?php

namespace App\Components;

use App\Entity\Identity;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('simple_card')]
class SimpleCardComponent
{
    public string $url;
    public string $imgUrl;
    public array $stars;
    public Identity $identity;
}

