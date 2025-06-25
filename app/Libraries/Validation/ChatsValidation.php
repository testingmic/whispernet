<?php
namespace App\Libraries\Validation;

class ChatsValidation {

    public $routes = [
        'send' => [
            'method' => 'POST,GET',
            'authenticate' => true,
            'payload' => [
                'roomId' => 'permit_empty|integer',
                'receiver' => 'required|integer',
                'type' => 'permit_empty|string|in_list[individual,group]',
                'message' => 'permit_empty|string|max_length[255]',
                'timestamp' => 'permit_empty|integer|max_length[16]'
            ]
        ],
        'messages' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
                'roomId' => 'permit_empty|integer',
                'receiverId' => 'permit_empty|integer',
            ]
        ],
        'delete' => [
            'method' => 'DELETE',
            'authenticate' => true,
            'payload' => [
                'type' => 'required|integer'
            ]
        ]
    ];
}