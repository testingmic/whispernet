<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class ChatsModel extends Model {

    public $payload = [];
    protected $table;
    protected $primaryKey = "chat_id";

    public function __construct() {
        parent::__construct();
        
        $this->table = DbTables::$userTable;
        foreach(DbTables::initTables() as $key) {
            if (property_exists($this, $key)) {
                $this->{$key} = DbTables::${$key};
            }
        }
    }

    public function createChatRoom($userId) {

        try {

            $roomId = $this->db->table('chat_rooms')->insert([
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $roomId = $this->db->insertID();

            // Add creator as participant
            $stmt = $this->db->query("INSERT INTO chat_participants (room_id, user_id) VALUES (?, ?)", [$roomId, $userId]);

            return [
                'success' => true,
                'room_id' => $roomId,
                'message' => 'Chat room created successfully'
            ];
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    public function addParticipant($roomId, $userId, $addedByUserId) {
        try {

            // Check if adder is a participant
            $participant = $this->db->table('chat_participants')->where('room_id', $roomId)->where('user_id', $addedByUserId)->get()->getRowArray();
            if (!$participant) {
                return ('Unauthorized to add participants');
            }

            // Check if user is already a participant
            $participant = $this->db->table('chat_participants')->where('room_id', $roomId)->where('user_id', $userId)->get()->getRowArray();
            if ($participant) {
                return ('User is already a participant');
            }

            // Add user as participant
            $this->db->table('chat_participants')->insert([
                'room_id' => $roomId,
                'user_id' => $userId
            ]);

            return [
                'success' => true,
                'message' => 'Participant added successfully'
            ];
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    public function removeParticipant($roomId, $userId, $removedByUserId) {
        try {
            $this->validateUser($removedByUserId);

            // Check if remover is a participant
            $participant = $this->db->table('chat_participants')->where('room_id', $roomId)->where('user_id', $removedByUserId)->get()->getRowArray();
            if (!$participant) {
                return ('Unauthorized to remove participants');
            }

            // Remove user as participant
            $this->db->table('chat_participants')->where('room_id', $roomId)->where('user_id', $userId)->delete();

            return [
                'success' => true,
                'message' => 'Participant removed successfully'
            ];
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    public function sendMessage($roomId, $userId, $content, $mediaUrl = null, $mediaType = 'text') {
        try {

            // Check if user is a participant
            $participant = $this->db->table('chat_participants')->where('room_id', $roomId)->where('user_id', $userId)->get()->getRowArray();
            if (!$participant) {
                return ('User is not a participant in this chat room');
            }

            $messageId = $this->db->table('chat_messages')->insert([
                'room_id' => $roomId,
                'user_id' => $userId,
                'content' => $content,
                'media_url' => $mediaUrl,
                'media_type' => $mediaType
            ]);

            // Update last message timestamp
            $this->db->table('chat_rooms')->where('room_id', $roomId)->update(['last_message_at' => date('Y-m-d H:i:s')]);

            // Create message status for all participants
            $this->db->table('message_status')->insert([
                'message_id' => $messageId,
                'user_id' => $userId,
                'status' => 'sent'
            ]);

            return [
                'success' => true,
                'message_id' => $messageId,
                'message' => 'Message sent successfully'
            ];
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    public function getMessages($roomId, $userId, $page = 1, $limit = 50) {
        try {

            // Check if user is a participant
            $participant = $this->db->table('chat_participants')->where('room_id', $roomId)->where('user_id', $userId)->get()->getRowArray();
            if (!$participant) {
                return ('User is not a participant in this chat room');
            }

            $offset = ($page - 1) * $limit;
            $messages = $this->db->table('chat_messages')->where('room_id', $roomId)->orderBy('created_at', 'DESC')->limit($limit)->offset($offset)->get()->getResultArray();

            // Update message status to 'read' for this user
            $this->db->table('message_status')->where('room_id', $roomId)->where('user_id', $userId)
                    ->where('status', '!=', 'read')->update(['status' => 'read', 'updated_at' => date('Y-m-d H:i:s')]);

            return [
                'success' => true,
                'messages' => $messages
            ];
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    public function getUserChats($userId, $page = 1, $limit = 20) {
        try {

            $offset = ($page - 1) * $limit;
            $chats = $this->db->table('chat_rooms')
                                ->where('user_id', $userId)
                                ->orderBy('last_message_at', 'DESC')
                                ->limit($limit)
                                ->offset($offset)
                                ->get()
                                ->getResultArray();

            return [
                'success' => true,
                'chats' => $chats
            ];
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    public function getChatParticipants($roomId, $userId) {
        try {

            // Check if user is a participant
            $participant = $this->db->table('chat_participants')->where('room_id', $roomId)->where('user_id', $userId)->get()->getRowArray();
            if (!$participant) {
                return ('User is not a participant in this chat room');
            }

            $sql = "SELECT u.user_id, u.username, u.profile_image, p.joined_at, p.last_read_at 
                    FROM chat_participants p 
                    INNER JOIN users u ON p.user_id = u.user_id 
                    WHERE p.room_id = ?";
            $participants = $this->db->query($sql, [$roomId])->getResultArray();

            return [
                'success' => true,
                'participants' => $participants
            ];
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

}