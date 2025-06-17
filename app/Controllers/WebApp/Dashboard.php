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
        return $this->templateObject->loadPage('feed', ['pageTitle' => 'Dashboard']);
    }

}