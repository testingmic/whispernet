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

    /**
     * Create chat room
     * 
     * @param int $sender
     * @param int $receiver
     * @param string $type
     * @return array
     */
    public function createChatRoom($sender, $receiver, $type, $receipientsList = null) {

        try {
            $this->db->table('chat_rooms')->insert([
                'sender_id' => $sender,
                'receiver_id' => $receiver,
                'type' => $type,
                'created_at' => date('Y-m-d H:i:s'),
                'receipients_list' => json_encode($receipientsList)
            ]);
            return $this->db->insertID();

        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get chat room
     * 
     * @param int $roomId
     * @return array
     */
    public function getChatRoom($roomId) {
        try {
            return $this->db->table('chat_rooms')->where('room_id', $roomId)->get()->getRowArray();
        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Get individual chat room id
     * 
     * @param int $sender
     * @param int $receiver
     * @return int
     */
    public function getIndividualChatRoomId($sender, $receiver) {
        try {
            $roomId = $this->db->query("SELECT * FROM chat_rooms 
                WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
                LIMIT 1", [$sender, $receiver, $receiver, $sender])->getRowArray();
            return $roomId;
        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Send message
     * 
     * @param int $roomId
     * @param int $userId
     * @param string $content
     * @param string $mediaUrl
     * @param string $mediaType
     * @return array
     */
    public function postMessage($payload) {
        try {
            // insert the message
            $this->db->table('chat_messages')->insert([
                'room_id' => $payload['room_id'],
                'user_id' => $payload['user_id'],
                'content' => $payload['content'],
                'unique_id' => $payload['unique_id'],
                'media_url' => $payload['media_url'] ?? '',
                'media_type' => $payload['media_type'] ?? 'text',
            ]);

            // Update last message timestamp
            $this->db->table('chat_rooms')->where('room_id', $payload['room_id'])->update(['last_message_at' => date('Y-m-d H:i:s')]);

            // return the message id
            return $this->db->insertID();
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get messages
     * 
     * @param int $roomId
     * @param int $userId
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getMessages($roomId, $page = 1, $limit = 50) {
        try {

            // Check if user is a participant
            $offset = ($page - 1) * $limit;
            $messages = $this->db->table('chat_messages')->where('room_id', $roomId)->orderBy('created_at', 'DESC')->limit($limit)->offset($offset)->get()->getResultArray();

            // Update message status to 'read' for this user
            // $this->db->table('message_status')->where('room_id', $roomId)->where('user_id', $userId)
            //         ->where('status', '!=', 'read')->update(['status' => 'read', 'updated_at' => date('Y-m-d H:i:s')]);

            return $messages;
        } catch (DatabaseException $e) {
            return $e->getMessage();
            return [];
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