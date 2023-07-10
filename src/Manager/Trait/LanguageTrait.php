<?php

namespace App\Manager\Trait;

use App\Entity\Language;

trait LanguageTrait
{
    public $LABELS = [
        Language::LEVEL_BASIC => 'I write clearly in this language',
        Language::LEVEL_CONVERSATIONNAL => 'I write and speak clearly in this language' ,
        Language::LEVEL_FLUENT => 'I write and speak this language to high level' ,
        Language::LEVEL_NATIVE => 'I write and speak perfectly in this language' ,
    ];

    public function getRealFileName($label): string
    {
        if (isset($this->LABELS[$label]))
            return $this->LABELS[$label];
        return $label;
    }
}