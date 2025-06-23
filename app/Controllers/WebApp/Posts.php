<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;
use App\Models\TagsModel;

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
    public function tags($searchQuery = null) {

        // initialize the tags model
        $tagsModel = new TagsModel();

        // get the popular tags
        $popularTags = $tagsModel->getPopularHashtags();

        // get the posts count for the search query
        if(!empty($searchQuery)) {
            foreach($popularTags as $tag) {
                if($tag['name'] == $searchQuery) {
                    $postsCount = $tag['usage_count'];
                }
            }
        }

        if(!empty($postsCount)) {
            // get the posts by tag
            $posts = $tagsModel->getPostsListByHashtag($searchQuery);
            $postsList = formatPosts($posts);
        }

        return $this->templateObject->loadPage('tags', [
            'pageTitle' => 'Tags', 
            'postsList' => $postsList ?? [],
            'popularTags' => $popularTags,
            'searchQuery' => $searchQuery,
            'postsCount' => $postsCount ?? 0,
        ]);
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