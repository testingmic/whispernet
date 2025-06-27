<?php
namespace App\Controllers\Posts;

use App\Controllers\LoadController;
use App\Controllers\Media\Media;

use App\Libraries\Routing;

class Posts extends LoadController {

    public $addComments = true;
    public $justCreated = false;

    /**
     * List posts
     * 
     * @return array
     */
    public function list() {

        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // make the call to the posts model
        $posts = $this->postsModel->list();

        return Routing::success(formatPosts($posts['posts'], false, $this->payload['userId']), $posts['pagination']);

    }

    /**
     * Create a post
     * 
     * @return array
     */
    public function create() {

        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // check if the content or media is required
        if(empty($this->payload['content']) && empty($this->payload['file_uploads'])) {
            return Routing::error('Content or media is required');
        }

        // make the call to the posts model
        $postId = $this->postsModel->create();

        $this->payload['postId'] = $postId;

        // upload the media files if any
        if(!empty($this->payload['file_uploads'])) {
            $media = new Media();
            $media->uploadMedia('posts', $postId, $this->currentUser['user_id'], $this->payload['file_uploads']);
        }

        // set the just created flag
        $this->justCreated = true;

        // increment the posts count
        $this->currentUser['statistics']['posts'] = ($this->currentUser['statistics']['posts'] ?? 0) + 1;

        // update the statistics
        $this->usersModel->db->table('users')->where('user_id', $this->currentUser['user_id'])->update(['statistics' => json_encode($this->currentUser['statistics'])]);

        // extract the hashtags
        $hashtags = extractHashtags($this->payload['content']);

        // insert the hashtags
        if(!empty($hashtags)) {

            $existingTags = $this->tagsModel->getHashtagsByList($hashtags);
            $existingIds = array_column($existingTags, 'id');
            $existingNames = array_column($existingTags, 'name');

            $noneExistingHashtags = array_diff($hashtags, $existingNames);
            $hashIds = $this->tagsModel->createhashtags($noneExistingHashtags, configs('is_local'));
            $allHashIds = array_merge($existingIds, $hashIds);
            $this->tagsModel->createposthashtags($postId, $allHashIds);
        }
        
        // connect to the votes database
        $this->postsModel->connectToDb('views');
        
        // record the view
        $this->postsModel->recordView($postId, $this->currentUser['user_id'], 'posts');

        // return the post id
        return Routing::created(['data' => 'Post created successfully', 'record' => $this->view()['data']]);

    }

    /**
     * View comments
     * 
     * @return array
     */
    public function comments() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;

        $whereClause = !empty($this->payload['postId']) ? "AND c.post_id = {$this->payload['postId']}" : "";
        
        // make the call to the posts model
        if(!empty($this->payload['postId'])) {
            $comments = $this->postsModel->viewComments($this->payload['postId']);
        } else {
            $comments = $this->postsModel->viewComments($this->payload['userId'], 'user_id', $whereClause);
        }

        // format the comments
        foreach($comments as $key => $comment) {
            $comments[$key]['manage'] = [
                'delete' => (bool)($comment['user_id'] == $this->payload['userId']),
            ];
            $comments[$key]['comment_id'] = (int)$comment['comment_id'];
            $comments[$key]['ago'] = formatTimeAgo($comment['created_at']);
        }

        return Routing::success($comments);
    }

    /**
     * View comments
     * 
     * @return array
     */
    public function viewComments() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // make the call to the posts model
        $comments = $this->postsModel->viewComments($this->payload['postId']);

        foreach($comments as $key => $comment) {
            $comments[$key]['manage'] = [
                'delete' => (bool)($comment['user_id'] == $this->payload['userId']),
            ];
            $comments[$key]['ago'] = formatTimeAgo($comment['created_at']);
        }

        return Routing::success($comments);
    }

    /**
     * View a comment
     * 
     * @return array
     */
    public function viewSingleComment() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;

        // if the comment id is not set, return an error
        if(empty($this->payload['commentId'])) {
            return Routing::error('Comment ID is required');
        }

        // make the call to the posts model
        $comment = $this->postsModel->viewSingleComment($this->payload['commentId']);

        if(empty($comment)) {
            return false;
        }
        $comment['ago'] = formatTimeAgo($comment['created_at']);
        $comment['manage'] = [
            'delete' => (bool)($comment['user_id'] == $this->payload['userId']),
        ];

        // linkify the comment content
        $comment['content'] = linkifyContent($comment['content']);

        $comment['comment_id'] = (int)$comment['comment_id'];

        return Routing::success($comment);
    }

    /**
     * Delete a comment
     * 
     * @return array
     */
    public function deletecomment() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // make the call to the posts model
        $comment = $this->viewSingleComment();

        // if the comment is not deleted, return not found
        if(empty($comment['data']['created_at'])) {
            return Routing::success('Comment already deleted.');
        }

        $this->postsModel->deleteComment($this->payload["commentId"]);

        // update the comments count
        $this->postsModel->updateCommentsCount($comment['data']['post_id'], "-");

        // increment the comments count
        $this->currentUser['statistics']['comments'] = ($this->currentUser['statistics']['comments'] ?? 0) - 1;

        // update the statistics
        $this->usersModel->db->table('users')->where('user_id', $this->payload['userId'])->update(['statistics' => json_encode($this->currentUser['statistics'])]);

        // return the success message
        return Routing::success('Comment deleted successfully');
    }

    /**
     * Comment on a post
     * 
     * @return array
     */
    public function comment() {

        // disable adding comments
        $this->addComments = false;

        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;

        // make the call to the posts model
        $postCheck = $this->postsModel->view();

        if(!isset($postCheck['created_at'])) {
            return Routing::notFound();
        }

        // make the call to the posts model
        $commentId = $this->postsModel->comment();

        // return the comment id
        $this->payload['commentId'] = (int)$commentId;

        // update the comments count
        $this->postsModel->updateCommentsCount($this->payload['postId']);

        // increment the comments count
        $this->currentUser['statistics']['comments'] = ($this->currentUser['statistics']['comments'] ?? 0) + 1;

        // update the statistics
        $this->usersModel->db->table('users')->where('user_id', $this->payload['userId'])->update(['statistics' => json_encode($this->currentUser['statistics'])]);

        // connect to the notification database
        $this->postsModel->connectToDb('notification');

        // if the owner is not the same as the user who commented
        if($postCheck['user_id'] !== $this->payload['userId']) {
            // notify the owner of the post
            $this->postsModel->notify(
                $this->payload['postId'], $postCheck['user_id'], 'comment',
                'posts', "@{$this->currentUser['username']} left a comment on your post \"". substr($this->payload['content'], 0, 40) . '...\"'
            );
        }

        // get the user ids of the comments on the post
        $commentUserIds = $this->postsModel->getCommentsUserIds($this->payload['postId']);

        // if there are comments on the post
        if(!empty($commentUserIds)) {
            // notify the users who have commented on the post
            foreach($commentUserIds as $userId) {
                if(!in_array($userId, [$this->payload['userId'], $postCheck['user_id']])) {
                    $this->postsModel->notify($this->payload['postId'], $userId, 'comment', 'posts', "@{$this->currentUser['username']} left a comment on the post \"". substr($this->payload['content'], 0, 40) . '...\"');
                }
            }
        }

        // return the comment id
        return Routing::created(['data' => 'Comment created successfully', 'record' => $this->viewSingleComment()['data']]);

    }

    /**
     * Notify a user
     * 
     * @return array
     */
    public function notify() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        
        // make the call to the posts model
        
    }

    /**
     * Bookmark a post
     * 
     * @return array
     */
    public function bookmark() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;

        // make the call to the posts model
        $post = $this->postsModel->view();

        if(empty($post)) {
            return Routing::notFound();
        }

        // check if the post is bookmarked
        $checkBookmark = $this->postsModel->checkBookmark();

        // if the post is bookmarked, unbookmark it
        if(!empty($checkBookmark)) {
            $this->postsModel->unbookmark();
            return Routing::success('Post unbookmarked successfully', 'Save Post');
        }
        
        // make the call to the posts model
        $this->postsModel->bookmark();

        // increment the bookmarks count
        return Routing::success('Post bookmarked successfully', 'Remove Post');
    }

    /**
     * View bookmarked posts
     * 
     * @return array
     */
    public function bookmarked() {

        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;
        $this->postsModel->payload['request_data'] = 'my_bookmarks';
        
        // make the call to the posts model
        $posts = $this->postsModel->list();

        return Routing::success(formatPosts($posts, false, $this->payload['userId']));
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

        if($this->addComments) {
            $post['comments'] = $this->postsModel->viewComments($post['post_id']);
            foreach($post['comments'] as $key => $comment) {
                $post['comments'][$key]['content'] = linkifyContent($comment['content']);
                $post['comments'][$key]['manage'] = [
                    'delete' => (bool)($comment['user_id'] == $this->payload['userId']),
                    'report' => (bool)($comment['user_id'] !== $this->payload['userId']),
                    'save' => (bool)($comment['user_id'] !== $this->payload['userId']),
                ];
                $post['comments'][$key]['comment_id'] = (int)$comment['comment_id'];
                $post['comments'][$key]['ago'] = formatTimeAgo($comment['created_at']);
            }
        }

        // if the post is not just created, record the view
        if(!$this->justCreated) {

            // update the raw post views
            $this->postsModel->updatePostViews($post['post_id']);

            // connect to the votes database
            $this->postsModel->connectToDb('views');

            // check if the user has already viewed the post
            $view = $this->postsModel->checkViews($post['post_id'], $this->payload['userId'], 'posts');
            if(empty($view)) {
                // record the view
                $this->postsModel->recordView($post['post_id'], $this->payload['userId'], 'posts');

                // increment the views count
                $post['views'] = $post['views'] + 1;
            }
        }

        return Routing::success(formatPosts([$post], true, $this->payload['userId']));
        
    }

    /**
     * Mark posts as seen
     * 
     * @return array
     */
    public function mark_as_seen() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;

        // connect to the votes database
        $this->postsModel->connectToDb('views');

        // get the post ids
        $postIds = explode(',', $this->payload['posts']);
        $ids = [];
        foreach($postIds as $postId) {
            $ids[] = (int)$postId;
        }

        // if there are no post ids, return success
        if(empty($ids)) {
            return Routing::success('No posts to mark as seen');
        }

        // check if the user has already viewed the post
        $view = $this->postsModel->checkBulkViews($ids, $this->payload['userId'], 'posts');

        // get the post ids that the user has not viewed
        $postIds = array_diff($ids, array_column($view, 'record_id'));

        if(!empty($postIds)) {
            // record the views
            foreach($postIds as $postId) {
                // record the view
                $this->postsModel->recordView($postId, $this->payload['userId'], 'posts');
            }
            // update the raw post views
            $this->postsModel->updateBulkPostViews($postIds);
        }
        
        // make the call to the posts model
        return Routing::success('Posts marked as seen');
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
        $deleted = $this->postsModel->deleteRecord();

        if(!$deleted) {
            return Routing::notFound();
        }

        return Routing::success('Post deleted successfully');

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

        return Routing::success(formatPosts($posts, false, $this->payload['userId']));

    }

    /**
     * Remove a vote
     * 
     * @return array
     */
    public function removevote() {
        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;

        // connect to the votes database
        $this->postsModel->connectToDb('votes');

        // get the user id
        $userId = $this->currentUser['user_id'];
        $section = $this->payload['section'];

        // get the votes
        $votes = $this->postsModel->getVotes($this->payload['recordId'], $userId, $section);

        if(empty($votes)) {
            // if the user has not voted, return an error
            return Routing::error('You have not voted on this ' . substr($section, 0, -1));
        }

        // make the call to the posts model
        $this->postsModel->removeVote($this->payload['recordId'], $userId, $section);

        // get the column
        $column = $section == "posts" ? "post_id" : "comment_id";
        $direction = $votes['direction'] == 'up' ? 'upvotes' : 'downvotes';

        // record the vote
        $this->postsModel->reduceVotes($this->payload['recordId'], $section, $direction, $column);

        // get the post votes
        return Routing::success('Vote removed successfully on the ' . substr($section, 0, -1));
    }

    /**
     * Vote on a post
     * 
     * @return array
     */
    public function vote() {

        // disable adding comments
        $this->addComments = false;

        // connect to the votes database
        $this->postsModel->connectToDb('votes');

        // check if the section is valid
        if(!empty($this->payload['section'])) {
            if(!in_array($this->payload['section'], ['posts', 'comments'])) {
                return Routing::error('Invalid section');
            }
        }

        if(!in_array($this->payload['direction'], ['up', 'down'])) {
            return Routing::error('Invalid direction');
        }

        // set the section to posts if not set
        $section = $this->payload['section'] ?? 'posts';
        $column = $section == "posts" ? "post_id" : "comment_id";

        $firstTime = true;

        // get the user id
        $userId = $this->currentUser['user_id'];

        // get the record
        $theRecord = $this->postsModel->db->query("SELECT * FROM {$section} WHERE {$column} = ?", [$this->payload['recordId']])->getRowArray();
        if(empty($theRecord)) {
            return Routing::notFound();
        }

        // check if the user has already voted
        $vote = $this->postsModel->checkVotes($this->payload['recordId'], $userId, $this->payload['section']);
        if(!empty($vote)) {

            $firstTime = false;
            if($vote['direction'] == $this->payload['direction']) {
                return Routing::error("You have already voted in the {$this->payload['direction']} direction");
            }
            $this->postsModel->deleteVotes($vote['vote_id']);

            // get the opposite direction
            $oppositeDirection = $this->payload['direction'] == 'up' ? 'downvotes' : 'upvotes';

            // record the vote
            $this->postsModel->reduceVotes($this->payload['recordId'], $section, $oppositeDirection, $column);
        }

        // set the payload to the posts model
        $this->postsModel->payload = $this->payload;

        // update the votes count
        $this->postsModel->recordVotes($this->payload['recordId'], $userId, $section, $this->payload['direction']);

        // make the call to the posts model
        $this->postsModel->vote($section, $column);

        // get the post votes
        $votes = $this->postsModel->db->query("SELECT downvotes, upvotes FROM {$section} WHERE {$column} = ?", [$this->payload['recordId']])->getRowArray();

        // if the user has voted for the first time and the owner is not the same as the user
        if($firstTime && ((int)$theRecord['user_id'] !== (int)$userId)) {

            // connect to the notification database
            $this->postsModel->connectToDb('notification');
            $item = $section == 'posts' ? 'post' : 'comment';

            // notify the owner of the post or comment
            $this->postsModel->notify(
                $this->payload['recordId'], $theRecord['user_id'], 'vote',
                $section, "@{$this->currentUser['username']} liked your {$item}"
            );

            // increment the votes count
            $this->currentUser['statistics']['votes'] = ($this->currentUser['statistics']['votes'] ?? 0) + 1;

            // update the statistics
            $this->usersModel->db->table('users')->where('user_id', $userId)->update(['statistics' => json_encode($this->currentUser['statistics'])]);
        }

        // return the votes
        return Routing::created(['data' => 'Vote successful', 'record' => $votes]);

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

        return Routing::success(formatPosts($posts, false, $this->payload['userId']));

    }
    
} 