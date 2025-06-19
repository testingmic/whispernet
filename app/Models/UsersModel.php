<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class UsersModel extends Model {

    public $payload = [];
    protected $table;
    protected $primaryKey = "user_id";
    protected $allowedFields = ['username', 'email', 'password_hash', 'full_name', 'is_verified', 'is_active', 'last_login', 'bio', 'profile_image'];

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
     * Search users
     * 
     * @param string $query
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function searchUsers($query, $page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            $searchTerm = "%$query%";

            $sql = "SELECT user_id, username, full_name, profile_image, is_verified, last_login
                    FROM users 
                    WHERE username LIKE ? OR full_name LIKE ? 
                    ORDER BY username 
                    LIMIT ? OFFSET ?";
            $users = $this->db->query($sql, [$searchTerm, $searchTerm, $limit, $offset])->getResultArray();

            // Get total count for pagination
            $sql = "SELECT COUNT(*) FROM users WHERE username LIKE ? OR full_name LIKE ?";
            $total = $this->db->query($sql, [$searchTerm, $searchTerm])->getRowArray()['COUNT(*)'];

            return [
                'success' => true,
                'users' => mask_email_address($users),
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