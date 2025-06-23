<?php
namespace App\Controllers\Tags;

use Exception;
use App\Controllers\LoadController;
use App\Libraries\Routing;

class Tags extends LoadController {

    /**
     * Get popular hashtags
     * 
     * @return array
     */
    public function popular() {
        $popularTags = $this->tagsModel->getPopularHashtags();
        return Routing::success($popularTags);
    }

    /**
     * Get posts list by hashtag
     * 
     * @param string $hashtag
     * 
     * @return array
     */
    public function posts() {
        if(empty($this->payload['hashtag'])) {
            return Routing::error('Hashtag is required');
        }
        $posts = $this->tagsModel->getPostsListByHashtag($this->payload['hashtag']);
        return Routing::success(formatPosts($posts));
    }

    /**
     * Get posts list by hashtag
     * 
     * @param string $hashtag
     * 
     * @return array
     */
    public function postsbyid() {

        if(empty($this->payload['tag_id'])) {
            return Routing::error('Tag ID is required');
        }

        $posts = $this->tagsModel->getPostsListByHashtag($this->payload['tag_id'], 'id');
        return Routing::success(formatPosts($posts));
    }

} 