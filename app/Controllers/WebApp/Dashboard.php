<?php 

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Dashboard extends WebAppController {

    /**
     * Index for the dashboard
     * 
     * @return void
     */
    public function index() {

        if(!user_loggedin()) {
            return $this->templateObject->loadPage('setup/login', ['pageTitle' => 'Account Login']);
        }

        return $this->templateObject->loadPage('dashboard', ['pageTitle' => 'Dashboard']);
    }

}