<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;
use App\Models\NotificationsModel;

class Notifications extends WebAppController
{
    
    public function index() {

        // verify if the user is logged in
        $this->verifyLogin();

        $userId = $this->loogedUserId;

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