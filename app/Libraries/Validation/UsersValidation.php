<?php
namespace App\Libraries\Validation;

class UsersValidation {

    public $routes = [
        'list' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
                'page' => 'required|integer',
                'limit' => 'required|integer',
            ]
        ],
        'view:user_id' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
                'user_id' => 'required|integer',
            ]
        ],
        'create' => [
            'method' => 'POST',
            'authenticate' => true,
            'is_admin' => true,
            'payload' => [
                'full_name' => 'required|max_length[100]',
                'email' => 'required|valid_email|max_length[100]',
                'password' => 'required|valid_password|min_length[8]|max_length[32]',
                'status' => 'required|string|in_list[active,inactive,blocked,pending,suspended]',
            ]
        ],
        'stats' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => []
        ],
        'location' => [
            'method' => 'GET,POST',
            'payload' => [
                'longitude' => 'permit_empty|numeric',
                'latitude' => 'permit_empty|numeric',
            ]
        ],
        'update:user_id' => [
            'method' => 'POST,PUT',
            'authenticate' => true,
            'payload' => [
                'user_id' => 'required|integer',
                'name' => 'string|max_length[32]',
                'gender' => 'string|in_list[Male,Female,Other]',
                'setting' => 'string|max_length[100]',
                'value' => 'max_length[100]',
            ]
        ],
        'export' => [
            'method' => 'GET',
            'authenticate' => true,
            'is_admin' => true,
            'payload' => []
        ],
        'status:user_id' => [
            'method' => 'PUT',
            'authenticate' => true,
            'is_admin' => true,
            'payload' => [
                'user_id' => 'required|integer',
                'status' => 'required|string|in_list[active,inactive,blocked,pending,suspended]',
            ]
        ],
        'delete:user_id' => [
            'method' => 'DELETE',
            'authenticate' => true,
            'is_admin' => true,
            'payload' => [
                'user_id' => 'required|integer',
            ]
        ],
        'goodbye' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => []
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