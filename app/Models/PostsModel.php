<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class PostsModel extends Model {

    public $payload = [];
    protected $table;
    protected $votesDb;
    protected $notifDb;
    protected $viewsDb;
    protected $primaryKey = "post_id";

    public function __construct() {
        parent::__construct();
        
        $this->table = DbTables::$userTable;
        foreach(DbTables::initTables() as $key) {
            if (property_exists($this, $key)) {
                $this->{$key} = DbTables::${$key};
            }
        }
    }

    /**
     * Connect to the votes and comments databases
     * 
     * @param string $db
     * 
     * @return array
     */
    public function connectToDb($db = 'votes') {

        // if the database group is default, use the default database
        if(in_array(configs('db_group'), ['default'])) {
            $this->votesDb = $this->db;
            $this->notifDb = $this->db;
            $this->viewsDb = $this->db;
            return;
        }

        // connect to the votes and comments databases
        if($db == 'votes') {
            $this->votesDb = db_connect('votes');
            setDatabaseSettings($this->votesDb);
        }
        
        if($db == 'notification') {
            $this->notifDb = db_connect('notification');
            setDatabaseSettings($this->notifDb);
        }

        if($db == 'views') {
            $this->viewsDb = db_connect('views');
            setDatabaseSettings($this->viewsDb);
        }

        if($db == 'all') {
            $this->votesDb = db_connect('votes');
            $this->notifDb = db_connect('notification');
            $this->viewsDb = db_connect('views');
        }
    }

    /**
     * List posts
     * 
     * @return array
     */
    public function list() {

        try {

            $offset = ($this->payload['offset'] ?? 1 - 1) * $this->payload['limit'];

            $total = 0;

            $hasCommented = (bool) (($this->payload['request_data'] ?? '') == 'my_replies');
            $hasVoted = (bool) (($this->payload['request_data'] ?? '') == 'my_votes');
            $hasViewed = (bool) (($this->payload['request_data'] ?? '') == 'my_views');
            $isAuthor = (bool) (($this->payload['request_data'] ?? '') == 'my_posts');
            $isBookmarked = (bool) (($this->payload['request_data'] ?? '') == 'my_bookmarks');

            $userPosts = $this->db->table('posts p')
                        ->select("p.*, u.full_name, u.username as username, u.profile_image, m.media as post_media")
                        ->join('users u', 'p.user_id = u.user_id')
                        ->join('media m', 'p.post_id = m.record_id AND m.section = "posts"', 'left')
                        ->orderBy('p.post_id DESC')
                        ->limit($this->payload['limit'])
                        ->offset($offset);

            if(!empty($this->payload['previous_record_id'])) {
                $userPosts->where('p.post_id <', $this->payload['previous_record_id']);
            }
            elseif(!empty($this->payload['last_record_id'])) {
                $userPosts->where('p.post_id >', $this->payload['last_record_id']);
            }

            if($isAuthor) {
                $userPosts->where('p.user_id', $this->payload['userId']);
            }

            if($hasCommented) {
                $userPosts->where("p.post_id IN (SELECT post_id FROM comments WHERE user_id = {$this->payload['userId']})");
            }

            if($hasVoted) {
                $this->connectToDb('votes');
                $postIds = $this->votesDb->table('votes')
                    ->select('record_id')
                    ->where('user_id', $this->payload['userId'])
                    ->where('section', 'posts')
                    ->get()
                    ->getResultArray();
                
                $postIds = array_column($postIds, 'record_id');
                if(empty($postIds)) {
                    return [];
                }
                $userPosts->where("p.post_id IN (" . implode(',', $postIds) . ")");
            }

            if($isBookmarked) {
                $userPosts->where("p.post_id IN (SELECT post_id FROM bookmarks WHERE user_id = {$this->payload['userId']})");
            } else {
                
                // if the user is not viewing their own posts, only show their own posts
                $userPosts->where('p.user_id', $this->payload['userId']);

                // count the total number of posts
                $totalPosts = $this->db->table('posts p')->where('p.user_id', $this->payload['userId']);
                $total = $totalPosts->countAllResults();
            }

            if(!empty($this->payload['location'])) {
                $userPosts->like('p.city', $this->payload['location'], 'both');
                $totalPosts->like('p.city', $this->payload['location'], 'both');
            }

            $posts = $userPosts->get()->getResultArray();

            return [
                'posts' => $posts,
                'pagination' => [
                    'total' => $total ?? 0,
                    'page' => $this->payload['offset'] ?? 1,
                    'limit' => $this->payload['limit'],
                    'pages' => $total > 0 ? ceil($total / $this->payload['limit']) : 0
                ]
            ];
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }

    }

    /**
     * Create a post
     * 
     * @return array
     */
    public function create() {
        try {

            $sql = "INSERT INTO posts (user_id, content, city, country, media_url, media_type, latitude, longitude) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, [
                $this->payload['userId'], 
                $this->payload['content'], 
                $this->payload['city'] ?? null,
                $this->payload['country'] ?? null,
                $this->payload['mediaUrl'] ?? null, 
                $this->payload['mediaType'] ?? null, 
                $this->payload['latitude'] ?? null, 
                $this->payload['longitude'] ?? null
            ]);

            $postId = $this->db->insertID();

            return $postId;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Comment on a post
     * 
     * @return array
     */
    public function comment() {
        try {
            $sql = "INSERT INTO comments (post_id, user_id, content, city, country) VALUES (?, ?, ?, ?, ?)";
            $this->db->query($sql, [$this->payload['postId'], $this->payload['userId'], $this->payload['content'], $this->payload['city'] ?? null, $this->payload['country'] ?? null]);

            return $this->db->insertID();
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get the user ids of the comments on a post
     * 
     * @param int $postId
     * 
     * @return array
     */
    public function getCommentsUserIds($postId) {
        try {
            $userIds = $this->db->query("SELECT user_id FROM comments WHERE post_id = ?", [$postId])->getResultArray();
            return array_column($userIds, 'user_id') ?? [];
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * View comments
     * 
     * @return array
     */
    public function viewComments($postId = null, $column = 'post_id', $whereClause = '') {
        try {
            $sql = "SELECT c.*, u.username as username, u.profile_image 
                    FROM comments c 
                    INNER JOIN users u ON c.user_id = u.user_id 
                    WHERE c.{$column} = ? {$whereClause}
                    ORDER BY c.created_at ASC";
            $comments = $this->db->query($sql, [$postId])->getResultArray();

            return $comments;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * View a post
     * 
     * @return array
     */
    public function viewSingleComment($commentId) {
        try {
            $sql = "SELECT c.*, u.username, u.profile_image 
                    FROM comments c 
                    INNER JOIN users u ON c.user_id = u.user_id 
                    WHERE c.comment_id = ?";
            $comment = $this->db->query($sql, [$commentId])->getRowArray();

            if (!$comment) {
                return false;
            }

            $comment['ago'] = formatTimeAgo($comment['created_at']);
            return $comment;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update a column
     * 
     * @return array
     */
    public function updateCommentsCount($postId, $sign = "+") {
        try {
            $sql = "UPDATE posts SET comments_count = comments_count {$sign} 1 WHERE post_id = ?";
            $this->db->query($sql, [$postId]);
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Delete a post
     * 
     * @return array
     */
    public function deleteComment($commentId) {
        try {

            $this->db->query("DELETE FROM comments WHERE comment_id = ?", [$commentId]);

            if ($this->db->affectedRows() === 0) {
                return false;
            }

            return true;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * View a post
     * 
     * @return array
     */
    public function view() {
        try {
            $sql = "SELECT p.*, u.username, u.profile_image, m.media as post_media
                    FROM posts p 
                    INNER JOIN users u ON p.user_id = u.user_id 
                    LEFT JOIN media m ON p.post_id = m.record_id AND m.section = 'posts'
                    WHERE p.post_id = ?";
            $post = $this->db->query($sql, [$this->payload['postId']])->getRowArray();

            if (!$post) {
                return false;
            }

            return $post;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update a post
     * 
     * @return array
     */
    public function updateRecord() {
        try {

            $sql = "UPDATE posts SET content = ? WHERE post_id = ? AND user_id = ?";
            $this->db->query($sql, [$this->payload['content'], $this->payload['postId'], $this->payload['userId']]);

            if ($this->db->affectedRows() === 0) {
                throw new DatabaseException('Post not found or unauthorized');
            }

            return true;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update the raw pageviews of the post
     * 
     * @param int $postId
     * 
     * @return bool
     */
    public function updatePostViews($postId) {
        try {
            return $this->db->query("UPDATE posts SET pageviews = pageviews + 1 WHERE post_id = ?", [$postId]);
        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Update the raw pageviews of the post
     * 
     * @param array $postIds
     * 
     * @return bool
     */
    public function updateBulkPostViews($postIds) {
        try {
            return $this->db->query("UPDATE posts SET pageviews = pageviews + 1 WHERE post_id IN (" . implode(',', $postIds) . ")");
        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Delete a post
     * 
     * @return array
     */
    public function deleteRecord() {
        try {

            $sql = "DELETE FROM posts WHERE post_id = ? AND user_id = ?";
            $this->db->query($sql, [$this->payload['postId'], $this->payload['userId']]);

            if ($this->db->affectedRows() === 0) {
                return false;
            }

            // delete the post tags created for this post
            foreach(['post_tags', 'post_hashtags', 'comments', 'bookmarks'] as $table) {
                $this->db->query("DELETE FROM {$table} WHERE post_id = ?", [$this->payload['postId']]);
            }

            return true;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Bookmark a post
     * 
     * @return array
     */
    public function bookmark() {
        try {
            $sql = "INSERT INTO bookmarks (user_id, post_id) VALUES (?, ?)";
            $this->db->query($sql, [$this->payload['userId'], $this->payload['postId']]);
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Unbookmark a post
     * 
     * @return array
     */
    public function unbookmark() {
        try {
            $sql = "DELETE FROM bookmarks WHERE user_id = ? AND post_id = ?";
            $this->db->query($sql, [$this->payload['userId'], $this->payload['postId']]);
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Check if a post is bookmarked
     * 
     * @return array
     */
    public function checkBookmark() {
        try {
            $sql = "SELECT * FROM bookmarks WHERE user_id = ? AND post_id = ?";
            $bookmark = $this->db->query($sql, [$this->payload['userId'], $this->payload['postId']])->getRowArray();
            return $bookmark;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Load trending posts
     * 
     * @return array
     */
    public function loadTrending($offset, $hours) {
        
        $posts = $this->db->table('posts p')
                    ->select("p.*, u.full_name, u.username as username, u.profile_image, (p.upvotes - p.downvotes) as score, m.media as post_media")
                    ->join('users u', 'p.user_id = u.user_id')
                    ->join('media m', 'p.post_id = m.record_id AND m.section = "posts"', 'left')
                    ->where('p.created_at >=', date('Y-m-d H:i:s', strtotime("-{$hours} hours")))
                    ->orderBy('score DESC, p.created_at DESC')
                    ->limit($this->payload['limit'])
                    ->offset($offset);

        if(!empty($this->payload['location'])) {
            $posts->like('p.city', $this->payload['location'], 'both');
        }

        return $posts->get()->getResultArray();

    }

    /**
     * Get trending posts
     * 
     * @return array
     */
    public function trending() {
        try {

            // set the offset and hours
            $offset = ($this->payload['offset'] - 1) * $this->payload['limit'];
            $hours = $this->payload['hours'] ?? 1;
            
            // load posts from the last 1 hour
            $initPosts = $this->loadTrending($offset, $hours);

            if(!empty($initPosts)) {
                return $initPosts;
            }

            // if no posts are found, load posts from the last 6 hours
            $posts = $this->loadTrending($offset, $hours * 6);

            return $posts;

        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get nearby posts
     * 
     * @return array
     */
    public function nearby() {

        try {

            $hasCommented = (bool) (($this->payload['request_data'] ?? '') == 'my_replies');
            $hasVoted = (bool) (($this->payload['request_data'] ?? '') == 'my_votes');
            $hasViewed = (bool) (($this->payload['request_data'] ?? '') == 'my_views');
            $isAuthor = (bool) (($this->payload['request_data'] ?? '') == 'my_posts');

            // if the user has commented, voted, or viewed a post, set the radius to 100km
            if($hasCommented || $hasVoted || $hasViewed) {
                $this->payload['radius'] = 100;
            }

            $isMySQL = in_array(configs('db_group'), ['default']);

            $whereClause = $isMySQL ? "(6371 * acos(cos(radians({$this->payload['latitude']})) * cos(radians(latitude)) * 
                            cos(radians(longitude) - radians({$this->payload['longitude']})) + 
                            sin(radians({$this->payload['latitude']})) * sin(radians(latitude))))" : "distance";

            $offset = ($this->payload['offset'] - 1) * $this->payload['limit'];
            $posts = $this->db->table('posts p')
                        ->select("p.*, u.full_name, u.username as username, u.profile_image,
                           (6371 * acos(cos(radians({$this->payload['latitude']})) * cos(radians(latitude)) * 
                            cos(radians(longitude) - radians({$this->payload['longitude']})) + 
                            sin(radians({$this->payload['latitude']})) * sin(radians(latitude)))) AS distance,
                            m.media as post_media, b.bookmark_id as is_bookmarked")
                        ->join('users u', 'p.user_id = u.user_id')
                        ->join('media m', 'p.post_id = m.record_id AND m.section = "posts"', 'left')
                        ->join('bookmarks b', 'p.post_id = b.post_id AND b.user_id = ' . $this->payload['userId'], 'left')
                        ->where("{$whereClause} <= ", $this->payload['radius'])
                        ->orderBy('p.post_id DESC')
                        ->limit($this->payload['limit'])
                        ->offset($offset);

            if($isAuthor) {
                $posts->where('p.user_id', $this->payload['userId']);
            }

            if($hasCommented) {
                $posts->where("p.post_id IN (SELECT post_id FROM comments WHERE user_id = {$this->payload['userId']})");
            }

            if($hasVoted) {
                $this->connectToDb('votes');
                $postIds = $this->votesDb->table('votes')
                    ->select('record_id')
                    ->where('user_id', $this->payload['userId'])
                    ->where('section', 'posts')
                    ->get()
                    ->getResultArray();
                
                $postIds = array_column($postIds, 'record_id');
                if(empty($postIds)) {
                    return [];
                }
                $posts->where("p.post_id IN (" . implode(',', $postIds) . ")");
            }

            if(!empty($this->payload['location'])) {
                $posts->like('p.city', $this->payload['location'], 'both');
            }

            if(!empty($this->payload['previous_record_id'])) {
                $posts->where('p.post_id <', $this->payload['previous_record_id']);
            }
            elseif(!empty($this->payload['last_record_id'])) {
                $posts->where('p.post_id >=', $this->payload['last_record_id']);
            }

            $query = $posts->get();

            return $query->getResultArray();
            
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Vote on a post
     * 
     * @return array
     */
    public function vote($table = 'posts', $whereColumn = 'upvotes') {
        try {

            if (!in_array($this->payload['direction'], ['up', 'down'])) {
                throw new DatabaseException('Invalid vote type');
            }

            $column = $this->payload['direction'] === 'up' ? 'upvotes' : 'downvotes';
            $sql = "UPDATE {$table} SET {$column} = {$column} + 1 WHERE {$whereColumn} = ?";
            $this->db->query($sql, [$this->payload['recordId']]);

            return true;
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Reduce votes
     * 
     * @param string $recordId
     * @param string $table
     * @param string $column
     * 
     * @return array
     */
    public function reduceVotes($recordId, $table, $column, $whereColumn) {
        try {
            $sql = "UPDATE {$table} SET {$column} = {$column} - 1 WHERE {$whereColumn} = {$recordId}";
            $this->db->query($sql);

            return true;
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Record votes
     * 
     * @return array
     */
    public function recordVotes($recordId, $userId, $section, $direction) {
        try {
            $sql = "INSERT INTO votes (record_id, user_id, section, direction) VALUES (?, ?, ?, ?)";
            $this->votesDb->query($sql, [$recordId, $userId, $section, $direction]);
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get votes
     * 
     * @param string $recordId
     * @param string $section
     * @param string $userId
     * 
     * @return array
     */
    public function getVotes($recordId, $userId, $section) {
        try {
            $sql = "SELECT * FROM votes WHERE record_id = ? AND user_id = ? AND section = ?";
            $votes = $this->votesDb->query($sql, [$recordId, $userId, $section])->getRowArray();
            return $votes;
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get bulk votes
     * 
     * @param array $recordIds
     * @param int $userId
     * @param string $section
     * 
     * @return array
     */
    public function getBulkVotes($recordIds, $userId, $section) {
        try {
            $sql = "SELECT vote_id, record_id, direction FROM votes WHERE record_id IN (" . implode(',', $recordIds) . ") AND user_id = ? AND section = ?";
            $votes = $this->votesDb->query($sql, [$userId, $section])->getResultArray();

            foreach($votes as $key => $vote) {
                $list[$vote['record_id']]['voted'] = $vote['direction'];
            }

            return $list ?? [];
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Remove a vote
     * 
     * @param string $recordId
     * @param string $userId
     * @param string $section
     * 
     * @return array
     */
    public function removeVote($recordId, $userId, $section) {
        try {
            $sql = "DELETE FROM votes WHERE record_id = ? AND user_id = ? AND section = ?";
            $this->votesDb->query($sql, [$recordId, $userId, $section]);
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Notify a user
     * 
     * @param int $recordId
     * @param int $userId
     * @param string $type
     * @param string $section
     * @param string $content
     * 
     * @return array
     */
    public function notify($recordId, $userId, $type, $section, $content) {
        try {
            $sql = "INSERT INTO notifications (user_id, type, section, reference_id, content) VALUES (?, ?, ?, ?, ?)";
            $this->notifDb->query($sql, [$userId, $type, $section, $recordId, $content]);
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Delete votes
     * 
     * @param int $voteId
     * 
     * @return array
     */
    public function deleteVotes($voteId) {
        try {
            $sql = "DELETE FROM votes WHERE vote_id = ?";
            $this->votesDb->query($sql, [$voteId]);
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Check votes
     * 
     * @param int $recordId
     * @param int $userId
     * @param string $section
     * 
     * @return array
     */
    public function checkVotes($recordId, $userId, $section) {
        try {
            $sql = "SELECT * FROM votes WHERE record_id = ? AND user_id = ? AND section = ?";
            $vote = $this->votesDb->query($sql, [$recordId, $userId, $section])->getRowArray();
            
            return $vote;
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Check bulk views
     * 
     * @param array $postIds
     * @param int $userId
     * @param string $section
     * 
     * @return array
     */
    public function checkBulkViews($postIds, $userId, $section) {
        try {
            $views = $this->viewsDb->table('views')
                ->whereIn('record_id', $postIds)
                ->where('user_id', $userId)
                ->where('section', $section)
                ->get()
                ->getResultArray();
            return $views;
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Check views
     * 
     * @param string $recordId
     * @param string $userId
     * @param string $section
     * 
     * @return array
     */
    public function checkViews($recordId, $userId, $section) {
        try {
            $sql = "SELECT * FROM views WHERE record_id = ? AND user_id = ? AND section = ?";
            $view = $this->viewsDb->query($sql, [$recordId, $userId, $section])->getRowArray();
            return $view;
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Record view
     * 
     * @param string $recordId
     * @param string $userId
     * @param string $section
     * 
     * @return array
     */
    public function recordView($recordId, $userId, $section) {
        try {
            // record the view
            $sql = "INSERT INTO views (record_id, user_id, section) VALUES (?, ?, ?)";
            $this->viewsDb->query($sql, [$recordId, $userId, $section]);

            // update the views count
            $this->db->query("UPDATE posts SET views = views + 1 WHERE post_id = ?", [$recordId]);

            // return true
            return true;
        } catch (DatabaseException $e) {
            return [];
        }
    }
    
}
?>