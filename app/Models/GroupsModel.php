<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class GroupsModel extends Model {

    public $payload = [];
    protected $table = 'groups';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'created_by', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function __construct() {
        parent::__construct();
        
        foreach(DbTables::initTables() as $key) {
            if (property_exists($this, $key)) {
                $this->{$key} = DbTables::${$key};
            }
        }
    }

    /**
     * Get group participants
     * 
     * @param int $groupId
     * @return array
     */
    public function getGroupParticipants($groupId)
    {
        return $this->db->table('group_participants')
            ->select('users.id, users.name, users.email')
            ->join('users', 'users.id = group_participants.user_id')
            ->where('group_participants.group_id', $groupId)
            ->get()
            ->getResultArray();
    }

    /**
     * Get group messages
     * 
     * @param int $groupId
     * @return array
     */
    public function getGroupMessages($groupId)
    {
        return $this->db->table('group_messages')
            ->select('group_messages.*, users.username')
            ->join('users', 'users.id = group_messages.user_id')
            ->where('group_messages.group_id', $groupId)
            ->orderBy('group_messages.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Add a new message to the group
     * 
     * @param array $data
     * @return int|false
     */
    public function addMessage($data)
    {
        return $this->db->table('group_messages')->insert($data) ? $this->db->insertID() : false;
    }

    /**
     * Check if user is a member of the group
     * 
     * @param int $groupId
     * @param int $userId
     * @return bool
     */
    public function isMember($groupId, $userId)
    {
        return $this->db->table('group_participants')
            ->where('group_id', $groupId)
            ->where('user_id', $userId)
            ->countAllResults() > 0;
    }
}