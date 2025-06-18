<?php

namespace App\Controllers;

use App\Controllers\BaseController;
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

    /**
     * Check if the user is logged in
     * 
     * @return bool
     */
    public function user_loggedin() {
        $this->session = session();
        
        if(empty($this->session->get('user_id')) && empty($this->session->get('user_loggedin'))) {
            return false;
        }

        return true;
    }

}
