<?php

namespace App\Controllers\Chats;

use Exception;
use App\Controllers\LoadController;

class Chats extends LoadController {

    public function createChatRoom($userId) {
        try {
            $this->validateUser($userId);

            $sql = "INSERT INTO chat_rooms () VALUES ()";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $roomId = $this->db->lastInsertId();

            // Add creator as participant
            $sql = "INSERT INTO chat_participants (room_id, user_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId, $userId]);

            return [
                'success' => true,
                'room_id' => $roomId,
                'message' => 'Chat room created successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function addParticipant($roomId, $userId, $addedByUserId) {
        try {
            $this->validateUser($userId);
            $this->validateUser($addedByUserId);

            // Check if adder is a participant
            $sql = "SELECT * FROM chat_participants WHERE room_id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId, $addedByUserId]);
            if (!$stmt->fetch()) {
                throw new Exception('Unauthorized to add participants');
            }

            // Check if user is already a participant
            $sql = "SELECT * FROM chat_participants WHERE room_id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId, $userId]);
            if ($stmt->fetch()) {
                throw new Exception('User is already a participant');
            }

            $sql = "INSERT INTO chat_participants (room_id, user_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId, $userId]);

            return [
                'success' => true,
                'message' => 'Participant added successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function removeParticipant($roomId, $userId, $removedByUserId) {
        try {
            $this->validateUser($userId);
            $this->validateUser($removedByUserId);

            // Check if remover is a participant
            $sql = "SELECT * FROM chat_participants WHERE room_id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId, $removedByUserId]);
            if (!$stmt->fetch()) {
                throw new Exception('Unauthorized to remove participants');
            }

            $sql = "DELETE FROM chat_participants WHERE room_id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId, $userId]);

            return [
                'success' => true,
                'message' => 'Participant removed successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function sendMessage($roomId, $userId, $content, $mediaUrl = null, $mediaType = 'text') {
        try {
            $this->validateUser($userId);

            // Check if user is a participant
            $sql = "SELECT * FROM chat_participants WHERE room_id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId, $userId]);
            if (!$stmt->fetch()) {
                throw new Exception('User is not a participant in this chat room');
            }

            $sql = "INSERT INTO chat_messages (room_id, user_id, content, media_url, media_type) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId, $userId, $content, $mediaUrl, $mediaType]);
            $messageId = $this->db->lastInsertId();

            // Update last message timestamp
            $sql = "UPDATE chat_rooms SET last_message_at = CURRENT_TIMESTAMP WHERE room_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId]);

            // Create message status for all participants
            $sql = "INSERT INTO message_status (message_id, user_id, status) 
                    SELECT ?, user_id, 'sent' 
                    FROM chat_participants 
                    WHERE room_id = ? AND user_id != ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$messageId, $roomId, $userId]);

            return [
                'success' => true,
                'message_id' => $messageId,
                'message' => 'Message sent successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getMessages($roomId, $userId, $page = 1, $limit = 50) {
        try {
            $this->validateUser($userId);

            // Check if user is a participant
            $sql = "SELECT * FROM chat_participants WHERE room_id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId, $userId]);
            if (!$stmt->fetch()) {
                throw new Exception('User is not a participant in this chat room');
            }

            $offset = ($page - 1) * $limit;
            $sql = "SELECT m.*, u.username, u.profile_image 
                    FROM chat_messages m 
                    INNER JOIN users u ON m.user_id = u.user_id 
                    WHERE m.room_id = ? 
                    ORDER BY m.created_at DESC 
                    LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId, $limit, $offset]);
            $messages = $stmt->fetchAll();

            // Update message status to 'read' for this user
            $sql = "UPDATE message_status 
                    SET status = 'read', updated_at = CURRENT_TIMESTAMP 
                    WHERE message_id IN (
                        SELECT message_id FROM chat_messages 
                        WHERE room_id = ? AND user_id != ?
                    ) AND user_id = ? AND status != 'read'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId, $userId, $userId]);

            return [
                'success' => true,
                'messages' => $messages
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getUserChats($userId, $page = 1, $limit = 20) {
        try {
            $this->validateUser($userId);

            $offset = ($page - 1) * $limit;
            $sql = "SELECT r.*, 
                           (SELECT COUNT(*) FROM chat_messages m 
                            WHERE m.room_id = r.room_id 
                            AND m.created_at > p.last_read_at) as unread_count 
                    FROM chat_rooms r 
                    INNER JOIN chat_participants p ON r.room_id = p.room_id 
                    WHERE p.user_id = ? 
                    ORDER BY r.last_message_at DESC 
                    LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $limit, $offset]);
            $chats = $stmt->fetchAll();

            return [
                'success' => true,
                'chats' => $chats
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getChatParticipants($roomId, $userId) {
        try {
            $this->validateUser($userId);

            // Check if user is a participant
            $sql = "SELECT * FROM chat_participants WHERE room_id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId, $userId]);
            if (!$stmt->fetch()) {
                throw new Exception('User is not a participant in this chat room');
            }

            $sql = "SELECT u.user_id, u.username, u.profile_image, p.joined_at, p.last_read_at 
                    FROM chat_participants p 
                    INNER JOIN users u ON p.user_id = u.user_id 
                    WHERE p.room_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$roomId]);
            $participants = $stmt->fetchAll();

            return [
                'success' => true,
                'participants' => $participants
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