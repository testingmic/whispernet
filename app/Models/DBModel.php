<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;

class DBModel extends Model {

    public $payload = [];
    protected $table;
    protected $authTokenTable;
    protected $primaryKey = "idusertokenauth";
    protected $allowedFields = ["login", "description", "password", "date_created", "date_expired", "system_token", "hash_algo"];

    public function __construct() {
        parent::__construct();
        
        $this->table = DbTables::$userTable;
        foreach(DbTables::initTables() as $key) {
            if (property_exists($this, $key)) {
                $this->{$key} = DbTables::${$key};
            }
        }
    }

    public function findOne($token) {

    }
}
?>