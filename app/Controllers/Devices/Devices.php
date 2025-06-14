<?php

namespace App\Controllers\Devices;

use App\Controllers\LoadController;

class Devices extends LoadController {
    public function list($filters = []) {
        try {
            $query = "SELECT * FROM devices WHERE 1=1";
            $params = [];

            if (!empty($filters['is_banned'])) {
                $query .= " AND is_banned = ?";
                $params[] = $filters['is_banned'];
            }

            if (!empty($filters['min_karma'])) {
                $query .= " AND karma_score >= ?";
                $params[] = $filters['min_karma'];
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $this->success($stmt->fetchAll(\PDO::FETCH_ASSOC));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function create($data) {
        try {
            $this->validateRequired($data, ['device_id']);
            
            // Encrypt device ID
            $deviceHash = $this->encryption->encryptDeviceId($data['device_id']);
            
            $stmt = $this->db->prepare("
                INSERT INTO devices (device_id, device_hash, karma_score)
                VALUES (?, ?, 0)
            ");
            
            $stmt->execute([$data['device_id'], $deviceHash]);
            return $this->success(['device_id' => $data['device_id']]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function update($deviceId, $data) {
        try {
            $allowedFields = ['karma_score', 'is_banned', 'is_muted'];
            $updates = [];
            $params = [];

            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $updates[] = "{$key} = ?";
                    $params[] = $value;
                }
            }

            if (empty($updates)) {
                throw new \Exception("No valid fields to update");
            }

            $params[] = $deviceId;
            $query = "UPDATE devices SET " . implode(", ", $updates) . " WHERE device_id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            
            return $this->success(['device_id' => $deviceId]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function delete($deviceId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM devices WHERE device_id = ?");
            $stmt->execute([$deviceId]);
            return $this->success();
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function updateLocation($deviceId, $latitude, $longitude, $accuracy = null) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO device_locations (device_id, latitude, longitude, accuracy)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                latitude = VALUES(latitude),
                longitude = VALUES(longitude),
                accuracy = VALUES(accuracy),
                last_updated = CURRENT_TIMESTAMP
            ");
            
            $stmt->execute([$deviceId, $latitude, $longitude, $accuracy]);
            return $this->success();
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function getNearbyDevices($deviceId, $radius = 1, $limit = 50) {
        try {
            // Get device's current location
            $stmt = $this->db->prepare("
                SELECT latitude, longitude 
                FROM device_locations 
                WHERE device_id = ?
                ORDER BY last_updated DESC 
                LIMIT 1
            ");
            $stmt->execute([$deviceId]);
            $location = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$location) {
                throw new \Exception("Device location not found");
            }

            // Find nearby devices using Haversine formula
            $query = "
                SELECT d.*, dl.latitude, dl.longitude,
                    (6371 * acos(
                        cos(radians(?)) * 
                        cos(radians(dl.latitude)) * 
                        cos(radians(dl.longitude) - radians(?)) + 
                        sin(radians(?)) * 
                        sin(radians(dl.latitude))
                    )) AS distance
                FROM devices d
                JOIN device_locations dl ON d.device_id = dl.device_id
                HAVING distance <= ?
                ORDER BY distance
                LIMIT ?
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $location['latitude'],
                $location['longitude'],
                $location['latitude'],
                $radius,
                $limit
            ]);

            return $this->success($stmt->fetchAll(\PDO::FETCH_ASSOC));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function banDevice($deviceId, $reason = '') {
        try {
            $this->db->beginTransaction();

            // Update device status
            $stmt = $this->db->prepare("
                UPDATE devices 
                SET is_banned = TRUE 
                WHERE device_id = ?
            ");
            $stmt->execute([$deviceId]);

            // Log the ban
            $stmt = $this->db->prepare("
                INSERT INTO reports (
                    reporter_id, 
                    reported_type, 
                    reported_id, 
                    reason, 
                    status
                ) VALUES (
                    'system',
                    'user',
                    ?,
                    ?,
                    'resolved'
                )
            ");
            $stmt->execute([$deviceId, $reason]);

            $this->db->commit();
            return $this->success();
        } catch (\Exception $e) {
            $this->db->rollBack();
            return $this->handleError($e);
        }
    }

    public function updateKarma($deviceId, $amount) {
        try {
            $stmt = $this->db->prepare("
                UPDATE devices 
                SET karma_score = karma_score + ? 
                WHERE device_id = ?
            ");
            $stmt->execute([$amount, $deviceId]);
            return $this->success();
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }
} 