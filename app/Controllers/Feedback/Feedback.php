<?php

namespace App\Controllers\Feedback;

use App\Controllers\LoadController;
use App\Libraries\Routing;

class Feedback extends LoadController {

    /**
     * Submit feedback
     * 
     * @return array
     */
    public function submit()
    {
        // Validate required fields
        $requiredFields = ['feedback_type', 'priority', 'subject', 'description'];
        foreach ($requiredFields as $field) {
            if (empty($this->payload[$field])) {
                return Routing::error('Missing required field: ' . $field, 400);
            }
        }

        // Validate feedback type
        $validTypes = ['suggestion', 'bug_report', 'improvement', 'general', 'praise', 'complaint'];
        if (!in_array($this->payload['feedback_type'], $validTypes)) {
            return Routing::error('Invalid feedback type', 400);
        }

        // Validate priority
        $validPriorities = ['low', 'medium', 'high'];
        if (!in_array($this->payload['priority'], $validPriorities)) {
            return Routing::error('Invalid priority level', 400);
        }

        // Sanitize and validate input
        $feedbackData = [
            'user_id' => $this->currentUser['user_id'] ?? 0,
            'feedback_type' => $this->cleanUtf8String($this->payload['feedback_type']),
            'priority' => $this->cleanUtf8String($this->payload['priority']),
            'subject' => $this->cleanUtf8String($this->payload['subject']),
            'description' => $this->cleanUtf8String($this->payload['description']),
            'contact_preference' => $this->cleanUtf8String($this->payload['contact_preference'] ?? 'no'),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            // Insert feedback into database
            $result = $this->feedbackModel->submitFeedback($feedbackData);
            
            if ($result) {
                return Routing::success([
                    'message' => 'Feedback submitted successfully',
                    'feedback_id' => $result
                ]);
            } else {
                return Routing::error('Failed to submit feedback', 500);
            }
        } catch (\Exception $e) {
            return Routing::error('An error occurred while submitting feedback', 500);
        }
    }

    /**
     * Get feedback for admin with filters
     * 
     * @return array
     */
    public function admin()
    {
        try {
            if (!$this->isAdminOrModerator()) {
                return Routing::error('Unauthorized access', 403);
            }
            
            $filters = [
                'search' => $this->payload['search'] ?? '',
                'type' => $this->payload['type'] ?? '',
                'priority' => $this->payload['priority'] ?? '',
                'status' => $this->payload['status'] ?? ''
            ];
            
            $feedback = $this->feedbackModel->getAdminFeedback($filters);
            $stats = $this->feedbackModel->getFeedbackStats();
            
            return Routing::success([
                'feedback' => $feedback,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return Routing::error('Failed to fetch feedback', 500);
        }
    }

    /**
     * View feedback
     * 
     * @return array
     */
    public function view()
    {
        $feedback = $this->feedbackModel->getFeedbackById($this->payload['feedback_id']);

        if(empty($feedback)) {
            return Routing::error('Feedback not found', 404);
        }

        $feedback['username'] = !empty($feedback['username']) ? $feedback['username'] : 'Unknown';

        return Routing::success([
            'feedback' => $feedback
        ]);
    }

    /**
     * Update feedback status
     * 
     * @return array
     */
    public function status()
    {
        $this->feedbackModel->updateStatus($this->payload['feedback_id'], $this->payload['status'], $this->payload['comment'] ?? '');
        return Routing::success([
            'message' => 'Feedback status updated successfully'
        ]);
    }

    /**
     * Check if user is admin or moderator
     * 
     * @return bool
     */
    private function isAdminOrModerator()
    {
        return is_admin_or_moderator($this->currentUser ?? []);
    }

    /**
     * Clean and validate UTF-8 string
     * 
     * @param string $string
     * @return string
     */
    private function cleanUtf8String($string)
    {
        if (empty($string)) {
            return '';
        }

        // Remove invalid UTF-8 characters
        $string = iconv('UTF-8', 'UTF-8//IGNORE', $string);
        
        // Remove null bytes and other control characters
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $string);
        
        // Ensure proper UTF-8 encoding
        if (!mb_check_encoding($string, 'UTF-8')) {
            $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        }
        
        return trim($string);
    }
}
?> 