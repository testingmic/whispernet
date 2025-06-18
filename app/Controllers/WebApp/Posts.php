<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Posts extends WebAppController {
    
    public function index() {
        return $this->templateObject->loadPage('feed', ['pageTitle' => 'Feed']);
    }

    public function create() {
        return $this->templateObject->loadPage('create', ['pageTitle' => 'Feed']);
    }

    public function view($postId = null) {
        return $this->templateObject->loadPage('post', ['pageTitle' => 'Feed', 'postId' => $postId]);
    }

}