<?php

namespace App\Libraries;

use Exception;

class Encryptions {
    private $key;
    private $cipher = 'aes-256-gcm';
    private $tagLength = 16;
    private $ivLength = 12;
    private $saltLength = 32;
    private $iterations = 100000;

    /**
     * Constructor - Initialize encryption with a secure key
     * @param string|null $key Optional encryption key. If not provided, a new one will be generated
     */
    public function __construct(?string $key = null) {
        if ($key === null) {
            $this->key = $this->generateSecureKey();
        } else {
            $this->key = $this->deriveKey($key);
        }
    }

    /**
     * Generate a secure encryption key
     * @return string
     */
    private function generateSecureKey(): string {
        return bin2hex(random_bytes(32));
    }

    /**
     * Derive a secure key from a password using PBKDF2
     * @param string $password
     * @return string
     */
    private function deriveKey(string $password): string {
        $salt = random_bytes($this->saltLength);
        return hash_pbkdf2('sha256', $password, $salt, $this->iterations, 32, true);
    }

    /**
     * Encrypt data with authentication
     * @param string $data
     * @param array $additionalData Optional additional authenticated data
     * @return array Encrypted data with metadata
     */
    public function encrypt(string $data, array $additionalData = []): array {
        // Generate a random IV
        $iv = random_bytes($this->ivLength);
        
        // Encrypt the data
        $encrypted = openssl_encrypt(
            $data,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            json_encode($additionalData),
            $this->tagLength
        );

        if ($encrypted === false) {
            throw new Exception('Encryption failed: ' . openssl_error_string());
        }

        // Combine IV, tag, and encrypted data
        return [
            'iv' => base64_encode($iv),
            'tag' => base64_encode($tag),
            'data' => base64_encode($encrypted),
            'additional_data' => $additionalData
        ];
    }

    /**
     * Decrypt data with authentication
     * @param array $encryptedData
     * @return string
     */
    public function decrypt(array $encryptedData): string {
        // Extract components
        $iv = base64_decode($encryptedData['iv']);
        $tag = base64_decode($encryptedData['tag']);
        $data = base64_decode($encryptedData['data']);
        $additionalData = $encryptedData['additional_data'] ?? [];

        // Decrypt the data
        $decrypted = openssl_decrypt(
            $data,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            json_encode($additionalData)
        );

        if ($decrypted === false) {
            throw new Exception('Decryption failed: ' . openssl_error_string());
        }

        return $decrypted;
    }

    /**
     * Encrypt a chat message with metadata
     * @param string $message
     * @param string $senderId
     * @param string $roomId
     * @param int|null $selfDestructTime Optional self-destruct timestamp
     * @return array
     */
    public function encryptChatMessage(
        string $message,
        string $senderId,
        string $roomId,
        ?int $selfDestructTime = null
    ): array {
        $additionalData = [
            'sender_id' => $senderId,
            'room_id' => $roomId,
            'timestamp' => time(),
            'self_destruct' => $selfDestructTime
        ];

        return $this->encrypt($message, $additionalData);
    }

    /**
     * Decrypt a chat message and verify metadata
     * @param array $encryptedMessage
     * @param string $expectedRoomId
     * @return array
     */
    public function decryptChatMessage(array $encryptedMessage, string $expectedRoomId): array {
        $decrypted = $this->decrypt($encryptedMessage);
        $metadata = $encryptedMessage['additional_data'];

        // Verify room ID
        if ($metadata['room_id'] !== $expectedRoomId) {
            throw new Exception('Invalid room ID');
        }

        // Check if message has expired
        if (isset($metadata['self_destruct']) && $metadata['self_destruct'] < time()) {
            throw new Exception('Message has expired');
        }

        return [
            'message' => $decrypted,
            'metadata' => $metadata
        ];
    }

    /**
     * Encrypt device identifier
     * @param string $deviceId
     * @return string
     */
    public function encryptDeviceId(string $deviceId): string {
        $additionalData = [
            'type' => 'device_id',
            'timestamp' => time()
        ];

        $encrypted = $this->encrypt($deviceId, $additionalData);
        return base64_encode(json_encode($encrypted));
    }

    /**
     * Decrypt device identifier
     * @param string $encryptedDeviceId
     * @return string
     */
    public function decryptDeviceId(string $encryptedDeviceId): string {
        $decoded = json_decode(base64_decode($encryptedDeviceId), true);
        if (!$decoded) {
            throw new Exception('Invalid encrypted device ID format');
        }

        $decrypted = $this->decrypt($decoded);
        $metadata = $decoded['additional_data'];

        if ($metadata['type'] !== 'device_id') {
            throw new Exception('Invalid device ID type');
        }

        return $decrypted;
    }

    /**
     * Rotate encryption key
     * @return string New key
     */
    public function rotateKey(): string {
        $this->key = $this->generateSecureKey();
        return $this->key;
    }

    /**
     * Get the current encryption key (for backup purposes)
     * @return string
     */
    public function getKey(): string {
        return $this->key;
    }
}