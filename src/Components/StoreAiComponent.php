<?php

namespace App\Components;

use App\Entity\AIcores;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('store_ai')]
class StoreAiComponent
{
    public AIcores $aicore;
}

