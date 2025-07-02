<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class AnalyticsModel extends Model {

    public $payload = [];
    protected $table;
    protected $primaryKey = "id";

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
     * Log pageview
     * 
     * @param string $page
     * @param string $userUUID
     * @param string $userAgent
     * @return void
     */
    public function logPageview($page, $userUUID, $userID, $userAgent, $referer) {
        $this->db->table('pageviews')->insert([
            'page' => $page,
            'uuid' => $userUUID,
            'user_id' => $userID,
            'user_agent' => $userAgent,
            'referer' => $referer
        ]);
    }

    /**
     * Get timeframe condition
     * 
     * @param string $timeframe
     * @return string
     */
    private function getTimeframeCondition($timeframe) {
        switch ($timeframe) {
            case '1h':
                return "created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
            case '24h':
                return "created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
            case '7d':
                return "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            case '30d':
                return "created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            default:
                return "created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
        }
    }

}