<?php

namespace App\Libraries\Validation;

class TagsValidation {

    public $routes = [
        'popular' => [
            'method' => 'GET',
            'authenticate' => false,
            'payload' => []
        ],
        'posts:hashtag' => [
            'method' => 'GET',
            'authenticate' => false,
            'payload' => [
                'hashtag' => 'required|string|max_length[12]'
                
            ]
        ],
        'postsbyid:tag_id' => [
            'method' => 'GET',
            'authenticate' => false,
            'payload' => [
                'tag_id' => 'required|integer|max_length[12]'
            ]
        ]
    ];
}