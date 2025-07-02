<?php
namespace App\Controllers\Analytics;

use App\Controllers\LoadController;
use App\Libraries\Routing;

class Analytics extends LoadController {

    /**
     * Get comprehensive analytics data
     * 
     * @return array
     */
    public function dashboard()
    {
        $timeRange = $this->payload['timeRange'] ?? 'month';
        
        // Get all analytics data
        $metrics = $this->analyticsModel->getMetrics($timeRange);
        $charts = $this->analyticsModel->getCharts($timeRange);
        $topContent = $this->analyticsModel->getTopContent($timeRange);
        $locations = $this->analyticsModel->getTopLocations($timeRange);
        $tags = $this->analyticsModel->getPopularTags($timeRange);

        return Routing::success([
            'metrics' => $metrics,
            'charts' => $charts,
            'topContent' => $topContent,
            'locations' => $locations,
            'tags' => $tags
        ]);
    }

    /**
     * Get real-time analytics data
     * 
     * @return array
     */
    public function realtime()
    {
        $realtimeData = $this->analyticsModel->getRealtimeData();
        $activeUsers = $this->analyticsModel->getActiveUsers();

        return Routing::success([
            'labels' => $realtimeData['labels'],
            'values' => $realtimeData['values'],
            'activeUsers' => $activeUsers
        ]);
    }

    /**
     * Get user growth data
     * 
     * @return array
     */
    public function userGrowth()
    {
        $timeRange = $this->payload['timeRange'] ?? 'month';
        $data = $this->analyticsModel->getUserGrowth($timeRange);
        
        return Routing::success($data);
    }

    /**
     * Get posts activity data
     * 
     * @return array
     */
    public function postsActivity()
    {
        $timeRange = $this->payload['timeRange'] ?? 'month';
        $data = $this->analyticsModel->getPostsActivity($timeRange);
        
        return Routing::success($data);
    }

    /**
     * Get engagement metrics
     * 
     * @return array
     */
    public function engagement()
    {
        $timeRange = $this->payload['timeRange'] ?? 'month';
        $data = $this->analyticsModel->getEngagementMetrics($timeRange);
        
        return Routing::success($data);
    }

    /**
     * Get posts by gender
     * 
     * @return array
     */
    public function postsByGender()
    {
        $timeRange = $this->payload['timeRange'] ?? 'month';
        $data = $this->analyticsModel->getPostsByGender($timeRange);
        
        return Routing::success($data);
    }

    /**
     * Get top locations
     * 
     * @return array
     */
    public function topLocations()
    {
        $timeRange = $this->payload['timeRange'] ?? 'month';
        $data = $this->analyticsModel->getTopLocations($timeRange);
        
        return Routing::success($data);
    }

    /**
     * Get popular tags
     * 
     * @return array
     */
    public function popularTags()
    {
        $timeRange = $this->payload['timeRange'] ?? 'month';
        $data = $this->analyticsModel->getPopularTags($timeRange);
        
        return Routing::success($data);
    }

    /**
     * Get top performing content
     * 
     * @return array
     */
    public function topContent()
    {
        $timeRange = $this->payload['timeRange'] ?? 'month';
        $data = $this->analyticsModel->getTopContent($timeRange);
        
        return Routing::success($data);
    }

    /**
     * Get hourly activity data
     * 
     * @return array
     */
    public function hourlyActivity()
    {
        $data = $this->analyticsModel->getHourlyActivity();
        
        return Routing::success($data);
    }

    /**
     * Get daily activity data
     * 
     * @return array
     */
    public function dailyActivity()
    {
        $data = $this->analyticsModel->getDailyActivity();
        
        return Routing::success($data);
    }

    /**
     * Get weekly activity data
     * 
     * @return array
     */
    public function weeklyActivity()
    {
        $data = $this->analyticsModel->getWeeklyActivity();
        
        return Routing::success($data);
    }

    /**
     * Get monthly activity data
     * 
     * @return array
     */
    public function monthlyActivity()
    {
        $data = $this->analyticsModel->getMonthlyActivity();
        
        return Routing::success($data);
    }

    /**
     * Get platform statistics
     * 
     * @return array
     */
    public function platformStats()
    {
        $stats = $this->analyticsModel->getPlatformStats();
        
        return Routing::success($stats);
    }

    /**
     * Export analytics data
     * 
     * @return array
     */
    public function export()
    {
        $timeRange = $this->payload['timeRange'] ?? 'month';
        $format = $this->payload['format'] ?? 'json';
        
        $data = $this->analyticsModel->exportData($timeRange, $format);
        
        return Routing::success($data);
    }

    /**
     * Log pageview
     * 
     * @return void
     */
    public function pageview() {
        // get the payload
        $page = $this->payload['page'] ?? '';
        $userUUID = $this->payload['userUUID'] ?? '';
        $userID = $this->payload['user_id'] ?? 0;
        $userAgent = $this->payload['user_agent'] ?? '';
        $referer = $this->payload['referer'] ?? '';

        if(!empty($userAgent)) {
            foreach(['facebook', 'snapchat', 'instagram', 'tiktok', 'google'] as $platform) {
                if(strpos(strtolower($userAgent), $platform) !== false) {
                    $referer = 'https://www.'.$platform.'.com';
                }
            }
        }

        if(empty($userUUID)) return Routing::success('Required userUUID missing from payload.');

        $this->analyticsModel->logPageview($page, $userUUID, $userID, $userAgent, $referer);

        return Routing::success('Pageview logged');
    }
    
}
?>