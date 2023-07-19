<?php

namespace App\Data;

class SeachData
{
    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Category[]
     */
    public $sectors = [];

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;

    /**
     * @var AiCores[]
     */
    public $aicores = [];

    /**
     * @var Languages[]
     */
    public $langues = [];
}