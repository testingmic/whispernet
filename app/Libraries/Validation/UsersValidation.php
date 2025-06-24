<?php
namespace App\Libraries\Validation;

class UsersValidation {

    public $routes = [
        'update' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
                'name' => 'string|max_length[32]',
                'gender' => 'string|in_list[Male,Female,Other]',
                'setting' => 'string|max_length[100]',
            ]
        ],
        'settings' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
            ]
        ],
        'search' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
            ]
        ]
    ];
}