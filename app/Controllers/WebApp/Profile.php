<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;
use App\Models\PostsModel;
use App\Models\UsersModel;

class Profile extends WebAppController
{
    /**
     * Display the profile page
     * 
     * @return string
     */
    public function index()
    {
        // verify if the user is logged in
        $this->verifyLogin();

        // Get the current user's data
        $userModel = new \App\Models\UsersModel();
        $userId = $this->loogedUserId;
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

        $data = [
            'pageTitle' => 'Profile',
            'user' => $user,
            'settings' => formatUserSettings($userModel->getUserSettings($userId)),
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'favicon_color' => 'profile'
        ];

        // return the user profile information
        return $this->templateObject->loadPage('profile', $data);
    }

    /**
     * Display the edit profile page
     * 
     * @return string
     */
    public function edit() {

        // verify if the user is logged in
        $this->verifyLogin();
        
        // get the user model
        $userModel = new \App\Models\UsersModel();
        $userId = $this->loogedUserId;

        // get the user
        $user = $userModel->find($userId);

        // get the user settings
        $userSettings = $userModel->getUserSettings($userId);

        return $this->templateObject->loadPage('edit_profile', [
            'pageTitle' => 'Edit Profile', 
            'favicon_color' => 'profile',
            'settings' => formatUserSettings($userSettings),
            'user' => $user
        ]);
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

        $postsModel = new PostsModel();
        
        $this->payload['limit'] = $this->defaultLimit;
        $this->payload['userId'] = $this->session->get('user_id');

        $postsModel->payload = $this->payload;
        $postsModel->payload['request_data'] = 'my_bookmarks';
        $bookmarkedPosts = formatPosts($postsModel->list()['posts'] ?? []);

        return $this->templateObject->loadPage('my_saved', ['pageTitle' => 'My Saved', 'favicon_color' => 'profile', 'bookmarkedPosts' => $bookmarkedPosts]);
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