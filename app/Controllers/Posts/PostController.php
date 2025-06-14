<?php
namespace App\Controllers\Tags;

use Exception;
use App\Controllers\LoadController;

class Posts extends LoadController {

    public function createPost($userId, $content, $latitude, $longitude, $mediaUrl = null, $mediaType = 'none') {
        try {
            $this->validateUser($userId);

            $sql = "INSERT INTO posts (user_id, content, media_url, media_type, latitude, longitude) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $content, $mediaUrl, $mediaType, $latitude, $longitude]);
            $postId = $this->db->lastInsertId();

            return [
                'success' => true,
                'post_id' => $postId,
                'message' => 'Post created successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getPost($postId) {
        try {
            $sql = "SELECT p.*, u.username, u.profile_image 
                    FROM posts p 
                    INNER JOIN users u ON p.user_id = u.user_id 
                    WHERE p.post_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$postId]);
            $post = $stmt->fetch();

            if (!$post) {
                throw new Exception('Post not found');
            }

            return [
                'success' => true,
                'post' => $post
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function updatePost($postId, $userId, $content) {
        try {
            $this->validateUser($userId);

            $sql = "UPDATE posts SET content = ? WHERE post_id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$content, $postId, $userId]);

            if ($stmt->rowCount() === 0) {
                throw new Exception('Post not found or unauthorized');
            }

            return [
                'success' => true,
                'message' => 'Post updated successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function deletePost($postId, $userId) {
        try {
            $this->validateUser($userId);

            $sql = "DELETE FROM posts WHERE post_id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$postId, $userId]);

            if ($stmt->rowCount() === 0) {
                throw new Exception('Post not found or unauthorized');
            }

            return [
                'success' => true,
                'message' => 'Post deleted successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getUserPosts($userId, $page = 1, $limit = 20) {
        try {
            $this->validateUser($userId);

            $offset = ($page - 1) * $limit;
            $sql = "SELECT p.*, u.username, u.profile_image 
                    FROM posts p 
                    INNER JOIN users u ON p.user_id = u.user_id 
                    WHERE p.user_id = ? 
                    ORDER BY p.created_at DESC 
                    LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $limit, $offset]);
            $posts = $stmt->fetchAll();

            $sql = "SELECT COUNT(*) FROM posts WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
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

    public function getNearbyPosts($latitude, $longitude, $radius = 10, $page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT p.*, u.username, u.profile_image,
                           (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                            cos(radians(longitude) - radians(?)) + 
                            sin(radians(?)) * sin(radians(latitude)))) AS distance 
                    FROM posts p 
                    INNER JOIN users u ON p.user_id = u.user_id 
                    HAVING distance <= ? 
                    ORDER BY distance, p.created_at DESC 
                    LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$latitude, $longitude, $latitude, $radius, $limit, $offset]);
            $posts = $stmt->fetchAll();

            return [
                'success' => true,
                'posts' => $posts
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function votePost($postId, $userId, $voteType) {
        try {
            $this->validateUser($userId);

            if (!in_array($voteType, ['up', 'down'])) {
                throw new Exception('Invalid vote type');
            }

            $column = $voteType === 'up' ? 'upvotes' : 'downvotes';
            $sql = "UPDATE posts SET $column = $column + 1 WHERE post_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$postId]);

            return [
                'success' => true,
                'message' => 'Vote recorded successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getTrendingPosts($page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT p.*, u.username, u.profile_image,
                           (p.upvotes - p.downvotes) as score 
                    FROM posts p 
                    INNER JOIN users u ON p.user_id = u.user_id 
                    WHERE p.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                    ORDER BY score DESC, p.created_at DESC 
                    LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit, $offset]);
            $posts = $stmt->fetchAll();

            return [
                'success' => true,
                'posts' => $posts
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    private function validateUser($userId) {
        $sql = "SELECT user_id, is_active FROM users WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) {
            throw new Exception('User not found');
        }

        if (!$user['is_active']) {
            throw new Exception('User account is deactivated');
        }

        return true;
    }
} 