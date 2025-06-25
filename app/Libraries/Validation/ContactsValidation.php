<?php

namespace App\Libraries\Validation;

class AuthValidation {

    public $routes = [
        'send' => [
            'method' => 'POST',
            'authenticate' => false,
            'payload' => [
                'name' => 'required|max_length[100]',
                'subject' => 'required|max_length[100]',
                'email' => 'required|valid_email|max_length[100]',
                'message' => 'required|max_length[1000]',
                'user_id' => 'permit_empty|integer|max_length[12]',
                'token' => 'permit_empty|string|max_length[40]',
            ]
        ],
        'list' => [
            'method' => 'GET',
            'authenticate' => false,
            'payload' => []
        ]
    ];

}