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
        'username', 'email', 'password_hash', 'full_name', 'is_verified', 'user_type', 'status',
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

    /**
     * Get users with filters and pagination
     * 
     * @param array $filters
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getUsers($filters = [], $page = 1, $limit = 10)
    {
        try {
            $offset = ($page - 1) * $limit;
            
            $sql = "SELECT 
                        user_id, full_name, username, email, user_type AS role, 
                        status, created_at, last_login AS last_activity, profile_image 
                    FROM users WHERE 1=1";
            $params = [];

            // Apply filters
            if (!empty($filters['search'])) {
                $sql .= " AND (full_name LIKE ? OR username LIKE ? OR email LIKE ?)";
                $searchTerm = "%{$filters['search']}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            if (!empty($filters['status']) && $filters['status'] !== 'all') {
                $sql .= " AND status = ?";
                $params[] = $filters['status'];
            }

            if (!empty($filters['role']) && $filters['role'] !== 'all') {
                $sql .= " AND user_type = ?";
                $params[] = $filters['role'];
            }

            $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;

            return $this->db->query($sql, $params)->getResultArray();

        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get total count of users with filters
     * 
     * @param array $filters
     * @return int
     */
    public function getUsersCount($filters = [])
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM users WHERE 1=1";
            $params = [];

            // Apply filters
            if (!empty($filters['search'])) {
                $sql .= " AND (full_name LIKE ? OR username LIKE ? OR email LIKE ?)";
                $searchTerm = "%{$filters['search']}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            if (!empty($filters['status']) && $filters['status'] !== 'all') {
                $sql .= " AND status = ?";
                $params[] = $filters['status'];
            }

            if (!empty($filters['role']) && $filters['role'] !== 'all') {
                $sql .= " AND role = ?";
                $params[] = $filters['role'];
            }

            $result = $this->db->query($sql, $params)->getRowArray();
            return (int)$result['count'];

        } catch (DatabaseException $e) {
            return 0;
        }
    }

    /**
     * Get user statistics
     * 
     * @return array
     */
    public function getStats()
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as totalUsers,
                        COUNT(CASE WHEN status = 'active' THEN 1 END) as activeUsers,
                        COUNT(CASE WHEN status = 'admin' THEN 1 END) as adminUsers,
                        COUNT(CASE WHEN status = 'suspended' THEN 1 END) as suspendedUsers,
                        COUNT(CASE WHEN user_type = 'moderator' THEN 1 END) as moderatorsCount
                    FROM users";
            
            return $this->db->query($sql)->getRowArray();

        } catch (DatabaseException $e) {
            return [
                'totalUsers' => 0,
                'activeUsers' => 0,
                'adminUsers' => 0,
                'suspendedUsers' => 0,
                'moderatorsCount' => 0
            ];
        }
    }

    /**
     * Get user by ID
     * 
     * @param int $userId
     * @return array|null
     */
    public function getUserById($userId)
    {
        try {
            $sql = "SELECT user_id, full_name, username, email, user_type AS role, status, created_at, 
                    last_login AS last_activity, profile_image, gender
                    FROM users WHERE user_id = ?";
            return $this->db->query($sql, [$userId])->getRowArray();

        } catch (DatabaseException $e) {
            return null;
        }
    }

    /**
     * Check if user exists
     * 
     * @param string $username
     * @param string $email
     * @param int $excludeUserId
     * @return bool
     */
    public function userExists($username, $email, $excludeUserId = null)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM users WHERE (username = ? OR email = ?)";
            $params = [$username, $email];

            if ($excludeUserId) {
                $sql .= " AND user_id != ?";
                $params[] = $excludeUserId;
            }

            $result = $this->db->query($sql, $params)->getRowArray();
            return (int)$result['count'] > 0;

        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Create new user
     * 
     * @param array $data
     * @return int|false
     */
    public function createUser($data)
    {
        try {
            $sql = "INSERT INTO users (full_name, username, email, password, role, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";
            
            $params = [
                $data['full_name'],
                $data['username'],
                $data['email'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['role'],
                $data['status']
            ];

            $result = $this->db->query($sql, $params);
            return $result ? $this->db->insertID() : false;

        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Update user
     * 
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function updateUser($userId, $data)
    {
        try {
            $sql = "UPDATE users SET full_name = ?, username = ?, email = ?, role = ?, status = ?";
            $params = [
                $data['full_name'],
                $data['username'],
                $data['email'],
                $data['role'],
                $data['status']
            ];

            // Add password update if provided
            if (!empty($data['password'])) {
                $sql .= ", password = ?";
                $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            $sql .= " WHERE user_id = ?";
            $params[] = $userId;

            return (bool)$this->db->query($sql, $params);

        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Delete user
     * 
     * @param int $userId
     * @return bool
     */
    public function deleteUser($userId)
    {
        try {
            // Start transaction
            $this->db->transStart();

            // Delete user's posts
            $this->db->query("DELETE FROM posts WHERE user_id = ?", [$userId]);

            // Delete user's comments
            $this->db->query("DELETE FROM comments WHERE user_id = ?", [$userId]);

            // Delete user's votes
            $this->db->query("DELETE FROM votes WHERE user_id = ?", [$userId]);

            // Delete user's bookmarks
            $this->db->query("DELETE FROM bookmarks WHERE user_id = ?", [$userId]);

            // Delete user's hidden posts
            $this->db->query("DELETE FROM hidden_posts WHERE user_id = ?", [$userId]);

            // Delete user's notifications
            $this->db->query("DELETE FROM notifications WHERE user_id = ?", [$userId]);

            // Delete user's devices
            $this->db->query("DELETE FROM user_devices WHERE user_id = ?", [$userId]);

            // Delete user's settings
            $this->db->query("DELETE FROM user_settings WHERE user_id = ?", [$userId]);

            // Finally delete the user
            $this->db->query("DELETE FROM users WHERE user_id = ?", [$userId]);

            // Commit transaction
            $this->db->transComplete();

            return $this->db->transStatus();

        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Update user status
     * 
     * @param int $userId
     * @param string $status
     * @return bool
     */
    public function updateUserStatus($userId, $status)
    {
        try {
            $sql = "UPDATE users SET status = ? WHERE user_id = ?";
            return (bool)$this->db->query($sql, [$status, $userId]);

        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Bulk update user status
     * 
     * @param array $userIds
     * @param string $status
     * @return bool
     */
    public function bulkUpdateStatus($userIds, $status)
    {
        try {
            $placeholders = str_repeat('?,', count($userIds) - 1) . '?';
            $sql = "UPDATE users SET status = ? WHERE user_id IN ($placeholders)";
            
            $params = array_merge([$status], $userIds);
            return (bool)$this->db->query($sql, $params);

        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Bulk delete users
     * 
     * @param array $userIds
     * @return bool
     */
    public function bulkDelete($userIds)
    {
        try {
            $this->db->transStart();

            foreach ($userIds as $userId) {
                $this->deleteUser($userId);
            }

            $this->db->transComplete();
            return $this->db->transStatus();

        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Get users for export
     * 
     * @param array $filters
     * @return array
     */
    public function getUsersForExport($filters = [])
    {
        try {
            $sql = "SELECT user_id, full_name, username, email, role, status, created_at, last_activity 
                    FROM users WHERE 1=1";
            $params = [];

            // Apply filters
            if (!empty($filters['search'])) {
                $sql .= " AND (full_name LIKE ? OR username LIKE ? OR email LIKE ?)";
                $searchTerm = "%{$filters['search']}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            if (!empty($filters['status']) && $filters['status'] !== 'all') {
                $sql .= " AND status = ?";
                $params[] = $filters['status'];
            }

            if (!empty($filters['role']) && $filters['role'] !== 'all') {
                $sql .= " AND role = ?";
                $params[] = $filters['role'];
            }

            $sql .= " ORDER BY created_at DESC";

            return $this->db->query($sql, $params)->getResultArray();

        } catch (DatabaseException $e) {
            return [];
        }
    }


}