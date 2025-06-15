<?php

namespace App\Libraries\Validation;

class PostsValidation {

    public $routes = [
        'list' => [
            'method' => 'POST,GET',
            'authenticate' => true,
            'payload' => []
        ],
        'view:postId' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
                'postId' => 'required|numeric|max_length[10]',
            ]
        ],
        'trending' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => []
        ],
        'nearby' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
                'latitude' => 'permit_empty|max_length[12]',
                'longitude' => 'permit_empty|max_length[12]',
                'radius' => 'permit_empty|numeric|max_length[10]',
                'limit' => 'permit_empty|numeric|max_length[10]',
                'offset' => 'permit_empty|numeric|max_length[10]',
            ]
        ],
        'create' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
                'content' => 'required|max_length[1000]',
                'mediaUrl' => 'permit_empty|max_length[255]',
                'mediaType' => 'permit_empty|max_length[255]',
                'latitude' => 'permit_empty|max_length[12]',
                'longitude' => 'permit_empty|max_length[12]',
            ]
        ]
    ];

}