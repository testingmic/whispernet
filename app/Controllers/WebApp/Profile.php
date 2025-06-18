<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Profile extends WebAppController
{
    public function index()
    {
        // Get the current user's data
        $userModel = new \App\Models\UsersModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        // Get the post model
        $postModel = new \App\Controllers\Posts\Posts();

        // Get user stats
        $stats = [
            'posts' => $this->getPostCount($userId),
            'comments' => $this->getCommentCount($userId),
            'likes' => $this->getLikeCount($userId)
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

    public function edit() {
        return $this->templateObject->loadPage('edit_profile', ['pageTitle' => 'Edit Profile', 'favicon_color' => 'profile']);
    }

    private function getPostCount($userId)
    {
        return 0;
    }

    private function getCommentCount($userId)
    {
        return 0;
    }

    private function getLikeCount($userId)
    {
        return 0;
    }

    private function getRecentActivity($userId)
    {
        return 0;
    }

} 