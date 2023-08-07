<?php

namespace App\Data;

class SearchPostData
{
    /**
     * @var null|string
     */
    public $q = '';
    
    /**
     * @var null|string
     */
    public $l = '';


    /**
     * @var Sector[]
     */
    public $sectors = [];

    /**
     * @var AiCores[]
     */
    public $aicores = [];

}