<?php

namespace App\Libraries\Validation;

class ReportsValidation {

    public $routes = [
        'list' => [
            'method' => 'POST,GET',
            'authenticate' => true,
            'is_moderator' => true,
            'payload' => [
                'status' => 'permit_empty|max_length[32]',
                'reported_type' => 'permit_empty|max_length[32]',
                'reason' => 'permit_empty|max_length[32]',
                'search' => 'permit_empty|max_length[32]',
                'offset' => 'permit_empty|numeric|max_length[10]',
                'limit' => 'permit_empty|numeric|max_length[10]',
            ]
        ],
        'stats' => [
            'method' => 'POST,GET',
            'authenticate' => true,
            'is_moderator' => true,
        ],
        'view:reportId' => [
            'method' => 'POST,GET',
            'authenticate' => true,
            'is_moderator' => true,
            'payload' => [
                'reportId' => 'required|numeric|max_length[10]',
            ]
        ],
        'vote:reportId' => [
            'method' => 'POST',
            'authenticate' => true,
            'is_moderator' => true,
            'payload' => [
                'reportId' => 'required|numeric|max_length[10]',
            ]
        ],
    ];

}