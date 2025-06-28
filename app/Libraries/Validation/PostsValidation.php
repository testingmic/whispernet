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
        'mark_as_seen' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
                'posts' => 'required|string|max_length[1000]',
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
            'method' => 'GET,POST',
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
                'latitude' => 'permit_empty|max_length[32]',
                'longitude' => 'permit_empty|max_length[32]',
            ]
        ],
        'comments:postId' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
                'postId' => 'permit_empty|numeric|max_length[10]',
            ]
        ],
        'bookmark:postId' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
                'postId' => 'required|numeric|max_length[10]',
            ]
        ],
        'bookmarked' => [
            'method' => 'GET',
            'authenticate' => true,
            'payload' => [
                'limit' => 'permit_empty|numeric|max_length[10]',
                'offset' => 'permit_empty|numeric|max_length[10]',
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
                'latitude' => 'permit_empty|max_length[32]',
                'longitude' => 'permit_empty|max_length[32]',
            ]
        ],
        'removevote:recordId' => [
            'method' => 'POST',
            'authenticate' => true,
            'payload' => [
                'recordId' => 'required|numeric|max_length[10]',
                'section' => 'required|in_list[posts,comments]',
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