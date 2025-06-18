<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Notifications extends WebAppController
{
    
    public function index() {

        $notifications = [];

        $types = ['like', 'comment', 'follow', 'message'];
        
        for($i = 0; $i < 10; $i++) {
            $notifications[] = [
                'id' => $i,
                'type' => $types[array_rand($types)],
                'message' => 'John Doe liked your post',
                'time' => date('H:i:s', strtotime('-' . $i . ' minutes')),
                'read' => false,
                'time_ago' => '30min ago'
            ];
        }

        return $this->templateObject->loadPage('notifications', [
            'pageTitle' => 'Notifications',
            'notifications' => $notifications,
            'favicon_color' => 'notifications'
        ]);
    }
}