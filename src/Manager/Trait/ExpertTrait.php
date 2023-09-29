<?php

namespace App\Manager\Trait;

use App\Entity\Expert;

trait ExpertTrait
{
    public $LABELS = [
        Expert::YEAR_SMALL => '50 to 149',
        Expert::YEAR_MEDIUM => '150 to 249',
        Expert::YEAR_LARGE => '250 to 499',
        Expert::YEAR_XLARGE => '250 to 499',
    ];

    public function getRealFileName($label): string
    {
        if (isset($this->LABELS[$label]))
            return $this->LABELS[$label];
        return $label;
    }
}