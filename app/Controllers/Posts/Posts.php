<?php
namespace App\Controllers\Posts;

use App\Controllers\LoadController;

class Posts extends LoadController {

    /**
     * List posts
     * 
     * @return array
     */
    public function list() {
        
        return $this->postsModel->list();

    }

    /**
     * Create a post
     * 
     * @return array
     */
    public function create() {
        
        return $this->postsModel->create();

    }

    /**
     * View a post
     * 
     * @return array
     */
    public function view() {
        
        return $this->postsModel->view();
    }

    /**
     * Update a post
     * 
     * @return array
     */
    public function update() {

        return $this->postsModel->updateRecord();

    }

    /**
     * Delete a post
     * 
     * @return array
     */
    public function delete() {

        return $this->postsModel->deleteRecord();
        
    }

    /**
     * Get nearby posts
     * 
     * @return array
     */
    public function nearby() {

        return $this->postsModel->nearby();
    }

    /**
     * Vote on a post
     * 
     * @return array
     */
    public function vote() {

        return $this->postsModel->vote();

    }

    /**
     * Get trending posts
     * 
     * @return array
     */
    public function trending() {

        return $this->postsModel->trending();

    }
    
} 