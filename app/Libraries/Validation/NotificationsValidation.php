<?php
namespace App\Libraries\Validation;

class NotificationsValidation {

    public $routes = [
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