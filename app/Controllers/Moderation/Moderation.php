<?php

namespace App\Controllers\Moderation;

use App\Controllers\LoadController;

class Moderation extends LoadController {
    public function createReport($data) {
        try {
            $this->validateRequired($data, ['reporter_id', 'reported_type', 'reported_id', 'reason']);
            
            $stmt = $this->db->prepare("
                INSERT INTO reports (
                    reporter_id, reported_type, reported_id,
                    reason, description, status
                ) VALUES (?, ?, ?, ?, ?, 'pending')
            ");
            
            $stmt->execute([
                $data['reporter_id'],
                $data['reported_type'],
                $data['reported_id'],
                $data['reason'],
                $data['description'] ?? null
            ]);

            return $this->success(['report_id' => $this->db->lastInsertId()]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function listReports($filters = []) {
        try {
            $query = "
                SELECT r.*, d.karma_score as reporter_karma
                FROM reports r
                LEFT JOIN devices d ON r.reporter_id = d.device_id
                WHERE 1=1
            ";
            $params = [];

            if (!empty($filters['status'])) {
                $query .= " AND r.status = ?";
                $params[] = $filters['status'];
            }

            if (!empty($filters['reported_type'])) {
                $query .= " AND r.reported_type = ?";
                $params[] = $filters['reported_type'];
            }

            if (!empty($filters['reporter_id'])) {
                $query .= " AND r.reporter_id = ?";
                $params[] = $filters['reporter_id'];
            }

            $query .= " ORDER BY r.created_at DESC";

            if (!empty($filters['limit'])) {
                $query .= " LIMIT ?";
                $params[] = $filters['limit'];
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $this->success($stmt->fetchAll(\PDO::FETCH_ASSOC));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function updateReportStatus($reportId, $status, $action = null) {
        try {
            $this->db->beginTransaction();

            // Update report status
            $stmt = $this->db->prepare("
                UPDATE reports 
                SET status = ?, updated_at = CURRENT_TIMESTAMP
                WHERE report_id = ?
            ");
            $stmt->execute([$status, $reportId]);

            // Get report details
            $stmt = $this->db->prepare("
                SELECT * FROM reports WHERE report_id = ?
            ");
            $stmt->execute([$reportId]);
            $report = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Take action based on report type
            if ($action && $report) {
                switch ($report['reported_type']) {
                    case 'post':
                        $this->handlePostAction($report['reported_id'], $action);
                        break;
                    case 'comment':
                        $this->handleCommentAction($report['reported_id'], $action);
                        break;
                    case 'user':
                        $this->handleUserAction($report['reported_id'], $action);
                        break;
                    case 'message':
                        $this->handleMessageAction($report['reported_id'], $action);
                        break;
                }
            }

            $this->db->commit();
            return $this->success();
        } catch (\Exception $e) {
            $this->db->rollBack();
            return $this->handleError($e);
        }
    }

    private function handlePostAction($postId, $action) {
        switch ($action) {
            case 'delete':
                $stmt = $this->db->prepare("DELETE FROM posts WHERE post_id = ?");
                $stmt->execute([$postId]);
                break;
            case 'hide':
                $stmt = $this->db->prepare("UPDATE posts SET is_hidden = TRUE WHERE post_id = ?");
                $stmt->execute([$postId]);
                break;
        }
    }

    private function handleCommentAction($commentId, $action) {
        switch ($action) {
            case 'delete':
                $stmt = $this->db->prepare("DELETE FROM comments WHERE comment_id = ?");
                $stmt->execute([$commentId]);
                break;
            case 'hide':
                $stmt = $this->db->prepare("UPDATE comments SET is_hidden = TRUE WHERE comment_id = ?");
                $stmt->execute([$commentId]);
                break;
        }
    }

    private function handleUserAction($deviceId, $action) {
        switch ($action) {
            case 'ban':
                $stmt = $this->db->prepare("UPDATE devices SET is_banned = TRUE WHERE device_id = ?");
                $stmt->execute([$deviceId]);
                break;
            case 'mute':
                $stmt = $this->db->prepare("UPDATE devices SET is_muted = TRUE WHERE device_id = ?");
                $stmt->execute([$deviceId]);
                break;
        }
    }

    private function handleMessageAction($messageId, $action) {
        if ($action === 'delete') {
            $stmt = $this->db->prepare("DELETE FROM chat_messages WHERE message_id = ?");
            $stmt->execute([$messageId]);
        }
    }

    public function getModerationStats() {
        try {
            $query = "
                SELECT 
                    reported_type,
                    status,
                    COUNT(*) as count
                FROM reports
                GROUP BY reported_type, status
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Format stats
            $formattedStats = [];
            foreach ($stats as $stat) {
                if (!isset($formattedStats[$stat['reported_type']])) {
                    $formattedStats[$stat['reported_type']] = [];
                }
                $formattedStats[$stat['reported_type']][$stat['status']] = $stat['count'];
            }

            return $this->success($formattedStats);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getReportHistory($reportedId, $reportedType) {
        try {
            $query = "
                SELECT r.*, d.karma_score as reporter_karma
                FROM reports r
                LEFT JOIN devices d ON r.reporter_id = d.device_id
                WHERE r.reported_id = ? AND r.reported_type = ?
                ORDER BY r.created_at DESC
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute([$reportedId, $reportedType]);
            return $this->success($stmt->fetchAll(\PDO::FETCH_ASSOC));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }
} 