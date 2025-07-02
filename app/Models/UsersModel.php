<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class UsersModel extends Model {

    public $payload = [];
    protected $table;
    protected $primaryKey = "user_id";
    protected $allowedFields = [
        'username', 'email', 'password_hash', 'full_name', 'is_verified',
        'is_active', 'last_login', 'bio', 'profile_image', 'location', 'gender'
    ];

    public $votesDb;
    public $notifDb;
    public $viewsDb;
    public $chatsDb;

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
     * Connect to the default database
     * 
     * @param string $db
     * 
     * @return array
     */
    public function connectToDb($db = 'votes') {

        // if the database group is default, use the default database
        if(in_array(configs('db_group'), ['default'])) {
            $this->votesDb = $this->db;
            $this->notifDb = $this->db;
            $this->viewsDb = $this->db;
            return;
        }

        // connect to the votes and comments databases
        if($db == 'votes') {
            $this->votesDb = db_connect('votes');
            setDatabaseSettings($this->votesDb);
        }
        
        if($db == 'notification') {
            $this->notifDb = db_connect('notification');
            setDatabaseSettings($this->notifDb);
        }

        if($db == 'views') {
            $this->viewsDb = db_connect('views');
            setDatabaseSettings($this->viewsDb);
        }

        if($db == 'all') {
            $this->chatsDb = db_connect('chats');
            $this->votesDb = db_connect('votes');
            $this->notifDb = db_connect('notification');
            $this->viewsDb = db_connect('views');
        }
    }

    /**
     * Get all users
     * 
     * @return array
     */
    public function getAllUsers($columns = '*') {
        try {
            return $this->db->table($this->table)->select($columns)->get()->getResultArray();
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get user profile
     * 
     * @param string $userId
     * @return array
     */
    public function getUserProfile($userId) {
        try {
            return $this->db->table($this->table)->where('user_id', $userId)->get()->getRowArray();
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update user profile
     * 
     * @param string $userId
     * @param array $data
     * @return array
     */
    public function updateProfile($userId, $data) {
        try {
            $this->db->table($this->table)->where('user_id', $userId)->update($data);
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Register device
     * 
     * @param string $userId
     * @param string $deviceId
     * @param string $deviceName
     * @param string $deviceType
     * @return array
     */
    public function registerDevice($userId, $deviceId, $deviceName = null, $deviceType = null) {
        try {
            $this->db->table($this->table)->insert([
                'device_id' => $deviceId,
                'user_id' => $userId,
                'device_name' => $deviceName,
                'device_type' => $deviceType
            ]);
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get user devices
     * 
     * @param string $userId
     * @return array
     */
    public function getUserDevices($userId) {
        try {
            return $this->db->table($this->table)->where('user_id', $userId)->orderBy('last_active', 'DESC')->get()->getResultArray();
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Deactivate account
     * 
     * @param string $userId
     * @return array
     */
    public function deactivateAccount($userId) {
        try {
            $this->db->table($this->table)->where('user_id', $userId)->update(['is_active' => false]);
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Reactivate account
     * 
     * @param string $userId
     * @return array
     */
    public function reactivateAccount($userId) {
        try {
            $this->db->table($this->table)->where('user_id', $userId)->update(['is_active' => true]);
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Find user by email
     * 
     * @param string $email
     * @return array
     */
    public function findByEmail($email, $column = 'email') {
        try {
            return $this->db->table($this->table)->where([$column => $email, 'is_active' => '1'])->get()->getRowArray();
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Delete account
     * 
     * @param string $userId
     * @return array
     */
    public function deleteAccount($userId) {
        try {
            // connect to the default database
            $this->connectToDb('all');

            // delete the user from the database
            $this->db->table('users')->where('user_id', $userId)->delete();
            $this->db->table('posts')->where('user_id', $userId)->delete();
            $this->db->table('comments')->where('user_id', $userId)->delete();
            $this->db->table('settings')->where('user_id', $userId)->delete();

            // delete the votes and views
            $this->votesDb->table('votes')->where('user_id', $userId)->delete();
            $this->viewsDb->table('views')->where('user_id', $userId)->delete();

            // delete the notifications
            $this->notifDb->table('notifications')->where('user_id', $userId)->delete();

            // delete the user from the chat rooms
            $this->chatsDb->table('user_chat_rooms')->where('user_id', $userId)->delete();
        } catch (DatabaseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get user settings
     * 
     * @param string $userId
     * @return array
     */
    public function getUserSettings($userId) {
        try {
            return $this->db->table('settings')
                            ->select('id, setting, value')
                            ->where('user_id', $userId)
                            ->get()
                            ->getResultArray();
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Create user settings
     * 
     * @param string $userId
     * @param string $setting
     * @param string $value
     * @return array
     */
    public function createUserSettings($userId, $setting, $value) {
        try {
            $value = is_array($value) ? json_encode($value) : $value;
            return $this->db->table('settings')->insert(['user_id' => $userId, 'setting' => $setting, 'value' => $value]);
        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Update user settings
     * 
     * @param string $userId
     * @param string $setting
     * @param string $value
     * @return array
     */
    public function updateUserSettings($userId, $setting, $value) {
        try {
            $value = is_array($value) ? json_encode($value) : $value;
            return $this->db->table('settings')->where(['user_id' => $userId, 'setting' => $setting])->update(['value' => $value]);
        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Search users
     * 
     * @param string $query
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function searchUsers($query, $page = 1, $limit = 20, $first_part = false) {
        try {
            $offset = ($page - 1) * $limit;
            $searchTerm = "%$query%";

            $sql = "SELECT users.user_id, username, full_name, profile_image, is_verified, last_login, gender
                    FROM users 
                    INNER JOIN settings ON users.user_id = settings.user_id
                    WHERE (username LIKE ? OR full_name LIKE ?) 
                        AND (settings.setting = 'search_visibility' AND settings.value = '1')
                    ORDER BY username 
                    LIMIT ? OFFSET ?";
            $users = $this->db->query($sql, [$searchTerm, $searchTerm, $limit, $offset])->getResultArray();

            // Get total count for pagination
            $sql = "SELECT COUNT(*) FROM users WHERE username LIKE ? OR full_name LIKE ?";
            $total = $this->db->query($sql, [$searchTerm, $searchTerm])->getRowArray()['COUNT(*)'];

            return [
                'success' => true,
                'users' => mask_email_address($users, $first_part),
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

}