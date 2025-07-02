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
                return "created_at >= ".date('Y-m-d H:i:s', strtotime('-1 HOUR'));
            case '24h':
                return "created_at >= ".date('Y-m-d H:i:s', strtotime('-24 HOUR'));
            case '7d':
                return "created_at >= ".date('Y-m-d H:i:s', strtotime('-7 DAY'));
            case '30d':
                return "created_at >= ".date('Y-m-d H:i:s', strtotime('-30 DAY'));
            default:
                return "created_at >= ".date('Y-m-d H:i:s', strtotime('-24 HOUR'));
        }
    }

    /**
     * Get comprehensive metrics for a time range
     * 
     * @param string $timeRange
     * @return array
     */
    public function getMetrics($timeRange = 'month')
    {
        $dateFilter = $this->getDateFilter($timeRange);
        
        // Get current period metrics
        $currentMetrics = $this->getCurrentPeriodMetrics($dateFilter);
        
        // Get previous period metrics for growth calculation
        $previousMetrics = $this->getPreviousPeriodMetrics($timeRange);
        
        // Calculate growth percentages
        $growth = $this->calculateGrowth($currentMetrics, $previousMetrics);
        
        return array_merge($currentMetrics, $growth);
    }

    /**
     * Get current period metrics
     * 
     * @param string $dateFilter
     * @return array
     */
    private function getCurrentPeriodMetrics($dateFilter)
    {
        $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 HOUR'));
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM users WHERE created_at >= ?) as totalUsers,
                    (SELECT COUNT(*) FROM posts WHERE created_at >= ?) as totalPosts,
                    (SELECT COUNT(*) FROM comments WHERE created_at >= ?) as totalComments,
                    (SELECT COUNT(*) FROM votes WHERE created_at >= ?) as totalVotes,
                    (SELECT SUM(views) FROM posts WHERE created_at >= ?) as totalPageViews,
                    (SELECT COUNT(*) FROM users WHERE last_login >= '{$oneHourAgo}') as activeUsers,
                    (SELECT COUNT(*) FROM users WHERE user_type = 'moderator') as moderatorsCount,
                    (SELECT COUNT(*) FROM tags) as totalTags";
        
        $result = $this->db->query($sql, array_fill(0, 8, $dateFilter))->getRowArray();
        
        return [
            'totalUsers' => (int)$result['totalUsers'],
            'totalPosts' => (int)$result['totalPosts'],
            'totalComments' => (int)$result['totalComments'],
            'totalVotes' => (int)$result['totalVotes'],
            'totalPageViews' => (int)$result['totalPageViews'],
            'activeUsers' => (int)$result['activeUsers'],
            'moderatorsCount' => (int)$result['moderatorsCount'],
            'totalTags' => (int)$result['totalTags']
        ];
    }

    /**
     * Get previous period metrics
     * 
     * @param string $timeRange
     * @return array
     */
    private function getPreviousPeriodMetrics($timeRange)
    {
        $dateFilter = $this->getPreviousDateFilter($timeRange);
        
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM users WHERE created_at >= ?) as totalUsers,
                    (SELECT COUNT(*) FROM posts WHERE created_at >= ?) as totalPosts,
                    (SELECT COUNT(*) FROM comments WHERE created_at >= ?) as totalComments,
                    (SELECT COUNT(*) FROM votes WHERE created_at >= ?) as totalVotes";
        
        $result = $this->db->query($sql, array_fill(0, 4, $dateFilter))->getRowArray();
        
        return [
            'totalUsers' => (int)$result['totalUsers'],
            'totalPosts' => (int)$result['totalPosts'],
            'totalComments' => (int)$result['totalComments'],
            'totalVotes' => (int)$result['totalVotes']
        ];
    }

    /**
     * Calculate growth percentages
     * 
     * @param array $current
     * @param array $previous
     * @return array
     */
    private function calculateGrowth($current, $previous)
    {
        $growth = [];
        
        foreach ($current as $key => $value) {
            if (isset($previous[$key]) && $previous[$key] > 0) {
                $growth[$key . 'Growth'] = round((($value - $previous[$key]) / $previous[$key]) * 100, 1);
            } else {
                $growth[$key . 'Growth'] = 0;
            }
        }
        
        return $growth;
    }

    /**
     * Get charts data
     * 
     * @param string $timeRange
     * @return array
     */
    public function getCharts($timeRange = 'month')
    {
        return [
            'userGrowth' => $this->getUserGrowth($timeRange),
            'postsActivity' => $this->getPostsActivity($timeRange),
            'engagement' => $this->getEngagementMetrics($timeRange),
            'gender' => $this->getPostsByGender($timeRange),
            'realtimeActivity' => $this->getRealtimeData()
        ];
    }

    /**
     * Get user growth data
     * 
     * @param string $timeRange
     * @return array
     */
    public function getUserGrowth($timeRange = 'month')
    {
        $dateFilter = $this->getDateFilter($timeRange);
        $groupBy = $this->getGroupBy($timeRange);
        
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as count 
                FROM users 
                WHERE created_at >= ? 
                GROUP BY {$groupBy} 
                ORDER BY date";
        
        $result = $this->db->query($sql, [$dateFilter])->getResultArray();
        
        $labels = [];
        $values = [];
        
        foreach ($result as $row) {
            $labels[] = $this->formatDate($row['date'], $timeRange);
            $values[] = (int)$row['count'];
        }
        
        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Get posts activity data
     * 
     * @param string $timeRange
     * @return array
     */
    public function getPostsActivity($timeRange = 'month')
    {
        $dateFilter = $this->getDateFilter($timeRange);
        $groupBy = $this->getGroupBy($timeRange);
        
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as count 
                FROM posts 
                WHERE created_at >= ? 
                GROUP BY {$groupBy} 
                ORDER BY date";
        
        $result = $this->db->query($sql, [$dateFilter])->getResultArray();
        
        $labels = [];
        $values = [];
        
        foreach ($result as $row) {
            $labels[] = $this->formatDate($row['date'], $timeRange);
            $values[] = (int)$row['count'];
        }
        
        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Get engagement metrics
     * 
     * @param string $timeRange
     * @return array
     */
    public function getEngagementMetrics($timeRange = 'month')
    {
        $dateFilter = $this->getDateFilter($timeRange);
        
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM posts WHERE created_at >= ?) as posts,
                    (SELECT COUNT(*) FROM comments WHERE created_at >= ?) as comments,
                    (SELECT COUNT(*) FROM votes WHERE created_at >= ?) as votes,
                    (SELECT SUM(views) FROM posts WHERE created_at >= ?) as views";
        
        $result = $this->db->query($sql, array_fill(0, 4, $dateFilter))->getRowArray();
        
        return [
            'posts' => (int)$result['posts'],
            'comments' => (int)$result['comments'],
            'votes' => (int)$result['votes'],
            'views' => (int)$result['views']
        ];
    }

    /**
     * Get posts by gender
     * 
     * @param string $timeRange
     * @return array
     */
    public function getPostsByGender($timeRange = 'month')
    {
        $dateFilter = $this->getDateFilter($timeRange);
        
        $sql = "SELECT 
                    COALESCE(u.gender, 'not_specified') as gender,
                    COUNT(p.post_id) as count
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.user_id
                WHERE p.created_at >= ?
                GROUP BY u.gender";
        
        $result = $this->db->query($sql, [$dateFilter])->getResultArray();
        
        $data = [
            'male' => 0,
            'female' => 0,
            'other' => 0,
            'notSpecified' => 0
        ];
        
        foreach ($result as $row) {
            switch ($row['gender']) {
                case 'male':
                    $data['male'] = (int)$row['count'];
                    break;
                case 'female':
                    $data['female'] = (int)$row['count'];
                    break;
                case 'other':
                    $data['other'] = (int)$row['count'];
                    break;
                default:
                    $data['notSpecified'] = (int)$row['count'];
                    break;
            }
        }
        
        return $data;
    }

    /**
     * Get real-time activity data (last 24 hours)
     * 
     * @return array
     */
    public function getRealtimeData()
    {
        try {
            $twentyFourHoursAgo = date('Y-m-d H:i:s', strtotime('-24 hours'));
            $sql = "SELECT 
                        HOUR(created_at) as hour,
                        COUNT(*) as count
                    FROM posts 
                    WHERE created_at >= '{$twentyFourHoursAgo}'
                    GROUP BY HOUR(created_at)
                    ORDER BY hour";
            
            $result = $this->db->query($sql)->getResultArray();
            
            $labels = [];
            $values = [];
            
            // Initialize 24 hours
            for ($i = 0; $i < 24; $i++) {
                $labels[] = sprintf('%02d:00', $i);
                $values[] = 0;
            }
        
            // Fill in actual data
            foreach ($result as $row) {
                $hour = (int)$row['hour'];
                $values[$hour] = (int)$row['count'];
            }
            
            return ['labels' => $labels, 'values' => $values];
        } catch(DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get active users count
     * 
     * @return int
     */
    public function getActiveUsers()
    {
        try {
            
            $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 HOUR'));
            $sql = "SELECT COUNT(*) as count FROM users WHERE last_login >= '{$oneHourAgo}'";
            $result = $this->db->query($sql)->getRowArray();
            
            return (int)$result['count'];

        } catch(DatabaseException $e) {
            return 0;
        }
    }

    /**
     * Get top performing content
     * 
     * @param string $timeRange
     * @return array
     */
    public function getTopContent($timeRange = 'month')
    {
        $dateFilter = $this->getDateFilter($timeRange);
        
        $sql = "SELECT 
                    p.post_id,
                    p.content,
                    u.username as author,
                    SUM(p.upvotes - p.downvotes) as votes,
                    SUM(p.comments_count) as comments,
                    SUM(p.views) as views
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.user_id
                WHERE p.created_at >= ?
                GROUP BY p.post_id
                ORDER BY (votes + comments + views) DESC
                LIMIT 10";
        
        $result = $this->db->query($sql, [$dateFilter])->getResultArray();
        
        return array_map(function($row) {
            return [
                'id' => $row['post_id'],
                'title' => substr($row['content'], 0, 50) . '...',
                'author' => $row['author'],
                'votes' => (int)$row['votes'],
                'comments' => (int)$row['comments'],
                'views' => (int)$row['views']
            ];
        }, $result);
    }

    /**
     * Get top locations
     * 
     * @param string $timeRange
     * @return array
     */
    public function getTopLocations($timeRange = 'month')
    {
        $dateFilter = $this->getDateFilter($timeRange);
        
        $sql = "SELECT 
                    u.location,
                    COUNT(p.post_id) as posts
                FROM posts p
                JOIN users u ON p.user_id = u.user_id
                WHERE p.created_at >= ? AND u.location IS NOT NULL AND u.location != ''
                GROUP BY u.location
                ORDER BY posts DESC
                LIMIT 10";
        
        $result = $this->db->query($sql, [$dateFilter])->getResultArray();
        
        return array_map(function($row) {
            $location = explode(',', $row['location']);
            return [
                'city' => trim($location[0] ?? 'Unknown'),
                'country' => trim($location[1] ?? ''),
                'posts' => (int)$row['posts']
            ];
        }, $result);
    }

    /**
     * Get popular tags
     * 
     * @param string $timeRange
     * @return array
     */
    public function getPopularTags($timeRange = 'month')
    {
        $dateFilter = $this->getDateFilter($timeRange);
        
        $sql = "SELECT 
                    t.name,
                    COUNT(pt.post_id) as posts,
                    COUNT(pt.post_id) as usage
                FROM tags t
                JOIN post_tags pt ON t.tag_id = pt.tag_id
                JOIN posts p ON pt.post_id = p.post_id
                WHERE p.created_at >= ?
                GROUP BY t.tag_id
                ORDER BY posts DESC
                LIMIT 10";
        
        $result = $this->db->query($sql, [$dateFilter])->getResultArray();
        
        return array_map(function($row) {
            return [
                'name' => $row['name'],
                'posts' => (int)$row['posts'],
                'usage' => (int)$row['usage']
            ];
        }, $result);
    }

    /**
     * Get hourly activity
     * 
     * @return array
     */
    public function getHourlyActivity()
    {
        try {
            $sql = "SELECT 
                    HOUR(created_at) as hour,
                    COUNT(*) as count
                FROM posts 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY HOUR(created_at)
                ORDER BY hour";
        
            return $this->db->query($sql)->getResultArray();
        } catch(DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get daily activity
     * 
     * @return array
     */
    public function getDailyActivity()
    {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as count
                FROM posts 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(created_at)
                ORDER BY date";
        
        return $this->db->query($sql)->getResultArray();
    }

    /**
     * Get weekly activity
     * 
     * @return array
     */
    public function getWeeklyActivity()
    {
        $sql = "SELECT 
                    YEARWEEK(created_at) as week,
                    COUNT(*) as count
                FROM posts 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 WEEK)
                GROUP BY YEARWEEK(created_at)
                ORDER BY week";
        
        return $this->db->query($sql)->getResultArray();
    }

    /**
     * Get monthly activity
     * 
     * @return array
     */
    public function getMonthlyActivity()
    {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as count
                FROM posts 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month";
        
        return $this->db->query($sql)->getResultArray();
    }

    /**
     * Get platform statistics
     * 
     * @return array
     */
    public function getPlatformStats()
    {
        $twentyFourHoursAgo = date('Y-m-d H:i:s', strtotime('-24 hours'));
        $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 HOUR'));

        $sql = "SELECT 
                    (SELECT COUNT(*) FROM users) as totalUsers,
                    (SELECT COUNT(*) FROM posts) as totalPosts,
                    (SELECT COUNT(*) FROM comments) as totalComments,
                    (SELECT COUNT(*) FROM votes) as totalVotes,
                    (SELECT COUNT(*) FROM tags) as totalTags,
                    (SELECT COUNT(*) FROM users WHERE user_type = 'moderator') as moderators,
                    (SELECT COUNT(*) FROM users WHERE last_login >= '{$oneHourAgo}') as activeUsers,
                    (SELECT COUNT(*) FROM posts WHERE created_at >= '{$twentyFourHoursAgo}') as postsToday,
                    (SELECT COUNT(*) FROM users WHERE created_at >= '{$twentyFourHoursAgo}') as newUsersToday";
        
        return $this->db->query($sql)->getRowArray();
    }

    /**
     * Export analytics data
     * 
     * @param string $timeRange
     * @param string $format
     * @return array
     */
    public function exportData($timeRange = 'month', $format = 'json')
    {
        $data = [
            'metrics' => $this->getMetrics($timeRange),
            'charts' => $this->getCharts($timeRange),
            'topContent' => $this->getTopContent($timeRange),
            'locations' => $this->getTopLocations($timeRange),
            'tags' => $this->getPopularTags($timeRange),
            'exported_at' => date('Y-m-d H:i:s'),
            'time_range' => $timeRange
        ];
        
        if ($format === 'csv') {
            return $this->convertToCsv($data);
        }
        
        return $data;
    }

    /**
     * Convert data to CSV format
     * 
     * @param array $data
     * @return string
     */
    private function convertToCsv($data)
    {
        // Implementation for CSV conversion
        return json_encode($data); // Placeholder
    }

    /**
     * Get date filter based on time range
     * 
     * @param string $timeRange
     * @return string
     */
    private function getDateFilter($timeRange)
    {
        switch ($timeRange) {
            case 'today':
                return 'DATE(NOW())';
            case 'week':
                return 'DATE_SUB(NOW(), INTERVAL 7 DAY)';
            case 'month':
                return 'DATE_SUB(NOW(), INTERVAL 30 DAY)';
            case 'year':
                return 'DATE_SUB(NOW(), INTERVAL 1 YEAR)';
            default:
                return 'DATE_SUB(NOW(), INTERVAL 30 DAY)';
        }
    }

    /**
     * Get previous date filter
     * 
     * @param string $timeRange
     * @return string
     */
    private function getPreviousDateFilter($timeRange)
    {
        switch ($timeRange) {
            case 'today':
                return 'DATE_SUB(NOW(), INTERVAL 1 DAY)';
            case 'week':
                return 'DATE_SUB(NOW(), INTERVAL 14 DAY)';
            case 'month':
                return 'DATE_SUB(NOW(), INTERVAL 60 DAY)';
            case 'year':
                return 'DATE_SUB(NOW(), INTERVAL 2 YEAR)';
            default:
                return 'DATE_SUB(NOW(), INTERVAL 60 DAY)';
        }
    }

    /**
     * Get GROUP BY clause based on time range
     * 
     * @param string $timeRange
     * @return string
     */
    private function getGroupBy($timeRange)
    {
        switch ($timeRange) {
            case 'today':
                return 'HOUR(created_at)';
            case 'week':
                return 'DATE(created_at)';
            case 'month':
                return 'DATE(created_at)';
            case 'year':
                return 'DATE_FORMAT(created_at, "%Y-%m")';
            default:
                return 'DATE(created_at)';
        }
    }

    /**
     * Format date for display
     * 
     * @param string $date
     * @param string $timeRange
     * @return string
     */
    private function formatDate($date, $timeRange)
    {
        switch ($timeRange) {
            case 'today':
                return date('H:i', strtotime($date));
            case 'week':
            case 'month':
                return date('M j', strtotime($date));
            case 'year':
                return date('M Y', strtotime($date));
            default:
                return date('M j', strtotime($date));
        }
    }
}