<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Profile extends WebAppController
{
    /**
     * Display the profile page
     * 
     * @return string
     */
    public function index()
    {
        // Get the current user's data
        $userModel = new \App\Models\UsersModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        // decode the statistics
        $user['statistics'] = !empty($user['statistics']) ? json_decode($user['statistics'], true) : [];

        // Get user stats
        $stats = [
            'posts' => $this->getPostCount($user['statistics']),
            'comments' => $this->getCommentCount($user['statistics']),
            'votes' => $this->getLikeCount($user['statistics'])
        ];

        // Get recent activity
        $recentActivity = $this->getRecentActivity($userId);

        return $this->templateObject->loadPage('profile', [
            'pageTitle' => 'Profile',
            'user' => $user,
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'favicon_color' => 'profile'
        ]);
    }

    /**
     * Display the edit profile page
     * 
     * @return string
     */
    public function edit() {
        return $this->templateObject->loadPage('edit_profile', ['pageTitle' => 'Edit Profile', 'favicon_color' => 'profile']);
    }

    /**
     * Display the my posts page
     * 
     * @return string
     */
    public function posts() {
        return $this->templateObject->loadPage('my_posts', ['pageTitle' => 'My Posts', 'favicon_color' => 'profile']);
    }

    /**
     * Display the my posts page
     * 
     * @return string
     */
    public function replies() {
        return $this->templateObject->loadPage('my_replies', ['pageTitle' => 'My Replies', 'favicon_color' => 'profile']);
    }

    /**
     * Display the my votes page
     * 
     * @return string
     */
    public function votes() {
        return $this->templateObject->loadPage('my_votes', ['pageTitle' => 'My Votes', 'favicon_color' => 'profile']);
    }

    /**
     * Display the my votes page
     * 
     * @return string
     */
    public function saved() {
        return $this->templateObject->loadPage('my_saved', ['pageTitle' => 'My Saved', 'favicon_color' => 'profile']);
    }

    private function getPostCount($statistics)
    {
        return $statistics['posts'] ?? 0;
    }

    private function getCommentCount($statistics)
    {
        return $statistics['comments'] ?? 0;
    }

    private function getLikeCount($statistics)
    {
        return $statistics['votes'] ?? 0;
    }

    private function getRecentActivity($statistics)
    {

    }

} 