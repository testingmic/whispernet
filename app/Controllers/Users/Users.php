<?php

namespace App\Controllers\Users;

use App\Controllers\LoadController;
use App\Libraries\Routing;

class Users extends LoadController {

    /**
     * Get user profile
     * 
     * @return array
     */
    public function profile() {
        $profile = $this->usersModel->getUserProfile($this->payload['userId']);
        if(empty($profile)) {
            return Routing::error('User not found');
        }
        $profile['statistics'] = json_decode($profile['statistics'], true);
        unset($profile['password_hash']);
        return Routing::success($profile);

    }

    /**
     * Update user profile
     * 
     * @return array
     */
    public function updateProfile() {
        return $this->usersModel->updateProfile($this->payload['userId'], $this->payload['data']);
    }

    /**
     * Register device
     * 
     * @return array
     */
    public function registerDevice() {
        return $this->usersModel->registerDevice($this->payload['userId'], $this->payload['deviceId'], $this->payload['deviceName'], $this->payload['deviceType']);
    }

    /**
     * Get user devices
     * 
     * @return array
     */
    public function getUserDevices() {
        return $this->usersModel->getUserDevices($this->payload['userId']);
    }

    /**
     * Deactivate account
     * 
     * @return array
     */
    public function deactivateAccount() {
        return $this->usersModel->deactivateAccount($this->payload['userId']);
    }

    /**
     * Reactivate account
     * 
     * @return array
     */
    public function reactivateAccount() {
        return $this->usersModel->reactivateAccount($this->payload['userId']);
    }

    /**
     * Get user settings
     * 
     * @return array
     */
    public function settings() {

        // get the user id
        $userId = $this->payload['userId'] ?? $this->currentUser['user_id'];

        // get user settings
        $userSettings = $this->usersModel->getUserSettings($userId);

        return Routing::success(formatUserSettings($userSettings, true));

    }

    /**
     * Update user profile and settings
     * 
     * @return array
     */
    public function update() {

        // get the user id
        $userId = $this->payload['userId'] ?? $this->currentUser['user_id'];

        // Handle profile image upload
        if (!empty($this->payload['file_uploads']['profile_image'])) {
            $uploadedImage = $this->handleProfileImageUpload($userId);
            if (isset($uploadedImage['error'])) {
                return Routing::error($uploadedImage['error']);
            }
        }

        // Handle profile data updates (name, etc.)
        $profileData = [];
        if (!empty($this->payload['name'])) {
            $profileData['full_name'] = trim($this->payload['name']);
        }

        // Update profile data if any
        if (!empty($profileData)) {
            $updateResult = $this->usersModel->updateProfile($userId, $profileData);
            if (is_string($updateResult)) {
                return Routing::error($updateResult);
            }
        }

        // Handle user settings
        if (!empty($this->payload['setting'])) {
            $userSettings = $this->usersModel->getUserSettings($userId);
            $setting = trim($this->payload['setting']);
            
            // check if the setting is valid
            if(!in_array($setting, array_keys(listUserSettings()))) {
                return Routing::error("Invalid setting:- {$setting}");
            }

            // if the user settings is empty, create a new one
            if(empty($userSettings)) {
                $settingValue = $this->payload['value'] ?? null;
                if(!empty($setting) && !empty($settingValue)) {
                    $this->usersModel->createUserSettings($userId, trim($setting), $settingValue);
                }
            } else {
                // user settings
                $settingsSet = array_column($userSettings, 'setting');

                if(empty($this->payload['value'])) {
                    return Routing::error("Value is required for setting:- {$setting}");
                }

                // loop through the settings and confirm if the setting is not already saved
                $isetting = !is_array($this->payload['setting']) ? trim($this->payload['setting']) : $this->payload['setting'];
                if(in_array($isetting, $settingsSet)) {
                    $this->usersModel->updateUserSettings($userId, $isetting, $this->payload['value']);
                } else {
                    $this->usersModel->createUserSettings($userId, $isetting, $this->payload['value']);
                }
            }

            // get the updated settings
            $userSettings = $this->usersModel->getUserSettings($userId);

            // return the updated settings
            return Routing::created(['data' => 'User settings successfully saved.', 'record' => formatUserSettings($userSettings)]);
        }

        $payload = [];

        // set the fullname
        $this->payload['full_name'] = $this->payload['name'] ?? ($this->payload['full_name'] ?? null);

        foreach(['gender', 'full_name', 'location'] as $item) {
            // update the user gender
            if(!empty($this->payload[$item])) {
                $payload[$item] = $this->payload[$item];
            }
        }

        if(!empty($payload)) {
            $this->usersModel->updateProfile($userId, $payload);
        }

        return Routing::created(['data' => 'Profile updated successfully', 'record' => $this->profile()['data']]);
    }

    /**
     * Handle profile image upload
     * 
     * @param int $userId
     * @return array
     */
    private function handleProfileImageUpload($userId) {
        try {
            $file = $this->payload['file_uploads']['profile_image'];
            
            // Validate file
            if (!$file->isValid()) {
                return ['error' => 'Invalid file upload'];
            }

            // Check file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return ['error' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.'];
            }

            // Check file size (2MB limit)
            if ($file->getSize() > 2 * 1024 * 1024) {
                return ['error' => 'File size must be less than 2MB'];
            }

            // Create upload directory
            $uploadPath = rtrim(PUBLICPATH, "/") . "/assets/uploads/profiles/";
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $extension = $file->getExtension();
            $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
            $filePath = $uploadPath . $filename;

            // Move uploaded file
            $file->move($uploadPath, $filename);

            // Create thumbnail
            $thumbnailPath = $uploadPath . 'thumbnails/';
            if (!file_exists($thumbnailPath)) {
                mkdir($thumbnailPath, 0755, true);
            }

            $thumbnailFilename = 'thumb_' . $filename;
            $thumbnailFilePath = $thumbnailPath . $thumbnailFilename;

            // Resize image to create thumbnail
            $image = \Config\Services::image()
                ->withFile($filePath)
                ->resize(150, 150, true, 'center')
                ->save($thumbnailFilePath);

            // Update user profile with image path
            $imageUrl = '/assets/uploads/profiles/' . $filename;
            $this->usersModel->updateProfile($userId, ['profile_image' => $imageUrl]);

            return ['success' => true, 'image_url' => $imageUrl];

        } catch (\Exception $e) {
            return ['error' => 'Failed to upload image: ' . $e->getMessage()];
        }
    }

    /**
     * Search users
     * 
     * @return array
     */
    public function search() {
        return $this->usersModel->searchUsers($this->payload['query'], 1, 20, $this->payload['first_part'] ?? false);
    }
} 