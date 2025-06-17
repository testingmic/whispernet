<?php

namespace App\Controllers;

use App\Controllers\Templates;

class WebAppController extends BaseController
{
 
    protected $templateObject;
    protected $session;

    /**
     * Constructor
     * 
     * @param array $payload
     * @return void
     */
    public function __construct($payload = [])
    {
        $this->session = session();
        $this->templateObject = new Templates();
    }

}
