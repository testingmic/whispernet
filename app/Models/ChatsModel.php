<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class ChatsModel extends Model {

    public $payload = [];
    protected $table;
    protected $chatsDb;
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
     * Connect to the votes and comments databases
     * 
     * @param string $db
     * 
     * @return array
     */
    public function connectToDb($db = 'chats') {
        // if the database group is default, use the default database
        if(in_array(configs('db_group'), ['default'])) {
            $this->chatsDb = $this->db;
            return;
        }

        if($db == 'chats') {
            $this->chatsDb = db_connect('chats');
            setDatabaseSettings($this->chatsDb);
        }
    }

    /**
     * Create chat room
     * 
     * @param int $sender
     * @param int $receiver
     * @param string $type
     * @param array $receipientsList
     * @param string $roomUUID
     * @return array
     */
    public function createChatRoom($sender, $receiver, $type, $receipientsList = null, $roomUUID = null, $groupInfo = null) {

        try {
            $this->db->table('chat_rooms')->insert([
                'sender_id' => $sender,
                'receiver_id' => $receiver,
                'type' => $type,
                'room_uuid' => $roomUUID,
                'created_at' => date('Y-m-d H:i:s'),
                'receipients_list' => json_encode($receipientsList)
            ]);
            $roomId = $this->db->insertID();

            // update the group info if it exists
            if(!empty($groupInfo)) {
                $this->db->table('chat_rooms')->update([
                    'name' => $groupInfo['name'] ?? '',
                    'description' => $groupInfo['description'] ?? ''
                ], ['room_id' => $roomId]);
            }

            // connect to the chats database
            $this->connectToDb('chats');

            // create the room for the sender and receiver
            foreach([$sender, $receiver] as $userId) {
                if(empty($userId)) continue;
                $this->chatsDb->table('user_chat_rooms')->insert([
                    'room_id' => (int)$roomId,
                    'user_id' => (int)$userId,
                    'type' => $type,
                ]);
            }

            return $roomId;

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
     * Get user chat rooms
     * 
     * @param int $userId
     * @return array
     */
    public function getUserChatRooms($userId, $roomId = null) {

        try {
        
            $this->connectToDb('chats');
            $rooms = $this->chatsDb->table('user_chat_rooms')->where('user_id', $userId);

            if(!empty($roomId)) {
                $rooms->where('room_id', $roomId);
            }
            $chatRooms = $rooms->get()->getResultArray();

            if(empty($chatRooms)) {
                return [];
            }

            // get the user names for the individual chats
            $roomIds = array_column($chatRooms, 'room_id');

            // get the participants for the rooms
            $participants = $this->db->table('chat_rooms')->select('*')->whereIn('room_id', $roomIds)->get()->getResultArray();
            if(empty($participants)) {
                return [];
            }

            $groupsList = [];
            $groupedType = [];
            $userIdsByRoomId = [];
            foreach($participants as $key => $par) {
                $groupedType[$par['room_id']] = $par['type'];

                $data = [
                    'type' => $par['type'],
                    'participants' => json_decode($par['receipients_list'], true),
                    'name' => $par['name'],
                    'description' => $par['description'],
                ];

                $userIdsByRoomId[$par['room_id']] = $data;
                if($par['type'] == 'group') {
                    $data['room_id'] = $par['room_id'];
                    $data['last_login'] = $par['last_message_at'];
                    $data['room_uuid'] = $par['room_uuid'];
                    $groupsList[] = $data;
                }
            }

            $participants = array_column($participants, 'receipients_list');

            // convert each record to an array
            $participants = array_map(function($participant) {
                return json_decode($participant, true);
            }, $participants);

            // group the participants into one array
            $participants = array_unique(array_merge(...$participants));

            // remove the current user from the participants
            $participants = array_values(array_diff($participants, [$userId]));

            // get the users for the participants
            $users = $this->db->table('users')
                            ->select('user_id, full_name, username, profile_image, last_login')
                            ->whereIn('user_id', $participants)
                            ->where('is_active', 1)
                            ->get()
                            ->getResultArray();

            $roomsList = [];
            foreach($users as $user) {
                $theRoom = array_filter($userIdsByRoomId, function($room) use ($user) {
                    return in_array($user['user_id'], $room['participants']);
                });
                $user['room_id'] = array_keys($theRoom)[0] ?? 0;
                $user['room'] = array_values($theRoom)[0] ?? [];
                $roomsList[] = $user;
            }

            if(!empty($groupsList)) {
                foreach($groupsList as $group) {
                    $group['full_name'] = $group['name'];
                    $group['username'] = $group['name'];
                    $group['user_id'] = $group['room_id'];

                    $count = count($group['participants']);

                    $group['particiants'] = $count == 1 ? '1 participant' : $count . ' participants';
                    $group['room'] = [
                        'type' => 'group',
                        'participants' => $group['participants'],
                        'name' => $group['name'],
                        'description' => $group['description'],
                    ];
                    unset($group['participants']);
                    unset($group['description']);
                    unset($group['name']);
                    $roomsList[] = $group;
                }
            }

            return $roomsList;

        } catch (DatabaseException $e) {
            return [];
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
     * @return int
     */
    public function postMessage($payload) {
        try {
            // insert the message
            $this->db->table('chat_messages')->insert([
                'room_id' => $payload['room_id'],
                'user_id' => $payload['user_id'],
                'content' => $payload['content'],
                'self_destruct_at' => $payload['self_destruct_at'] ?? date('Y-m-d H:i:s', strtotime("+24 hours")),
                'unique_id' => $payload['unique_id'],
                'media_url' => $payload['media_url'] ?? '',
                'media_type' => $payload['media_type'] ?? 'text',
            ]);

            $insertId = $this->db->insertID();

            // Update last message timestamp
            $this->db->table('chat_rooms')->where('room_id', $payload['room_id'])->update(['last_message_at' => date('Y-m-d H:i:s')]);

            // return the message id
            return $insertId;
        } catch (DatabaseException $e) {
            return 0;
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
            $messages = $this->db->table('chat_messages c')
                ->select('c.*, m.media')
                ->where('c.room_id', $roomId)
                ->join('media m', 'm.record_id = c.message_id', 'left')
                ->select('c.*, m.media')
                ->orderBy('c.created_at', 'DESC')
                ->limit($limit)
                ->offset($offset)
                ->get()->getResultArray();

            return $messages;
        } catch (DatabaseException $e) {
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