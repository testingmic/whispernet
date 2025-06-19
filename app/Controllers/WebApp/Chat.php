<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Chat extends WebAppController {
    
    public function index() {
        $chats = [[], [], [], [], [], []];
        return $this->templateObject->loadPage('chat', ['pageTitle' => 'Chat', 'chats' => $chats, 'favicon_color' => 'chat']);
    }

}