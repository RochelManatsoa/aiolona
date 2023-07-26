<?php

namespace App\Manager\Trait;

use App\Entity\Compagny;

trait CompanyTrait
{
    public $LABELS = [
        Compagny::SIZE_XSMALL => '1 to 49',
        Compagny::SIZE_SMALL => '50 to 149',
        Compagny::SIZE_MEDIUM => '150 to 249',
        Compagny::SIZE_LARGE => '250 to 499',
        Compagny::SIZE_XLARGE => '500 +',
    ];

    public function getRealFileName($label): string
    {
        if (isset($this->LABELS[$label]))
            return $this->LABELS[$label];
        return $label;
    }
}