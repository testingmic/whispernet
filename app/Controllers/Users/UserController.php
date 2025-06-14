<?php

namespace App\Controllers\Users;

use App\Controllers\LoadController;

class Users extends LoadController {

    public function register($username, $email, $password, $fullName = null) {
        try {
            // Validate input
            $this->validateRegistrationInput($username, $email, $password);

            // Check if username or email already exists
            if ($this->userExists($username, $email)) {
                throw new Exception('Username or email already exists');
            }

            // Hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Create user
            $sql = "INSERT INTO users (username, email, password_hash, full_name) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$username, $email, $passwordHash, $fullName]);
            $userId = $this->db->lastInsertId();

            return [
                'success' => true,
                'user_id' => $userId,
                'message' => 'User registered successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function login($username, $password) {
        try {
            $sql = "SELECT user_id, username, password_hash, is_active, is_verified 
                    FROM users 
                    WHERE username = ? OR email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($password, $user['password_hash'])) {
                throw new Exception('Invalid credentials');
            }

            if (!$user['is_active']) {
                throw new Exception('Account is deactivated');
            }

            // Update last login
            $sql = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user['user_id']]);

            return [
                'success' => true,
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'is_verified' => $user['is_verified']
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getUserProfile($userId) {
        try {
            $sql = "SELECT user_id, username, email, full_name, bio, profile_image, 
                           is_verified, created_at 
                    FROM users 
                    WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if (!$user) {
                throw new Exception('User not found');
            }

            return [
                'success' => true,
                'profile' => $user
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function updateProfile($userId, $data) {
        try {
            $allowedFields = ['full_name', 'bio', 'profile_image'];
            $updates = [];
            $params = [];

            foreach ($data as $field => $value) {
                if (in_array($field, $allowedFields)) {
                    $updates[] = "$field = ?";
                    $params[] = $value;
                }
            }

            if (empty($updates)) {
                throw new Exception('No valid fields to update');
            }

            $params[] = $userId;
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return [
                'success' => true,
                'message' => 'Profile updated successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function changePassword($userId, $currentPassword, $newPassword) {
        try {
            // Verify current password
            $sql = "SELECT password_hash FROM users WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
                throw new Exception('Current password is incorrect');
            }

            // Update password
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password_hash = ? WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$passwordHash, $userId]);

            return [
                'success' => true,
                'message' => 'Password changed successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function registerDevice($userId, $deviceId, $deviceName = null, $deviceType = null) {
        try {
            $sql = "INSERT INTO user_devices (device_id, user_id, device_name, device_type) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$deviceId, $userId, $deviceName, $deviceType]);

            return [
                'success' => true,
                'message' => 'Device registered successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getUserDevices($userId) {
        try {
            $sql = "SELECT * FROM user_devices WHERE user_id = ? ORDER BY last_active DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $devices = $stmt->fetchAll();

            return [
                'success' => true,
                'devices' => $devices
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function deactivateAccount($userId) {
        try {
            $sql = "UPDATE users SET is_active = FALSE WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);

            return [
                'success' => true,
                'message' => 'Account deactivated successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function reactivateAccount($userId) {
        try {
            $sql = "UPDATE users SET is_active = TRUE WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);

            return [
                'success' => true,
                'message' => 'Account reactivated successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    private function validateRegistrationInput($username, $email, $password) {
        if (empty($username) || strlen($username) < 3 || strlen($username) > 50) {
            throw new Exception('Username must be between 3 and 50 characters');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long');
        }
    }

    private function userExists($username, $email) {
        $sql = "SELECT user_id FROM users WHERE username = ? OR email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username, $email]);
        return $stmt->fetch() !== false;
    }

    public function searchUsers($query, $page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            $searchTerm = "%$query%";

            $sql = "SELECT user_id, username, full_name, profile_image, is_verified 
                    FROM users 
                    WHERE username LIKE ? OR full_name LIKE ? 
                    ORDER BY username 
                    LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$searchTerm, $searchTerm, $limit, $offset]);
            $users = $stmt->fetchAll();

            // Get total count for pagination
            $sql = "SELECT COUNT(*) FROM users WHERE username LIKE ? OR full_name LIKE ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$searchTerm, $searchTerm]);
            $total = $stmt->fetchColumn();

            return [
                'success' => true,
                'users' => $users,
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
} 