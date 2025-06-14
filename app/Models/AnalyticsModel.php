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
     * Track event
     * 
     * @param string $eventType
     * @param string $deviceId
     * @param float $latitude
     * @param float $longitude
     * @param array $metadata
     * @return int
     */
    public function trackEvent($eventType, $deviceId = null, $latitude = null, $longitude = null, $metadata = []) {
        try {
            $this->db->table('analytics')->insert([
                'event_type' => $eventType,
                'device_id' => $deviceId,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'metadata' => json_encode($metadata)
            ]);

            return $this->db->insertID();
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Get activity heatmap
     * 
     * @param string $timeframe
     * @return array
     */
    public function getActivityHeatmap($timeframe = '24h') {
        try {
            $timeCondition = $this->getTimeframeCondition($timeframe);
            
            $query = "
                SELECT 
                    ROUND(latitude, 2) as lat,
                    ROUND(longitude, 2) as lng,
                    COUNT(*) as activity_count
                FROM analytics
                WHERE {$timeCondition}
                AND latitude IS NOT NULL 
                AND longitude IS NOT NULL
                GROUP BY ROUND(latitude, 2), ROUND(longitude, 2)
                HAVING activity_count > 0
            ";

            return $this->db->query($query)->getResultArray();
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Get user activity
     * 
     * @param string $deviceId
     * @param string $timeframe
     * @return array
     */
    public function getUserActivity($deviceId, $timeframe = '24h') {
        try {
            $timeCondition = $this->getTimeframeCondition($timeframe);
            
            $query = "
                SELECT 
                    event_type,
                    COUNT(*) as count,
                    DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') as hour
                FROM analytics
                WHERE device_id = ? AND {$timeCondition}
                GROUP BY event_type, hour
                ORDER BY hour DESC
            ";

            return $this->db->query($query, [$deviceId])->getResultArray();
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /** 
     * Get system metrics
     * 
     * @param string $timeframe
     * @return array
     */
    public function getSystemMetrics($timeframe = '24h') {
        try {
            $timeCondition = $this->getTimeframeCondition($timeframe);
            
            $metrics = [
                'user_activity' => $this->getUserActivityMetrics($timeCondition),
                'content_metrics' => $this->getContentMetrics($timeCondition),
                'chat_metrics' => $this->getChatMetrics($timeCondition),
                'moderation_metrics' => $this->getModerationMetrics($timeCondition)
            ];

            return $this->success($metrics);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Get user activity metrics
     * 
     * @param string $timeCondition
     * @return array
     */
    private function getUserActivityMetrics($timeCondition) {
        $query = "
            SELECT 
                COUNT(DISTINCT device_id) as active_users,
                COUNT(*) as total_events
            FROM analytics
            WHERE {$timeCondition}
        ";

        return $this->db->query($query)->getRowArray();
    }

    /**
     * Get content metrics
     * 
     * @param string $timeCondition
     * @return array
     */
    private function getContentMetrics($timeCondition) {
        $query = "
            SELECT 
                COUNT(*) as total_posts,
                COUNT(DISTINCT device_id) as unique_posters,
                AVG(upvotes - downvotes) as avg_post_score
            FROM posts
            WHERE {$timeCondition}
        ";

        return $this->db->query($query)->getRowArray();
    }

    /**
     * Get chat metrics
     * 
     * @param string $timeCondition
     * @return array
     */
    private function getChatMetrics($timeCondition) {
        $query = "
            SELECT 
                COUNT(DISTINCT room_id) as active_chats,
                COUNT(*) as total_messages,
                COUNT(DISTINCT sender_id) as unique_senders
            FROM chat_messages
            WHERE {$timeCondition}
        ";

        return $this->db->query($query)->getRowArray();
    }

    /**
     * Get moderation metrics
     * 
     * @param string $timeCondition
     * @return array
     */
    private function getModerationMetrics($timeCondition) {
        $query = "
            SELECT 
                COUNT(*) as total_reports,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_reports,
                COUNT(CASE WHEN status = 'resolved' THEN 1 END) as resolved_reports
            FROM reports
            WHERE {$timeCondition}
        ";

        return $this->db->query($query)->getRowArray();
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

    /**
     * Get popular tags
     * 
     * @param string $timeframe
     * @param int $limit
     * @return array
     */
    public function getPopularTags($timeframe = '24h', $limit = 10) {
        try {
            $timeCondition = $this->getTimeframeCondition($timeframe);
            
            $query = "
                SELECT 
                    t.name,
                    COUNT(*) as usage_count
                FROM tags t
                JOIN post_tags pt ON t.tag_id = pt.tag_id
                JOIN posts p ON pt.post_id = p.post_id
                WHERE {$timeCondition}
                GROUP BY t.name
                ORDER BY usage_count DESC
                LIMIT ?
            ";

            return $this->db->query($query, [$limit])->getResultArray();
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Get active regions
     * 
     * @param string $timeframe
     * @param int $limit
     * @return array
     */
    public function getActiveRegions($timeframe = '24h', $limit = 10) {
        try {
            $timeCondition = $this->getTimeframeCondition($timeframe);
            
            $query = "
                SELECT 
                    ROUND(latitude, 1) as lat,
                    ROUND(longitude, 1) as lng,
                    COUNT(DISTINCT device_id) as unique_users,
                    COUNT(*) as total_events
                FROM analytics
                WHERE {$timeCondition}
                AND latitude IS NOT NULL 
                AND longitude IS NOT NULL
                GROUP BY ROUND(latitude, 1), ROUND(longitude, 1)
                ORDER BY unique_users DESC
                LIMIT ?
            ";

            return $this->db->query($query, [$limit])->getResultArray();
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

}