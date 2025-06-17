<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;
use CodeIgniter\Controller;

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
            'posts' => $this->getPostCount($userId, $postModel),
            'comments' => $this->getCommentCount($userId, $postModel),
            'likes' => $this->getLikeCount($userId, $postModel)
        ];

        // Get recent activity
        $recentActivity = $this->getRecentActivity($userId);

        return $this->templateObject->loadPage('profile', [
            'pageTitle' => 'Profile',
            'user' => $user,
            'stats' => $stats,
            'recentActivity' => $recentActivity
        ]);
    }

    public function edit() {
        return $this->templateObject->loadPage('edit_profile', ['pageTitle' => 'Edit Profile']);
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