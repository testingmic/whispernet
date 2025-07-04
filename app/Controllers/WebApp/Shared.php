<?php 

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;
use App\Controllers\Posts\Posts;

class Shared extends WebAppController {
    
    /**
     * View a shared post
     * 
     * @return array
     */
    public function index() {
        return $this->posts();
    }

    /**
     * View a shared post
     * 
     * @param string $post_uuid
     * @param int $post_id
     * 
     * @return array
     */
    public function posts($post_uuid = null, $post_id = null) {
        
        $notFound = false;

        // if the post uuid or post id is empty, then the post is not found
        if(empty($post_uuid) || empty($post_id)) {
            $notFound = true;
        }

        // if the post uuid and post id are not empty, then we need to check if the post is valid
        if(!$notFound) {
            $postObject = new Posts();
            $postObject->payload['postId'] = $post_id;
            $postObject->byPassLogin = true;
            $post = $postObject->view()['data'] ?? [];

            // if the post uuid is not set, then the post is not found
            if(empty($post['post_uuid'])) {
                $notFound = true;
            } else if($post_uuid !== $post['post_uuid']) {
                $notFound = true;
            }
        }

        if($notFound) {
            return $this->templateObject->load404Page('Post', true);
        }

        // get the post
        return $this->templateObject->loadPage('shared_post', [
            'pageTitle' => 'Shared Post', 
            'sharedPost' => true,
            'pgDesc' => $post['content'],
            'postUUID' => $post_uuid,
            'postId' => $post_id, 
            'footerHidden' => true, 
            'loadFeed' => true,
            'post' => $post
        ]);

    }
    
}