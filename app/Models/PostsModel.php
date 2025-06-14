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
            $sql = "SELECT p.*, u.username, u.profile_image 
                    FROM posts p 
                    INNER JOIN users u ON p.user_id = u.user_id 
                    WHERE p.user_id = ? 
                    ORDER BY p.created_at DESC 
                    LIMIT ? OFFSET ?";
            $posts = $this->db->query($sql, [$this->payload['userId'], $this->payload['limit'], $offset])->getResultArray();

            $sql = "SELECT COUNT(*) FROM posts WHERE user_id = ?";
            $total = $this->db->query($sql, [$this->payload['userId']])->getRowArray()['COUNT(*)'];

            return [
                'success' => true,
                'posts' => $posts,
                'pagination' => [
                    'total' => $total,
                    'page' => $this->payload['page'],
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

            $sql = "INSERT INTO posts (user_id, content, media_url, media_type, latitude, longitude) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, [$this->payload['userId'], $this->payload['content'], $this->payload['mediaUrl'], $this->payload['mediaType'], $this->payload['latitude'], $this->payload['longitude']]);

            $postId = $this->db->insertID();

            return $postId;
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
                throw new DatabaseException('Post not found');
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
                throw new DatabaseException('Post not found or unauthorized');
            }

            return true;
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
            $sql = "SELECT p.*, u.username, u.profile_image,
                           (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                            cos(radians(longitude) - radians(?)) + 
                            sin(radians(?)) * sin(radians(latitude)))) AS distance 
                    FROM posts p 
                    INNER JOIN users u ON p.user_id = u.user_id 
                    HAVING distance <= ? 
                    ORDER BY distance, p.created_at DESC 
                    LIMIT ? OFFSET ?";
            $posts = $this->db->query($sql, [
                $this->payload['latitude'], 
                $this->payload['longitude'], 
                $this->payload['latitude'], 
                $this->payload['radius'], 
                $this->payload['limit'], 
                $offset
            ])->getResultArray();

            return $posts;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Vote on a post
     * 
     * @return array
     */
    public function vote() {
        try {

            if (!in_array($this->payload['voteType'], ['up', 'down'])) {
                throw new DatabaseException('Invalid vote type');
            }

            $column = $this->payload['voteType'] === 'up' ? 'upvotes' : 'downvotes';
            $sql = "UPDATE posts SET $column = $column + 1 WHERE post_id = ?";
            $this->db->query($sql, [$this->payload['postId']]);

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
            $sql = "SELECT p.*, u.username, u.profile_image,
                           (p.upvotes - p.downvotes) as score 
                    FROM posts p 
                    INNER JOIN users u ON p.user_id = u.user_id 
                    WHERE p.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                    ORDER BY score DESC, p.created_at DESC 
                    LIMIT ? OFFSET ?";
            $posts = $this->db->query($sql, [$this->payload['limit'], $offset])->getResultArray();

            return $posts;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }
    
}
?>