<?php

namespace App\Libraries\Validation;

class TagsValidation {

    public $routes = [
        'popular' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => []
        ],
        'posts:hashtag' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
                'hashtag' => 'required|string|max_length[12]'
                
            ]
        ],
        'postsbyid:tag_id' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
                'tag_id' => 'required|integer|max_length[12]'
            ]
        ]
    ];
}