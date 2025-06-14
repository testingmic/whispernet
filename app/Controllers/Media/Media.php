<?php

namespace App\Controllers\Media;

use App\Controllers\LoadController;
use Exception;

class Media extends LoadController {

    private $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private $allowedVideoTypes = ['video/mp4', 'video/webm', 'video/quicktime'];
    private $maxFileSize = 100 * 1024 * 1024; // 100MB
    private $uploadPath;
    private $thumbnailPath;

    public function __construct($db) {
        $this->uploadPath = dirname(__DIR__) . '/uploads/';
        $this->thumbnailPath = $this->uploadPath . 'thumbnails/';
        $this->ensureDirectoriesExist();
    }

    private function ensureDirectoriesExist() {
        if (!file_exists($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
        if (!file_exists($this->thumbnailPath)) {
            mkdir($this->thumbnailPath, 0755, true);
        }
    }

    private function validateDevice($deviceId) {
        $sql = "SELECT device_id, is_banned FROM devices WHERE device_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$deviceId]);
        $device = $stmt->fetch();

        if (!$device) {
            throw new Exception('Device not found');
        }

        if ($device['is_banned']) {
            throw new Exception('Device is banned');
        }

        return true;
    }

    public function uploadMedia($deviceId, $file, $type = 'image') {
        try {
            $this->validateDevice($deviceId);
            $this->validateFile($file, $type);

            $fileInfo = $this->processFile($file, $type);
            $mediaId = $this->saveMediaRecord($deviceId, $fileInfo);

            return [
                'success' => true,
                'media_id' => $mediaId,
                'file_path' => $fileInfo['file_path'],
                'thumbnail_path' => $fileInfo['thumbnail_path'] ?? null
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    private function validateFile($file, $type) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception('Invalid file upload');
        }

        if ($file['size'] > $this->maxFileSize) {
            throw new Exception('File size exceeds limit');
        }

        $mimeType = mime_content_type($file['tmp_name']);
        $allowedTypes = $type === 'image' ? $this->allowedImageTypes : $this->allowedVideoTypes;

        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception('Invalid file type');
        }
    }

    private function processFile($file, $type) {
        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $this->uploadPath . $fileName;
        $mimeType = mime_content_type($file['tmp_name']);

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('Failed to save file');
        }

        $fileInfo = [
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => $type,
            'mime_type' => $mimeType,
            'file_size' => $file['size']
        ];

        if ($type === 'image') {
            $fileInfo = $this->processImage($fileInfo);
        } else {
            $fileInfo = $this->processVideo($fileInfo);
        }

        return $fileInfo;
    }

    private function processImage($fileInfo) {
        $image = imagecreatefromstring(file_get_contents($fileInfo['file_path']));
        if (!$image) {
            throw new Exception('Failed to process image');
        }

        $fileInfo['width'] = imagesx($image);
        $fileInfo['height'] = imagesy($image);

        // Create thumbnail
        $thumbnail = $this->createThumbnail($image, 300, 300);
        $thumbnailName = 'thumb_' . $fileInfo['file_name'];
        $thumbnailPath = $this->thumbnailPath . $thumbnailName;
        
        imagejpeg($thumbnail, $thumbnailPath, 80);
        $fileInfo['thumbnail_path'] = $thumbnailPath;

        imagedestroy($image);
        imagedestroy($thumbnail);

        return $fileInfo;
    }

    private function processVideo($fileInfo) {
        // Get video information using FFmpeg
        $command = "ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($fileInfo['file_path']);
        $duration = shell_exec($command);
        
        $fileInfo['duration'] = (int)$duration;

        // Generate thumbnail using FFmpeg
        $thumbnailName = 'thumb_' . pathinfo($fileInfo['file_name'], PATHINFO_FILENAME) . '.jpg';
        $thumbnailPath = $this->thumbnailPath . $thumbnailName;
        
        $command = "ffmpeg -i " . escapeshellarg($fileInfo['file_path']) . 
                  " -ss 00:00:01 -vframes 1 -vf scale=300:300 " . 
                  escapeshellarg($thumbnailPath);
        
        shell_exec($command);
        $fileInfo['thumbnail_path'] = $thumbnailPath;

        return $fileInfo;
    }

    private function createThumbnail($image, $maxWidth, $maxHeight) {
        $width = imagesx($image);
        $height = imagesy($image);

        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = $width * $ratio;
        $newHeight = $height * $ratio;

        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        return $thumbnail;
    }

    private function saveMediaRecord($deviceId, $fileInfo) {
        $sql = "INSERT INTO media (device_id, file_name, file_path, file_type, mime_type, 
                file_size, width, height, duration, thumbnail_path, is_processed, processing_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $deviceId,
            $fileInfo['file_name'],
            $fileInfo['file_path'],
            $fileInfo['file_type'],
            $fileInfo['mime_type'],
            $fileInfo['file_size'],
            $fileInfo['width'] ?? null,
            $fileInfo['height'] ?? null,
            $fileInfo['duration'] ?? null,
            $fileInfo['thumbnail_path'] ?? null,
            true,
            'completed'
        ]);

        return $this->db->lastInsertId();
    }

    public function getMedia($mediaId) {
        try {
            $sql = "SELECT * FROM media WHERE media_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$mediaId]);
            $media = $stmt->fetch();

            if (!$media) {
                throw new Exception('Media not found');
            }

            return [
                'success' => true,
                'media' => $media
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function deleteMedia($mediaId, $deviceId) {
        try {
            $this->validateDevice($deviceId);

            $sql = "SELECT * FROM media WHERE media_id = ? AND device_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$mediaId, $deviceId]);
            $media = $stmt->fetch();

            if (!$media) {
                throw new Exception('Media not found or unauthorized');
            }

            // Delete files
            if (file_exists($media['file_path'])) {
                unlink($media['file_path']);
            }
            if ($media['thumbnail_path'] && file_exists($media['thumbnail_path'])) {
                unlink($media['thumbnail_path']);
            }

            // Delete database record
            $sql = "DELETE FROM media WHERE media_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$mediaId]);

            return [
                'success' => true,
                'message' => 'Media deleted successfully'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getDeviceMedia($deviceId, $page = 1, $limit = 20) {
        try {
            $this->validateDevice($deviceId);

            $offset = ($page - 1) * $limit;
            $sql = "SELECT * FROM media WHERE device_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$deviceId, $limit, $offset]);
            $media = $stmt->fetchAll();

            $sql = "SELECT COUNT(*) FROM media WHERE device_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$deviceId]);
            $total = $stmt->fetchColumn();

            return [
                'success' => true,
                'media' => $media,
                'pagination' => [
                    'total' => $total,
                    'page' => $page,
                    'limit' => $limit,
                    'pages' => ceil($total / $limit)
                ]
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function updateMediaStatus($mediaId, $status) {
        try {
            $sql = "UPDATE media SET processing_status = ? WHERE media_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$status, $mediaId]);

            return [
                'success' => true,
                'message' => 'Media status updated'
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getMediaByType($type, $page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT * FROM media WHERE file_type = ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$type, $limit, $offset]);
            $media = $stmt->fetchAll();

            $sql = "SELECT COUNT(*) FROM media WHERE file_type = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$type]);
            $total = $stmt->fetchColumn();

            return [
                'success' => true,
                'media' => $media,
                'pagination' => [
                    'total' => $total,
                    'page' => $page,
                    'limit' => $limit,
                    'pages' => ceil($total / $limit)
                ]
            ];
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
} 