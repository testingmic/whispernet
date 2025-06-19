<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Upgrades extends WebAppController {
    
    public function index() {
        $upgrades = [[], [], [], [], [], []];
        return $this->templateObject->loadPage('upgrades', ['pageTitle' => 'Upgrades', 'upgrades' => $upgrades, 'favicon_color' => 'chat']);
    }


}