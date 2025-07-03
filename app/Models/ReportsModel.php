<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class ReportsModel extends Model {

    public $payload = [];
    protected $table;
    protected $primaryKey = "report_id";

    public function __construct() {
        parent::__construct();
        
        $this->table = DbTables::$userTable;
        foreach(DbTables::initTables() as $key) {
            if (property_exists($this, $key)) {
                $this->{$key} = DbTables::${$key};
            }
        }
    }

    /**
     * Get reports with filtering and pagination
     * 
     * @param array $filters
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getReports($filters = [], $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT r.*, 
                       COUNT(CASE WHEN v.vote_type = 1 THEN 1 END) as upvotes,
                       COUNT(CASE WHEN v.vote_type = -1 THEN 1 END) as downvotes,
                       CASE WHEN mv.report_id IS NOT NULL THEN 1 ELSE 0 END as user_has_voted
                FROM reports r
                    LEFT JOIN report_votes v ON r.report_id = v.report_id
                    LEFT JOIN report_votes mv ON r.report_id = mv.report_id AND mv.moderator_id = ?
                WHERE 1=1";
        
        $params = [$this->payload['userId'] ?? 0];
        
        // Apply filters
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $sql .= " AND r.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['type']) && $filters['type'] !== 'all') {
            $sql .= " AND r.reported_type = ?";
            $params[] = $filters['type'];
        }
        
        if (!empty($filters['reason']) && $filters['reason'] !== 'all') {
            $sql .= " AND r.reason = ?";
            $params[] = $filters['reason'];
        }
        
        $sql .= " GROUP BY r.report_id ORDER BY r.created_at DESC LIMIT {$limit} OFFSET {$offset}";
        
        $reports = $this->db->query($sql, $params)->getResultArray();
        
        // Get content previews
        foreach ($reports as &$report) {
            $report['upvotes'] = (int)$report['upvotes'];
            $report['downvotes'] = (int)$report['downvotes'];
            $report['user_has_voted'] = (int)$report['user_has_voted'];
            $report['content_preview'] = $this->getContentPreview($report['reported_type'], $report['reported_id']);
        }
        
        return $reports;
    }

    /**
     * Get total count of reports with filters
     * 
     * @param array $filters
     * @return int
     */
    public function getReportsCount($filters = [])
    {
        $sql = "SELECT COUNT(*) as total FROM reports r WHERE 1=1";
        $params = [];
        
        // Apply filters
        if (!empty($filters['status'])) {
            $sql .= " AND r.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['type'])) {
            $sql .= " AND r.reported_type = ?";
            $params[] = $filters['type'];
        }
        
        if (!empty($filters['reason'])) {
            $sql .= " AND r.reason = ?";
            $params[] = $filters['reason'];
        }
        
        $result = $this->db->query($sql, $params)->getRowArray();
        return (int)$result['total'];
    }

    /**
     * Get report statistics
     * 
     * @return array
     */
    public function getStats()
    {
        $today = date('Y-m-d');
        $sql = "SELECT 
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending,
                    COUNT(CASE WHEN status = 'resolved' AND DATE(updated_at) = '{$today}' THEN 1 END) as resolved_today,
                    COUNT(CASE WHEN status = 'resolved' AND final_decision = 'removed' THEN 1 END) as removed,
                    COUNT(CASE WHEN status = 'resolved' AND final_decision = 'approved' THEN 1 END) as approved
                FROM reports";
        
        $result = $this->db->query($sql)->getRowArray();
        
        return [
            'pending' => (int)$result['pending'],
            'resolved_today' => (int)$result['resolved_today'],
            'removed' => (int)$result['removed'],
            'approved' => (int)$result['approved']
        ];
    }

    /**
     * Get a specific report by ID
     * 
     * @param int $reportId
     * @return array|null
     */
    public function getReportById($reportId)
    {
        $sql = "SELECT r.*, 
                       COUNT(CASE WHEN v.vote_type = 1 THEN 1 END) as upvotes,
                       COUNT(CASE WHEN v.vote_type = -1 THEN 1 END) as downvotes
                FROM reports r
                LEFT JOIN report_votes v ON r.report_id = v.report_id
                WHERE r.report_id = ?
                GROUP BY r.report_id";
        
        $result = $this->db->query($sql, [$reportId])->getRowArray();
        
        if ($result) {
            $result['content'] = $this->getContentPreview($result['reported_type'], $result['reported_id']);
        }
        
        return $result;
    }

    /**
     * Get user's vote on a report
     * 
     * @param int $reportId
     * @param int $userId
     * @return array|null
     */
    public function getUserVote($reportId, $userId)
    {
        $sql = "SELECT * FROM report_votes WHERE report_id = ? AND moderator_id = ?";
        return $this->db->query($sql, [$reportId, $userId])->getRowArray();
    }

    /**
     * Submit a vote on a report
     * 
     * @param array $voteData
     * @return int|false
     */
    public function submitVote($voteData)
    {
        $sql = "INSERT INTO report_votes (report_id, moderator_id, vote_type, created_at) 
                VALUES (?, ?, ?, ?)";
        
        $result = $this->db->query($sql, [
            $voteData['report_id'],
            $voteData['moderator_id'],
            $voteData['vote_type'],
            $voteData['created_at']
        ]);
        
        return $result ? $this->db->insertID() : false;
    }

    /**
     * Get all votes for a report
     * 
     * @param int $reportId
     * @return array
     */
    public function getReportVotes($reportId)
    {
        $sql = "SELECT * FROM report_votes WHERE report_id = ? ORDER BY created_at ASC";
        return $this->db->query($sql, [$reportId])->getResultArray();
    }

    /**
     * Update report status
     * 
     * @param int $reportId
     * @param string $status
     * @return bool
     */
    public function updateReportStatus($reportId, $status)
    {
        $sql = "UPDATE reports SET status = ?, updated_at = NOW() WHERE report_id = ?";
        return $this->db->query($sql, [$status, $reportId]);
    }

    /**
     * Mark content as approved
     * 
     * @param int $reportId
     * @return bool
     */
    public function markContentApproved($reportId)
    {
        $sql = "UPDATE reports SET final_decision = 'approved', updated_at = NOW() WHERE report_id = ?";
        return $this->db->query($sql, [$reportId]);
    }

    /**
     * Mark content as removed
     * 
     * @param int $reportId
     * @return bool
     */
    public function markContentRemoved($reportId)
    {
        $sql = "UPDATE reports SET final_decision = 'removed', updated_at = NOW() WHERE report_id = ?";
        return $this->db->query($sql, [$reportId]);
    }

    /**
     * Get reports by user
     * 
     * @param int $userId
     * @return array
     */
    public function getReportsByUser($userId)
    {
        $sql = "SELECT * FROM reports WHERE reporter_id = ? ORDER BY created_at DESC";
        return $this->db->query($sql, [$userId])->getResultArray();
    }

    /**
     * Get reports by content
     * 
     * @param string $type
     * @param int $id
     * @return array
     */
    public function getReportsByContent($type, $id)
    {
        $sql = "SELECT * FROM reports WHERE reported_type = ? AND reported_id = ? ORDER BY created_at DESC";
        return $this->db->query($sql, [$type, $id])->getResultArray();
    }

    /**
     * Get content preview based on type and ID
     * 
     * @param string $type
     * @param int $id
     * @return string
     */
    private function getContentPreview($type, $id)
    {
        switch ($type) {
            case 'post':
                $sql = "SELECT content FROM posts WHERE post_id = ?";
                $result = $this->db->query($sql, [$id])->getRowArray();
                return $result ? substr($result['content'], 0, 200) . '...' : 'Content not available';
                
            case 'comment':
                $sql = "SELECT content FROM comments WHERE comment_id = ?";
                $result = $this->db->query($sql, [$id])->getRowArray();
                return $result ? substr($result['content'], 0, 200) . '...' : 'Content not available';
                
            case 'user':
                $sql = "SELECT username FROM users WHERE user_id = ?";
                $result = $this->db->query($sql, [$id])->getRowArray();
                return $result ? "User: " . $result['username'] : 'User not available';
                
            default:
                return 'Content preview not available';
        }
    }

    /**
     * Create a new report
     * 
     * @param array $reportData
     * @return int|false
     */
    public function createReport($reportData)
    {
        $sql = "INSERT INTO reports (reporter_id, reported_type, reported_id, reason, description, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $result = $this->db->query($sql, [
            $reportData['reporter_id'],
            $reportData['reported_type'],
            $reportData['reported_id'],
            $reportData['reason'],
            $reportData['description'] ?? null,
            $reportData['status']
        ]);
        
        return $result ? $this->db->insertID() : false;
    }
}