<?php
namespace App\Libraries\Validation;

class NotificationsValidation {

    public $routes = [
        'list' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => []
        ],
        'notifyall' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
                'item' => 'required|string',
                'message' => 'required|string',
            ]
        ],
        'delete:notification_id' => [
            'method' => 'DELETE',
            'authenticate' => true,
            'payload' => [
                'notification_id' => 'required|integer',
            ]
        ],
        'allread' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => []
        ],
        'read:notification_id' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
                'notification_id' => 'required|integer',
            ]
        ],
        'recent' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => []
        ],
        'push' => [
            'method' => 'GET',
            'payload' => [
                'userId' => 'required|integer',
            ]
        ]
    ];
}