<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Templates;

class WebAppController extends BaseController
{
 
    protected $templateObject;
    protected $session;
    protected $loogedUserId;

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

        // set the logged in user id
        $this->loogedUserId = $this->session->get('user_id');
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

        // set the logged in user id
        $this->loogedUserId = $this->session->get('user_id');

        return true;
    }

    /**
     * Verify if the user is logged in
     * 
     * @return bool
     */
    public function verifyLogin() {
        if($this->user_loggedin()) return true;
        $this->templateObject->loadPage('setup/login', ['pageTitle' => 'Login', 'footerHidden' => true, 'userLoggedIn' => false]);
        exit;
    }

}
