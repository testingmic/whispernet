<?php

namespace App\Controllers\Votes;

use App\Controllers\LoadController;
use App\Models\PostsModel;

class Votes extends LoadController {

    /**
     * Get the votes for a list of posts
     * 
     * @param array $postsIds
     * @param int $userId
     * @param string $section
     * 
     * @return array
     */
    public function getVotes($posts, $userId, $section) {

        // set the payload to the posts model
        $this->postsModel = new PostsModel();

        // get the posts ids
        $postsIds = array_column($posts, 'post_id');

        // if there are no posts, return the posts
        if(empty($postsIds)) {
            return $posts;
        }

        // connect to the votes database
        $this->postsModel->connectToDb('votes');

        // get the user votes on the posts
        $votesList = $this->postsModel->getBulkVotes($postsIds, $userId, $section);
        
        // update the posts with the votes direction
        if(!empty($votesList)) {
            foreach($posts as $key => $post) {
                $posts[$key]['voted'] = $votesList[$post['post_id']]['voted'] ?? false;
            }
        }

        return $posts;

    }

}

