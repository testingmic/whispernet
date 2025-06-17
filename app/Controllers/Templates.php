<?php

namespace App\Controllers;

class Templates extends BaseController
{
    /**
     * Global variables for the templates
     * 
     * @return array
     */
    public function globalVariables() {
        return [
            'baseUrl' => getenv('baseURL') . 'public',
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

    public function load404Page() {
        // return view('errors/html/error_404');
        echo $this->loadHeader();
        echo view('errors/404');
        echo $this->loadFooter();
    }
}