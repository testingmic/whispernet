<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class NotificationsModel extends Model {
    
    protected $notifDb;
    protected $viewsDb;

    protected $table = "notifications";
    protected $primaryKey = "notification_id";
    protected $allowedFields = ["user_id", "type", "section", "reference_id", "content", "is_read", "created_at"];

    public function __construct() {
        parent::__construct();
    }

    /**
     * Connect to the votes and comments databases
     * 
     * @param string $db
     * 
     * @return array
     */
    public function connectToDb($db = 'votes') {

        // if the database group is default, use the default database
        if(in_array(configs('db_group'), ['default'])) {
            $this->notifDb = $this->db;
            $this->viewsDb = $this->db;
            return;
        }
        
        if($db == 'notification') {
            $this->notifDb = db_connect('notification');
            setDatabaseSettings($this->notifDb);
        }

        if($db == 'views') {
            $this->viewsDb = db_connect('views');
            setDatabaseSettings($this->viewsDb);
        }
    }

    /**
     * Get the notifications for a user
     * 
     * @param int $userId
     * @return array
     */
    public function getUserNotifications($userId) {

        try {
            // connect to the notification database
            return $this->notifDb->table($this->table)
                            ->where("user_id", $userId)
                            ->orderBy("created_at", "DESC")
                            ->get()->getResultArray();
        } catch (DatabaseException $e) {
            return [];
        }
    }

    /**
     * Delete a notification
     * 
     * @param int $notificationId
     * @param int $userId
     * 
     * @return bool
     */
    public function deleteRecord($notificationId, $userId) {
        try {
            return $this->notifDb->table($this->table)
                            ->where("notification_id", $notificationId)
                            ->where("user_id", $userId)
                            ->delete();
        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Read a notification
     * 
     * @param int $notificationId
     * @param int $userId
     * 
     * @return bool
     */
    public function readRecord($notificationId, $userId) {
        try {
            return $this->notifDb->table($this->table)
                            ->where("notification_id", $notificationId)
                            ->where("user_id", $userId)
                            ->update(['is_read' => 1]);
        } catch (DatabaseException $e) {
            return false;
        }
    }
}