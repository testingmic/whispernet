<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class TagsModel extends Model {

    public $payload = [];
    protected $table;
    protected $primaryKey = "tag_id";

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
     * Create tag
     * 
     * @param string $name
     * @return int
     */
    public function createTag($name) {
        try {
            // Validate tag name
            $name = trim($name);

            // Check if tag already exists
            $existingTag = $this->db->table('tags')->where('name', $name)->get()->getRowArray();

            if ($existingTag) {
                return [
                    'success' => true,
                    'tag_id' => $existingTag['tag_id'],
                    'message' => 'Tag already exists'
                ];
            }

            // Create new tag
            $tagId = $this->db->table('tags')->insert([
                'name' => $name
            ]);

            return $tagId;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Add tag to post
     * 
     * @param int $postId
     * @param string $tagName
     * @return bool
     */
    public function addTagToPost($postId, $tagName) {
        try {
            // First ensure tag exists
            $tagResult = $this->createTag($tagName);
            if (!$tagResult['success']) {
                return [
                    'success' => false,
                    'message' => 'Failed to create tag'
                ];
            }
            $tagId = $tagResult['tag_id'];

            // Check if post exists
            $post = $this->db->table('posts')->where('post_id', $postId)->get()->getRowArray();
            if (!$post) {
                return [
                    'success' => false,
                    'message' => 'Post not found'
                ];
            }

            // Check if tag is already assigned to post
            $postTag = $this->db->table('post_tags')->where('post_id', $postId)->where('tag_id', $tagId)->get()->getRowArray();
            if ($postTag) {
                return [
                    'success' => true,
                    'message' => 'Tag already assigned to post'
                ];
            }

            // Assign tag to post
            $this->db->table('post_tags')->insert([
                'post_id' => $postId,
                'tag_id' => $tagId
            ]);

            return true;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove tag from post
     * 
     * @param int $postId
     * @param int $tagId
     * @return bool
     */
    public function removeTagFromPost($postId, $tagId) {
        try {
            $this->db->table('post_tags')->where('post_id', $postId)->where('tag_id', $tagId)->delete();

            return true;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get post tags
     * 
     * @param int $postId
     * @return array
     */
    public function getPostTags($postId) {
        try {
            $sql = "SELECT t.* FROM tags t 
                    INNER JOIN post_tags pt ON t.tag_id = pt.tag_id 
                    WHERE pt.post_id = ?";
            $tags = $this->db->query($sql, [$postId])->getResultArray();

            return [
                'success' => true,
                'tags' => $tags
            ];
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get posts by tag
     * 
     * @param string $tagName
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getPostsByTag($tagName, $page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            
            $sql = "SELECT p.* FROM posts p 
                    INNER JOIN post_tags pt ON p.post_id = pt.post_id 
                    INNER JOIN tags t ON pt.tag_id = t.tag_id 
                    WHERE t.name = ? 
                    ORDER BY p.created_at DESC 
                    LIMIT ? OFFSET ?";
            $posts = $this->db->table('posts')->where('name', $tagName)->limit($limit)->offset($offset)->get()->getResultArray();

            // Get total count for pagination
            $sql = "SELECT COUNT(*) FROM posts p 
                    INNER JOIN post_tags pt ON p.post_id = pt.post_id 
                    INNER JOIN tags t ON pt.tag_id = t.tag_id 
                    WHERE t.name = ?";
            $total = $this->db->query($sql, [$tagName])->getRowArray()['COUNT(*)'];

            return [
                'success' => true,
                'posts' => $posts,
                'pagination' => [
                    'total' => $total,
                    'page' => $page,
                    'limit' => $limit,
                    'pages' => ceil($total / $limit)
                ]
            ];
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get popular tags
     * 
     * @param int $limit
     * @return array
     */
    public function getPopularTags($limit = 10) {
        try {
            $tags = $this->db->query("SELECT t.*, COUNT(pt.post_id) as usage_count 
                    FROM tags t 
                    INNER JOIN post_tags pt ON t.tag_id = pt.tag_id 
                    GROUP BY t.tag_id 
                    ORDER BY usage_count DESC 
                    LIMIT ?", [$limit])->getResultArray();

            return $tags;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Search tags
     * 
     * @param string $query
     * @param int $limit
     * @return array
     */
    public function searchTags($query, $limit = 10) {
        try {
            $sql = "SELECT * FROM tags 
                    WHERE name LIKE ? 
                    ORDER BY name 
                    LIMIT ?";
            $tags = $this->db->query($sql, [$query, $limit])->getResultArray();

            return $tags;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Delete tag
     * 
     * @param int $tagId
     * @return bool
     */
    public function deleteTag($tagId) {
        try {
            // Check if tag exists
            $tag = $this->db->table('tags')->where('tag_id', $tagId)->get()->getRowArray();
            if (!$tag) {
                return [
                    'success' => false,
                    'message' => 'Tag not found'
                ];
            }

            // Delete tag (post_tags entries will be automatically deleted due to foreign key constraint)
            $this->db->table('tags')->where('tag_id', $tagId)->delete();

            return true;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get related tags
     * 
     * @param int $tagId
     * @param int $limit
     * @return array
     */
    public function getRelatedTags($tagId, $limit = 5) {
        try {
            $sql = "SELECT t2.*, COUNT(*) as co_occurrence 
                    FROM post_tags pt1 
                    INNER JOIN post_tags pt2 ON pt1.post_id = pt2.post_id 
                    INNER JOIN tags t2 ON pt2.tag_id = t2.tag_id 
                    WHERE pt1.tag_id = ? AND pt2.tag_id != ? 
                    GROUP BY t2.tag_id 
                    ORDER BY co_occurrence DESC 
                    LIMIT ?";
            $tags = $this->db->query($sql, [$tagId, $tagId, $limit])->getResultArray();

            return $tags;
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

}