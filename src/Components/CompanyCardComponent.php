<?php

namespace App\Components;

use App\Entity\Identity;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('company_card')]
class CompanyCardComponent
{
    public string $url;
    public string $add;
    public string $imgUrl;
    public array $stars;
    public array $unlocked;
    public Identity $identity;
}

