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
                'value' => 'max_length[100]',
            ]
        ],
        'profile' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => []
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
                'query' => 'required|string|max_length[32]|min_length[2]'
            ]
        ]
    ];
}