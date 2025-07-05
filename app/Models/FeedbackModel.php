<?php 

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DbTables;
use CodeIgniter\Database\Exceptions\DatabaseException;

class FeedbackModel extends Model {

    public $payload = [];
    protected $table;
    protected $primaryKey = "id";

    public function __construct() {
        parent::__construct();
        
        $this->table = 'feedback';
        foreach(DbTables::initTables() as $key) {
            if (property_exists($this, $key)) {
                $this->{$key} = DbTables::${$key};
            }
        }
    }

    /**
     * Submit feedback
     * 
     * @param array $data
     * @return int|false
     */
    public function submitFeedback($data)
    {
        try {
            $result = $this->db->table('feedback')->insert($data);
            
            if ($result) {
                return $this->db->insertID();
            }
            
            return false;
        } catch(DatabaseException $e) {
            return false;
        }
    }

    /**
     * Get feedback by id
     * 
     * @param int $feedbackId
     * @return array
     */
    public function getFeedbackById($feedbackId)
    {
        try {
            return $this->db->table('feedback f')
                    ->select('f.*, u.username, u.email')
                    ->where('f.id', $feedbackId)
                    ->join('users u', 'u.user_id = f.user_id', 'left')
                    ->get()
                    ->getRowArray();
        } catch(DatabaseException $e) {
            return [];
        }
    }

    /**
     * Get all feedback for admin with filters
     * 
     * @param array $filters
     * @return array
     */
    public function getAdminFeedback($filters = [])
    {
        try {
            $where = [];
            $params = [];
            
            if (!empty($filters['search'])) {
                $where[] = "(f.subject LIKE ? OR f.description LIKE ?)";
                $params[] = "%{$filters['search']}%";
                $params[] = "%{$filters['search']}%";
            }
            
            if (!empty($filters['type'])) {
                $where[] = "f.feedback_type = ?";
                $params[] = $filters['type'];
            }
            
            if (!empty($filters['priority'])) {
                $where[] = "f.priority = ?";
                $params[] = $filters['priority'];
            }
            
            if (!empty($filters['status'])) {
                $where[] = "f.status = ?";
                $params[] = $filters['status'];
            }
            
            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
            
            $sql = "SELECT 
                        f.id,
                        f.feedback_type,
                        f.priority,
                        f.subject,
                        f.description,
                        f.contact_preference,
                        f.created_at,
                        f.status,
                        u.username,
                        u.email
                    FROM feedback f
                    LEFT JOIN users u ON f.user_id = u.user_id
                    {$whereClause}
                    ORDER BY f.created_at DESC 
                    LIMIT 50";
            
            $result = $this->db->query($sql, $params)->getResultArray();
            
            return array_map(function($row) {
                return [
                    'id' => $row['id'],
                    'type' => $row['feedback_type'],
                    'priority' => $row['priority'],
                    'subject' => $row['subject'],
                    'description' => $row['description'],
                    'contact_preference' => $row['contact_preference'],
                    'created_at' => $row['created_at'],
                    'status' => $row['status'] ?? 'pending',
                    'username' => $row['username'] ?? 'Anonymous',
                    'email' => $row['email'] ?? ''
                ];
            }, $result);
        } catch(DatabaseException $e) {
            return [];
        }
    }

    /**
     * Update feedback status
     * 
     * @param int $feedbackId
     * @param string $status
     * @return bool
     */
    public function updateStatus($feedbackId, $status, $comment = '')
    {
        try {
            $validStatuses = ['pending', 'in_progress', 'resolved', 'closed'];
            
            if (!in_array($status, $validStatuses)) {
                return false;
            }
            
            $result = $this->db->table('feedback')
                ->where('id', $feedbackId)
                ->update(['status' => $status]);
            
            return $result;
        } catch(DatabaseException $e) {
            return false;
        }
    }

    /**
     * Get feedback statistics
     * 
     * @return array
     */
    public function getFeedbackStats()
    {
        try {
            $sql = "SELECT 
                        feedback_type,
                        priority,
                        status,
                        COUNT(*) as count
                    FROM feedback 
                    GROUP BY feedback_type, priority, status";
            
            $result = $this->db->query($sql)->getResultArray();
            
            $stats = [
                'total' => 0,
                'by_type' => [],
                'by_priority' => [],
                'by_status' => []
            ];
            
            foreach ($result as $row) {
                $stats['total'] += $row['count'];
                
                // By type
                if (!isset($stats['by_type'][$row['feedback_type']])) {
                    $stats['by_type'][$row['feedback_type']] = 0;
                }
                $stats['by_type'][$row['feedback_type']] += $row['count'];
                
                // By priority
                if (!isset($stats['by_priority'][$row['priority']])) {
                    $stats['by_priority'][$row['priority']] = 0;
                }
                $stats['by_priority'][$row['priority']] += $row['count'];
                
                // By status
                if (!isset($stats['by_status'][$row['status']])) {
                    $stats['by_status'][$row['status']] = 0;
                }
                $stats['by_status'][$row['status']] += $row['count'];
            }
            
            return $stats;
        } catch(DatabaseException $e) {
            return [];
        }
    }
}
?> 