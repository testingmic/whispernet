<?php
namespace App\Libraries\Validation;

class NotificationsValidation {

    public $routes = [
        'recent' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => []
        ]
    ];
}