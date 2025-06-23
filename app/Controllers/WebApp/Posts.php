<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Posts extends WebAppController {
    
    /**
     * Index
     * 
     * @return array
     */
    public function index() {
        return $this->templateObject->loadPage('feed', ['pageTitle' => 'Feed']);
    }

    /**
     * Create a post
     * 
     * @return array
     */
    public function create() {
        return $this->templateObject->loadPage('create', ['pageTitle' => 'Feed']);
    }

    /**
     * View posts by tag
     * 
     * @param string $tag
     * @return array
     */
    public function tags($tag = null) {
        return $this->templateObject->loadPage('tags', ['pageTitle' => 'Tags', 'tag' => $tag]);
    }

    /**
     * View a post
     * 
     * @param string $postId
     * @return array
     */
    public function view($postId = null) {
        return $this->templateObject->loadPage('post', ['pageTitle' => 'Feed', 'postId' => $postId, 'footerHidden' => true]);
    }

}