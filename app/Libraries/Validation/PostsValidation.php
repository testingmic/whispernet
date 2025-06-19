<?php

namespace App\Libraries\Validation;

class PostsValidation {

    public $routes = [
        'list' => [
            'method' => 'POST,GET',
            'authenticate' => true,
            'payload' => [
                'location' => 'permit_empty|max_length[32]'
            ]
        ],
        'delete:postId' => [
            'method' => 'DELETE',
            'authenticate' => true,
            'payload' => [
                'postId' => 'required|numeric|max_length[10]',
            ]
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
            'payload' => [
                'location' => 'permit_empty|max_length[32]'
            ]
        ],
        'nearby' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
                'location' => 'permit_empty|max_length[32]',
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
                'content' => 'permit_empty|max_length[300]',
                'mediaUrl' => 'permit_empty|max_length[255]',
                'mediaType' => 'permit_empty|max_length[255]',
                'latitude' => 'permit_empty|max_length[12]',
                'longitude' => 'permit_empty|max_length[12]',
            ]
        ],
        'comments:postId' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
                'postId' => 'permit_empty|numeric|max_length[10]',
            ]
        ],
        'notify' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
                'postId' => 'required|numeric|max_length[10]',
            ]
        ],
        'vote' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
                'recordId' => 'required|numeric|max_length[10]',
                'section' => 'required|in_list[posts,comments]',
                'direction' => 'required|in_list[up,down]',
            ]
        ],
        'comment' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
                'postId' => 'required|numeric|max_length[10]',
                'content' => 'required|max_length[300]',
                'mediaUrl' => 'permit_empty|max_length[255]',
                'mediaType' => 'permit_empty|max_length[255]',
                'latitude' => 'permit_empty|max_length[12]',
                'longitude' => 'permit_empty|max_length[12]',
            ]
        ],

        'deletecomment:commentId' => [
            'method' => 'DELETE',
            'authenticate' => true,
            'payload' => [
                'commentId' => 'required|numeric|max_length[10]',
            ]
        ],
    ];

}