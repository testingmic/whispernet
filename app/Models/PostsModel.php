<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class PostsModel extends Model {

    public $payload = [];
    protected $table;
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
                        ->select("p.*, u.username, u.profile_image")
                        ->join('users u', 'p.user_id = u.user_id')
                        ->where('p.user_id', $this->payload['userId'])
                        ->orderBy('p.created_at DESC')
                        ->limit($this->payload['limit'])
                        ->offset($offset);

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
            $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
            $this->db->query($sql, [$this->payload['postId'], $this->payload['userId'], $this->payload['content']]);

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
    public function viewComments($postId) {
        try {
            $sql = "SELECT c.*, u.username, u.profile_image 
                    FROM comments c 
                    INNER JOIN users u ON c.user_id = u.user_id 
                    WHERE c.post_id = ?
                    ORDER BY c.created_at DESC";
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
            $sql = "SELECT p.*, u.username, u.profile_image 
                    FROM posts p 
                    INNER JOIN users u ON p.user_id = u.user_id 
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
     * Get trending posts
     * 
     * @return array
     */
    public function trending() {
        try {

            $offset = ($this->payload['offset'] - 1) * $this->payload['limit'];
            $hours = $this->payload['hours'] ?? 1;
            
            $posts = $this->db->table('posts p')
                        ->select("p.*, u.username, u.profile_image, (p.upvotes - p.downvotes) as score")
                        ->join('users u', 'p.user_id = u.user_id')
                        ->where('p.created_at >=', date('Y-m-d H:i:s', strtotime("-{$hours} hours")))
                        ->orderBy('score DESC, p.created_at DESC')
                        ->limit($this->payload['limit'])
                        ->offset($offset);

            if(!empty($this->payload['location'])) {
                $posts->like('p.city', $this->payload['location'], 'both');
            }

            return $posts->get()->getResultArray();

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
                        ->select("p.*, u.username, u.profile_image,
                           (6371 * acos(cos(radians({$this->payload['latitude']})) * cos(radians(latitude)) * 
                            cos(radians(longitude) - radians({$this->payload['longitude']})) + 
                            sin(radians({$this->payload['latitude']})) * sin(radians(latitude)))) AS distance")
                        ->join('users u', 'p.user_id = u.user_id')
                        ->where('distance <= ', $this->payload['radius'])
                        ->orderBy('distance, p.created_at DESC')
                        ->limit($this->payload['limit'])
                        ->offset($offset);

            if(!empty($this->payload['location'])) {
                $posts->like('p.city', $this->payload['location'], 'both');
            }

            return $posts->get()->getResultArray();
            
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

            if (!in_array($this->payload['voteType'], ['up', 'down'])) {
                throw new DatabaseException('Invalid vote type');
            }

            $column = $this->payload['voteType'] === 'up' ? 'upvotes' : 'downvotes';
            $sql = "UPDATE {$table} SET {$column} = {$column} + 1 WHERE {$whereColumn} = ?";
            $this->db->query($sql, [$this->payload['postId']]);

            return true;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }
    
}
?>