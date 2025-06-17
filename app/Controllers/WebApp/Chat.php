<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Chat extends WebAppController {
    
    public function index() {
        return $this->templateObject->loadPage('chat', ['pageTitle' => 'Chat']);
    }

}