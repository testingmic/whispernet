<?php
namespace App\Controllers\Reports;

use App\Controllers\LoadController;
use App\Libraries\Routing;

class Reports extends LoadController
{
    protected $reportsModel;

    /**
     * Get all reports with filtering and pagination
     * 
     * @return array
     */
    public function list()
    {
        // Get reports with pagination
        $reports = $this->reportsModel->getReports($this->payload, (int)$this->payload['offset'], (int)$this->payload['limit']);
        $total = $this->reportsModel->getReportsCount($this->payload);

        return Routing::success([
            'reports' => $reports,
            'total' => $total,
            'page' => $this->payload['offset'],
            'limit' => (int)$this->payload['limit'],
            'total_pages' => $total > 0 ? ceil($total / $this->payload['limit']) : 0
        ]);
    }

    /**
     * Get report statistics
     * 
     * @return array
     */
    public function stats()
    {
        $stats = $this->reportsModel->getStats();
        return Routing::success($stats);
    }

    /**
     * Get a specific report by ID
     * 
     * @param int $reportId
     * @return array
     */
    public function view()
    {
        $report = $this->reportsModel->getReportById($this->payload['reportId']);
        if (!$report) {
            return Routing::error('Report not found', 404);
        }
        return Routing::success($report);
    }

    /**
     * Vote on a report
     * 
     * @param int $reportId
     * @return array
     */
    public function vote()
    {
        $vote = $this->payload['vote'] ?? null;
        
        if (!in_array($vote, ['up', 'down'])) {
            return Routing::error('Invalid vote type');
        }

        // Check if user has already voted on this report
        $existingVote = $this->reportsModel->getUserVote($this->payload['reportId'], $this->payload['userId']);
        
        if ($existingVote) {
            return Routing::success('You have already voted on this report');
        }

        // Check if report is still pending
        $report = $this->reportsModel->getReportById($this->payload['reportId']);
        if (!$report || $report['status'] !== 'pending') {
            return Routing::error('Report is not available for voting');
        }

        // Submit vote
        $voteData = [
            'report_id' => $this->payload['reportId'],
            'moderator_id' => $this->payload['userId'],
            'vote_type' => $vote === 'up' ? 1 : -1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $voteId = $this->reportsModel->submitVote($voteData);
        
        if (!$voteId) {
            return Routing::error('Failed to submit vote');
        }

        // Check if we have enough votes to make a decision
        $this->checkVoteThreshold($this->payload['reportId']);

        return Routing::success('Vote submitted successfully');
    }

    /**
     * Check if we have enough votes to make a decision
     * 
     * @param int $reportId
     */
    private function checkVoteThreshold($reportId = null)
    {

        // If reportId is not provided, use the reportId from the payload
        $reportId = !empty($reportId) ? $reportId : $this->payload['reportId'];
        
        $votes = $this->reportsModel->getReportVotes($reportId);
        
        if (count($votes) >= 5) {
            $upvotes = 0;
            $downvotes = 0;
            
            foreach ($votes as $vote) {
                if ($vote['vote_type'] == 1) {
                    $upvotes++;
                } else {
                    $downvotes++;
                }
            }
            
            $netVotes = $upvotes - $downvotes;
            
            // Update report status based on voting results
            if ($netVotes >= 3) {
                // Approve content (remove report)
                $this->reportsModel->updateReportStatus($reportId, 'resolved');
                $this->reportsModel->markContentApproved($reportId);
            } elseif ($netVotes <= -3) {
                // Remove content
                $this->reportsModel->updateReportStatus($reportId, 'resolved');
                $this->reportsModel->markContentRemoved($reportId);
            } else {
                // Mark as reviewed but no clear decision
                $this->reportsModel->updateReportStatus($reportId, 'reviewed');
            }
        }
    }

    /**
     * Get reports for a specific user
     * 
     * @return array
     */
    public function userReports()
    {
        $reports = $this->reportsModel->getReportsByUser($this->payload['userId']);
        return Routing::success($reports);
    }

    /**
     * Get reports for a specific content item
     * 
     * @param string $type
     * @param int $id
     * @return array
     */
    public function contentReports()
    {
        $reports = $this->reportsModel->getReportsByContent($this->payload['type'], $this->payload['reportId']);
        return Routing::success($reports);
    }
}