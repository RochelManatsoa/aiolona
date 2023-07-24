<?php

namespace App\Data;

class SeachData
{
    /**
     * @var null|string
     */
    public $q = '';

    /**
     * @var Sector[]
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
     * @var Language[]
     */
    public $langues = [];
}