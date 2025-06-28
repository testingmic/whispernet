<?php
namespace App\Libraries\Validation;

class ChatsValidation {

    public $routes = [
        'rooms' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => []
        ],
        'join' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
                'roomId' => 'required|integer',
                'roomUUID' => 'required|string|max_length[64]',
            ]
        ],
        'send' => [
            'method' => 'POST,GET',
            'authenticate' => true,
            'payload' => [
                'roomId' => 'permit_empty|integer',
                'receiver' => 'required|integer',
                'uuid' => 'permit_empty|max_length[64]',
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
        'delete:roomId' => [
            'method' => 'DELETE',
            'authenticate' => true,
            'payload' => [
                'roomId' => 'required|integer',
                'type' => 'required|string|in_list[group,individual]'
            ]
        ]
    ];
}