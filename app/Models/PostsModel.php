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
     * List posts
     * 
     * @return array
     */
    public function list() {

        try {

            $offset = ($this->payload['offset'] - 1) * $this->payload['limit'];

            $userPosts = $this->db->table('posts p')
                        ->select("p.*, u.full_name as username, u.profile_image, m.media as post_media")
                        ->join('users u', 'p.user_id = u.user_id')
                        ->join('media m', 'p.post_id = m.record_id AND m.section = "posts"', 'left')
                        ->where('p.user_id', $this->payload['userId'])
                        ->orderBy('p.created_at DESC')
                        ->limit($this->payload['limit'])
                        ->offset($offset);

            if(!empty($this->payload['previous_record_id'])) {
                $userPosts->where('p.post_id <', $this->payload['previous_record_id']);
            }
            elseif(!empty($this->payload['last_record_id'])) {
                $userPosts->where('p.post_id >', $this->payload['last_record_id']);
            }

            $totalPosts = $this->db->table('posts p')->where('p.user_id', $this->payload['userId']);

            if(!empty($this->payload['location'])) {
                $userPosts->like('p.city', $this->payload['location'], 'both');
                $totalPosts->like('p.city', $this->payload['location'], 'both');
            }

            $total = $totalPosts->countAllResults();
            $posts = $userPosts->get()->getResultArray();

            return [
                'posts' => $posts,
                'pagination' => [
                    'total' => $total,
                    'page' => $this->payload['offset'] ?? 1,
                    'limit' => $this->payload['limit'],
                    'pages' => ceil($total / $this->payload['limit'])
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

            $sql = "DELETE FROM comments WHERE comment_id = ?";
            $this->db->query($sql, [$commentId]);

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
                    INNER JOIN media m ON p.post_id = m.record_id AND m.section = 'posts'
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

            return true;
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
                    ->select("p.*, u.full_name as username, u.profile_image, (p.upvotes - p.downvotes) as score, m.media as post_media")
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

            $offset = ($this->payload['offset'] - 1) * $this->payload['limit'];
            $posts = $this->db->table('posts p')
                        ->select("p.*, u.full_name as username, u.profile_image,
                           (6371 * acos(cos(radians({$this->payload['latitude']})) * cos(radians(latitude)) * 
                            cos(radians(longitude) - radians({$this->payload['longitude']})) + 
                            sin(radians({$this->payload['latitude']})) * sin(radians(latitude)))) AS distance,
                            m.media as post_media")
                        ->join('users u', 'p.user_id = u.user_id')
                        ->join('media m', 'p.post_id = m.record_id AND m.section = "posts"', 'left')
                        ->where("(6371 * acos(cos(radians({$this->payload['latitude']})) * cos(radians(latitude)) * 
                            cos(radians(longitude) - radians({$this->payload['longitude']})) + 
                            sin(radians({$this->payload['latitude']})) * sin(radians(latitude)))) <= ", $this->payload['radius'])
                        ->orderBy('distance, p.post_id DESC')
                        ->limit($this->payload['limit'])
                        ->offset($offset);

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
     * Connect to the votes and comments databases
     * 
     * @param string $db
     * 
     * @return array
     */
    public function connectToDb($db = 'votes') {

        // if the database group is production, use the default database
        if(configs('db_group') == 'production') {
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
     * Notify a user
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