<?php
namespace App\Libraries\Validation;

class FeedbackValidation {

    public $routes = [
        'admin' => [
            'method' => 'GET',
            'authenticate' => true,
            'is_moderator' => true,
            'payload' => []
        ],
        'submit' => [
            'method' => 'POST',
            'payload' => [
                'feedback_type' => 'required|string',
                'priority' => 'required|string',
                'subject' => 'required|string|max_length[200]',
                'description' => 'required|string|max_length[2000]',
                'contact_preference' => 'required|string'
            ]
        ],
        'status:feedback_id' => [
            'method' => 'PUT',
            'authenticate' => true,
            'is_moderator' => true,
            'payload' => [
                'feedback_id' => 'required|integer',
                'status' => 'required|string',
                'comment' => 'permit_empty|string|max_length[2000]'
            ]
        ],
        'view:feedback_id' => [
            'method' => 'GET',
            'authenticate' => true,
            'is_moderator' => true,
            'payload' => [
                'feedback_id' => 'required|integer'
            ]
        ]
    ];

}