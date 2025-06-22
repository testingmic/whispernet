<?php
namespace App\Libraries\Validation;

class UsersValidation {

    public $routes = [
        'update' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
            ]
        ],
        'settings' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
            ]
        ]
    ];
}