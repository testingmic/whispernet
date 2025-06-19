<?php
namespace App\Libraries\Validation;

class ChatsValidation {

    public $routes = [
        'sendMessage' => [
            'method' => 'POST,GET',
            'authenticate' => true,
            'payload' => [
                'roomId' => 'required|integer',
                'sender' => 'required|integer',
                'receiver' => 'required|integer',
                'type' => 'required|string|in_list[individual,group]',
                'message' => 'required|string|max_length[255]',
                'timestamp' => 'required|integer|max_length[16]'
            ]
        ]
    ];
}