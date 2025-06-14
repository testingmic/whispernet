<?php
namespace App\Controllers\Tags;

use Exception;
use App\Controllers\LoadController;

class Tags extends LoadController {

    public function createTag($name) {
        try {
            // Validate tag name
            $name = trim($name);
            if (empty($name) || strlen($name) > 50) {
                throw new Exception('Invalid tag name');
            }

            // Check if tag already exists
            $sql = "SELECT tag_id FROM tags WHERE name = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$name]);
            $existingTag = $stmt->fetch();

            if ($existingTag) {
                return [
                    'success' => true,
                    'tag_id' => $existingTag['tag_id'],
                    'message' => 'Tag already exists'
                ];
            }

            // Create new tag
            $sql = "INSERT INTO tags (name) VALUES (?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$name]);
            $tagId = $this->db->lastInsertId();

            return [
                'success' => true,
                'tag_id' => $tagId,
                'message' => 'Tag created successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function addTagToPost($postId, $tagName) {
        try {
            // First ensure tag exists
            $tagResult = $this->createTag($tagName);
            if (!$tagResult['success']) {
                throw new Exception('Failed to create tag');
            }
            $tagId = $tagResult['tag_id'];

            // Check if post exists
            $sql = "SELECT post_id FROM posts WHERE post_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$postId]);
            if (!$stmt->fetch()) {
                throw new Exception('Post not found');
            }

            // Check if tag is already assigned to post
            $sql = "SELECT * FROM post_tags WHERE post_id = ? AND tag_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$postId, $tagId]);
            if ($stmt->fetch()) {
                return [
                    'success' => true,
                    'message' => 'Tag already assigned to post'
                ];
            }

            // Assign tag to post
            $sql = "INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$postId, $tagId]);

            return [
                'success' => true,
                'message' => 'Tag assigned to post successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function removeTagFromPost($postId, $tagId) {
        try {
            $sql = "DELETE FROM post_tags WHERE post_id = ? AND tag_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$postId, $tagId]);

            return [
                'success' => true,
                'message' => 'Tag removed from post successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getPostTags($postId) {
        try {
            $sql = "SELECT t.* FROM tags t 
                    INNER JOIN post_tags pt ON t.tag_id = pt.tag_id 
                    WHERE pt.post_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$postId]);
            $tags = $stmt->fetchAll();

            return [
                'success' => true,
                'tags' => $tags
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getPostsByTag($tagName, $page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            
            $sql = "SELECT p.* FROM posts p 
                    INNER JOIN post_tags pt ON p.post_id = pt.post_id 
                    INNER JOIN tags t ON pt.tag_id = t.tag_id 
                    WHERE t.name = ? 
                    ORDER BY p.created_at DESC 
                    LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$tagName, $limit, $offset]);
            $posts = $stmt->fetchAll();

            // Get total count for pagination
            $sql = "SELECT COUNT(*) FROM posts p 
                    INNER JOIN post_tags pt ON p.post_id = pt.post_id 
                    INNER JOIN tags t ON pt.tag_id = t.tag_id 
                    WHERE t.name = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$tagName]);
            $total = $stmt->fetchColumn();

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
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getPopularTags($limit = 10) {
        try {
            $sql = "SELECT t.*, COUNT(pt.post_id) as usage_count 
                    FROM tags t 
                    INNER JOIN post_tags pt ON t.tag_id = pt.tag_id 
                    GROUP BY t.tag_id 
                    ORDER BY usage_count DESC 
                    LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            $tags = $stmt->fetchAll();

            return [
                'success' => true,
                'tags' => $tags
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function searchTags($query, $limit = 10) {
        try {
            $sql = "SELECT * FROM tags 
                    WHERE name LIKE ? 
                    ORDER BY name 
                    LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['%' . $query . '%', $limit]);
            $tags = $stmt->fetchAll();

            return [
                'success' => true,
                'tags' => $tags
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function deleteTag($tagId) {
        try {
            // Check if tag exists
            $sql = "SELECT tag_id FROM tags WHERE tag_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$tagId]);
            if (!$stmt->fetch()) {
                throw new Exception('Tag not found');
            }

            // Delete tag (post_tags entries will be automatically deleted due to foreign key constraint)
            $sql = "DELETE FROM tags WHERE tag_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$tagId]);

            return [
                'success' => true,
                'message' => 'Tag deleted successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

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
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$tagId, $tagId, $limit]);
            $tags = $stmt->fetchAll();

            return [
                'success' => true,
                'tags' => $tags
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
} 