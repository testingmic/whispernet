<?php
namespace App\Controllers\Posts;

use App\Controllers\LoadController;

use App\Libraries\Routing;

class Posts extends LoadController {

    /**
     * List posts
     * 
     * @return array
     */
    public function list() {

        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // make the call to the posts model
        return $this->postsModel->list();

    }

    /**
     * Create a post
     * 
     * @return array
     */
    public function create() {

        // generate random longitude and latitude for Accra, Ghana
        $location = generateRandomLocationAndSentence();
        $this->payload['longitude'] = $location['longitude'];
        $this->payload['latitude'] = $location['latitude'];
        $this->payload['city'] = $location['city'];

        $this->payload['content'] .= ' ' . $location['sentence'] . " ({$this->payload['longitude']}, {$this->payload['latitude']})";

        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // make the call to the posts model
        $postId = $this->postsModel->create();

        $this->payload['postId'] = $postId;
        
        // return the post id
        return Routing::created(['data' => 'Post created successfully', 'record' => $this->view()['data']]);

    }

    /**
     * View a post
     * 
     * @return array
     */
    public function view() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // make the call to the posts model
        $post = $this->postsModel->view();

        if(empty($post)) {
            return Routing::notFound();
        }

        return Routing::success(formatPosts([$post], true));
        
    }

    /**
     * Update a post
     * 
     * @return array
     */
    public function update() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // make the call to the posts model
        return $this->postsModel->updateRecord();

    }

    /**
     * Delete a post
     * 
     * @return array
     */
    public function delete() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // make the call to the posts model
        return $this->postsModel->deleteRecord();
        
    }

    /**
     * Get nearby posts
     * 
     * @return array
     */
    public function nearby() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // make the call to the posts model
        $posts = $this->postsModel->nearby();

        return Routing::success(formatPosts($posts));

    }

    /**
     * Vote on a post
     * 
     * @return array
     */
    public function vote() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // make the call to the posts model
        return $this->postsModel->vote();

    }

    /**
     * Get trending posts
     * 
     * @return array
     */
    public function trending() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // make the call to the posts model
        $posts = $this->postsModel->trending();

        return Routing::success(formatPosts($posts));

    }
    
} 