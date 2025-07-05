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
        try {
            $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 HOUR'));
            $sql = "SELECT 
                        (SELECT COUNT(*) FROM users WHERE created_at >= {$dateFilter}) as totalUsers,
                        (SELECT COUNT(*) FROM posts WHERE created_at >= {$dateFilter}) as totalPosts,
                        (SELECT COUNT(*) FROM comments WHERE created_at >= {$dateFilter}) as totalComments,
                        (SELECT SUM(upvotes + downvotes) FROM posts WHERE created_at >= {$dateFilter}) as totalPostVotes,
                        (SELECT SUM(upvotes + downvotes) FROM comments WHERE created_at >= {$dateFilter}) as totalCommentVotes,
                        (SELECT SUM(views) FROM posts WHERE created_at >= {$dateFilter}) as totalPageViews,
                        (SELECT COUNT(*) FROM users WHERE last_login >= '{$oneHourAgo}') as activeUsers,
                        (SELECT COUNT(*) FROM users WHERE user_type = 'moderator') as moderatorsCount,
                        (SELECT COUNT(*) FROM hashtags) as totalTags";
            
            $result = $this->db->query($sql)->getRowArray();
            
            return [
                'totalUsers' => (int)$result['totalUsers'],
                'totalPosts' => (int)$result['totalPosts'],
                'totalComments' => (int)$result['totalComments'],
                'totalVotes' => (int)$result['totalPostVotes'] + (int)$result['totalCommentVotes'],
                'totalPostVotes' => (int)$result['totalPostVotes'],
                'totalCommentVotes' => (int)$result['totalCommentVotes'],
                'totalPageViews' => (int)$result['totalPageViews'],
                'activeUsers' => (int)$result['activeUsers'],
                'moderatorsCount' => (int)$result['moderatorsCount'],
                'totalTags' => (int)$result['totalTags']
            ];
        } catch(DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get previous period metrics
     * 
     * @param string $timeRange
     * @return array
     */
    private function getPreviousPeriodMetrics($timeRange)
    {
        try {
            $dateFilter = $this->getPreviousDateFilter($timeRange);
            
            $sql = "SELECT 
                        (SELECT COUNT(*) FROM users WHERE created_at >= {$dateFilter}) as totalUsers,
                        (SELECT COUNT(*) FROM posts WHERE created_at >= {$dateFilter}) as totalPosts,
                        (SELECT COUNT(*) FROM comments WHERE created_at >= {$dateFilter}) as totalComments,
                        (SELECT SUM(upvotes + downvotes) FROM posts WHERE created_at >= {$dateFilter}) as totalPostVotes,
                        (SELECT SUM(upvotes + downvotes) FROM comments WHERE created_at >= {$dateFilter}) as totalCommentVotes";
            
            $result = $this->db->query($sql)->getRowArray();
            
            return [
                'totalUsers' => (int)$result['totalUsers'],
                'totalPosts' => (int)$result['totalPosts'],
                'totalComments' => (int)$result['totalComments'],
                'totalVotes' => (int)$result['totalPostVotes'] + (int)$result['totalCommentVotes'],
                'totalPostVotes' => (int)$result['totalPostVotes'],
                'totalCommentVotes' => (int)$result['totalCommentVotes'],
            ];
        } catch(DatabaseException $e) {
            return [];
        }
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
     * Regroup data by hour
     * 
     * @param array $result
     * @return array
     */
    private function regroupData($result, $groupBy) {
        $finalResult = [];
        $regroupData = [];
        foreach($result as $row) {
            $hour = date($groupBy, strtotime($row['created_at']));
            if(!isset($regroupData[$hour])) {
                $regroupData[$hour] = 0;
            }
            $value = $regroupData[$hour] + $row['count'];
            $finalResult[] = [
                'date' => $row['created_at'],
                'count' => $value
            ];
        }
        unset($result);
        return $finalResult;
    }

    /**
     * Get user growth data
     * 
     * @param string $timeRange
     * @return array
     */
    public function getUserGrowth($timeRange = 'month')
    {
        try {

            $dateFilter = $this->getDateFilter($timeRange);
            $groupBy = $this->getGroupBy($timeRange);

            $groupByQuery = false;
            if($groupBy == 'HOUR(created_at)') {
                $groupByQuery = true;
                $groupBy = "DATE(created_at)";
            }
            
            $sql = "SELECT DATE(created_at) as date, created_at, COUNT(*) as count 
                    FROM users 
                    WHERE created_at >= {$dateFilter}
                    GROUP BY {$groupBy} 
                    ORDER BY date";
            
            $result = $this->db->query($sql)->getResultArray();
            
            $labels = [];
            $values = [];

            // regroup data if it is grouped by hour
            if($groupByQuery) {
                $result = $this->regroupData($result, 'H');
            }
        
            foreach ($result as $row) {
                $labels[] = $this->formatDate($row['date'], $timeRange);
                $values[] = (int)$row['count'];
            }
            
            return ['labels' => $labels, 'values' => $values];

        } catch(DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get posts activity data
     * 
     * @param string $timeRange
     * @return array
     */
    public function getPostsActivity($timeRange = 'month')
    {
        try {
            $dateFilter = $this->getDateFilter($timeRange);
            $groupBy = $this->getGroupBy($timeRange);

            $groupByQuery = false;
            if($groupBy == 'HOUR(created_at)') {
                $groupByQuery = true;
                $groupBy = "DATE(created_at)";
            }
            
            $sql = "SELECT DATE(created_at) as date, created_at, COUNT(*) as count 
                    FROM posts 
                    WHERE created_at >= {$dateFilter}
                    GROUP BY {$groupBy} 
                    ORDER BY date";
            
            $result = $this->db->query($sql)->getResultArray();
            
            $labels = [];
            $values = [];

            if($groupByQuery) {
                $result = $this->regroupData($result, 'H');
            }
            
            foreach ($result as $row) {
                $labels[] = $this->formatDate($row['date'], $timeRange);
                $values[] = (int)$row['count'];
            }
            
            return ['labels' => $labels, 'values' => $values];
        } catch(DatabaseException $e) {
            return [];
        }
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
                case 'Male':
                    $data['male'] = (int)$row['count'];
                    break;
                case 'Female':
                    $data['female'] = (int)$row['count'];
                    break;
                case 'Other':
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
            $twentyFourHoursAgo = date('Y-m-d H:i:s', strtotime('-24 hour'));
            $sql = "SELECT 
                        created_at, COUNT(*) as count
                    FROM posts 
                    WHERE created_at >= '{$twentyFourHoursAgo}'
                    GROUP BY created_at
                    ORDER BY created_at";
            
            $result = $this->db->query($sql)->getResultArray();

            $regroupData = [];
            foreach($result as $row) {
                $hour = date('H', strtotime($row['created_at']));
                if(!isset($regroupData[$hour])) {
                    $regroupData[$hour] = 0;
                }
                $regroupData[$hour] += $row['count'];
            }
            
            // Initialize 24 hours
            for ($i = 0; $i < 24; $i++) {
                $labels[] = sprintf('%02d:00', $i);
                $values[$i] = $regroupData[$i] ?? 0;
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
            $result = $this->db->query("SELECT COUNT(*) as count FROM users WHERE last_login >= '{$oneHourAgo}'")->getRowArray();
            
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
                WHERE p.created_at >= {$dateFilter}
                GROUP BY p.post_id
                ORDER BY p.upvotes, p.comments_count DESC
                LIMIT 10";
        
        $result = $this->db->query($sql)->getResultArray();
        
        return array_map(function($row) {
            // Clean and validate UTF-8 content
            $content = $this->cleanUtf8String($row['content'] ?? '');
            $author = $this->cleanUtf8String($row['author'] ?? '');
            
            return [
                'id' => $row['post_id'],
                'title' => mb_substr($content, 0, 50, 'UTF-8') . '...',
                'author' => $author,
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
                    p.city AS location, p.country AS country,
                    COUNT(p.post_id) AS posts
                FROM posts p
                WHERE 
                    p.created_at >= {$dateFilter}
                    AND p.city IS NOT NULL
                    AND p.city != ''
                GROUP BY p.city, p.country
                ORDER BY posts DESC
                LIMIT 10;";
        
        $result = $this->db->query($sql)->getResultArray();
        
        return array_map(function($row) {
            return [
                'city' => $this->cleanUtf8String($row['location'] ?? 'Unknown'),
                'country' => $this->cleanUtf8String($row['country'] ?? ''),
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
                    COUNT(pt.post_id) as ausage
                FROM hashtags t
                JOIN post_hashtags pt ON t.id = pt.hashtag_id
                JOIN posts p ON pt.post_id = p.post_id
                WHERE p.created_at >= {$dateFilter}
                GROUP BY t.id
                ORDER BY posts DESC
                LIMIT 10";
        
        $result = $this->db->query($sql)->getResultArray();
        
        return array_map(function($row) {
            return [
                'name' => $this->cleanUtf8String($row['name']),
                'posts' => (int)$row['posts'],
                'usage' => (int)$row['ausage']
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

            $sevenDaysAgo = date('Y-m-d H:i:s', strtotime('-7 days'));

            $sql = "SELECT 
                    created_at, COUNT(*) as count
                FROM posts 
                WHERE created_at >= '{$sevenDaysAgo}'
                GROUP BY created_at
                ORDER BY created_at";
            $result = $this->db->query($sql)->getResultArray();

            $regroupData = [];
            foreach($result as $row) {
                $hour = date('H', strtotime($row['created_at']));
                if(!isset($regroupData[$hour])) {
                    $regroupData[$hour] = 0;
                }
                $regroupData[$hour] += $row['count'];
            }

            foreach($regroupData as $hour => $value) {
                $finalResult[] = [
                    'hour' => $hour,
                    'count' => $value
                ];
            }
        
            return $finalResult ?? [];
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
                return "'".date('Y-m-d')."'";
            case 'week':
                return "'".date('Y-m-d', strtotime('-7 DAY'))."'";
            case 'month':
                return "'".date('Y-m-d', strtotime('-30 DAY'))."'";
            case 'year':
                return "'".date('Y-m-d', strtotime('-1 YEAR'))."'";
            default:
                return "'".date('Y-m-d', strtotime('-30 DAY'))."'";
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
                return "'".date('Y-m-d')."'";
            case 'week':
                return "'".date('Y-m-d', strtotime('-4 DAY'))."'";
            case 'month':
                return "'".date('Y-m-d', strtotime('-60 DAY'))."'";
            case 'year':
                return "'".date('Y-m-d', strtotime('-2 YEAR'))."'";
            default:
                return "'".date('Y-m-d', strtotime('-60 DAY'))."'";
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

    /**
     * Clean and validate UTF-8 string
     * 
     * @param string $string
     * @return string
     */
    private function cleanUtf8String($string)
    {
        if (empty($string)) {
            return '';
        }

        // Remove invalid UTF-8 characters
        $string = iconv('UTF-8', 'UTF-8//IGNORE', $string);
        
        // Remove null bytes and other control characters
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $string);
        
        // Ensure proper UTF-8 encoding
        if (!mb_check_encoding($string, 'UTF-8')) {
            $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        }
        
        return trim($string);
    }
}