<?php
namespace App\Controllers\Tags;

use Exception;
use App\Controllers\LoadController;

class Tags extends LoadController {

    /**
     * Create tag
     * 
     * @return array
     */
    public function createTag() {
        return $this->tagsModel->createTag($this->payload['name']);
    }

    /**
     * Add tag to post
     * 
     * @return array
     */
    public function addTagToPost() {
        return $this->tagsModel->addTagToPost($this->payload['postId'], $this->payload['tagName']);
    }

    /**
     * Remove tag from post
     * 
     * @return array
     */
    public function removeTagFromPost() {
        return $this->tagsModel->removeTagFromPost($this->payload['postId'], $this->payload['tagId']);
    }

    /**
     * Get post tags
     * 
     * @return array
     */
    public function getPostTags() {
        return $this->tagsModel->getPostTags($this->payload['postId']);
    }

    /**
     * Get posts by tag
     * 
     * @return array
     */
    public function getPostsByTag() {
        return $this->tagsModel->getPostsByTag($this->payload['tagName'], $this->payload['page'], $this->payload['limit']);
    }

    /**
     * Get popular tags
     * 
     * @return array
     */
    public function getPopularTags() {
        return $this->tagsModel->getPopularTags($this->payload['limit']);
    }

    /**
     * Search tags
     * 
     * @return array
     */
    public function searchTags() {
        return $this->tagsModel->searchTags($this->payload['query'], $this->payload['limit']);
    }

    /**
     * Delete tag
     * 
     * @return array
     */
    public function deleteTag() {
        return $this->tagsModel->deleteTag($this->payload['tagId']);
    }

    /**
     * Get related tags
     * 
     * @return array
     */
    public function getRelatedTags() {
        return $this->tagsModel->getRelatedTags($this->payload['tagId'], $this->payload['limit']);
    }
} 