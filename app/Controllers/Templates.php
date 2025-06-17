<?php

namespace App\Controllers;

class Templates extends BaseController
{
    /**
     * Check if the user is logged in
     * 
     * @return bool
     */
    public function user_loggedin() {
        $session = session();
        if(empty($session->get('user_id')) && empty($session->get('user_loggedin'))) {
            return false;
        }
        return true;
    }

    /**
     * Global variables for the templates
     * 
     * @return array
     */
    public function globalVariables() {
        return [
            'baseUrl' => getenv('baseURL') . 'public',
            'userLoggedin' => $this->user_loggedin(),
            'appName' => 'WhisperNet - Hyperlocal Social Network',
        ];
    }

    /**
     * Load the header template
     * 
     * @return void
     */
    public function loadHeader() {
        return view('templates/header', $this->globalVariables());
    }

    /**
     * Load the footer template
     * 
     * @return void
     */
    public function loadFooter() {
        return view('templates/footer', $this->globalVariables());
    }

    /**
     * Load a page
     * 
     * @param string $page
     * @param array $data
     * @return void
     */
    public function loadPage($page, $data = []) {
        echo $this->loadHeader();
        echo view($page, $data);
        echo $this->loadFooter();
    }

    /**
     * Load the 404 page
     * 
     * @return void
     */
    public function load404Page() {
        echo $this->loadHeader();
        echo view('errors/404');
        echo $this->loadFooter();
    }
}