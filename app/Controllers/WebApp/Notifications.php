<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;
use App\Models\NotificationsModel;

class Notifications extends WebAppController
{
    
    public function index() {

        $notifications = [];

        $types = ['like', 'comment', 'follow', 'message'];

        $userId = $this->session->get('user_id');

        $notifModel = new NotificationsModel();
        $notifModel->connectToDb('notification');
        $notifications = $notifModel->getUserNotifications($userId);

        $theList = [];
        foreach($notifications as $notification) {
            $notification['time_ago'] = formatTimeAgo($notification['created_at']);
            $theList[] = $notification;
        }

        return $this->templateObject->loadPage('notifications', [
            'pageTitle' => 'Notifications',
            'notifications' => $theList,
            'favicon_color' => 'notifications'
        ]);
    }
}