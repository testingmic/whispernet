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
    public function getUserProfile() {
        return $this->usersModel->getUserProfile($this->payload['userId']);
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
     * Get user settings
     * 
     * @return array
     */
    public function update() {

        // get the user id
        $userId = $this->payload['userId'] ?? $this->currentUser['user_id'];

        // get user settings
        $userSettings = $this->usersModel->getUserSettings($userId);

        // if the setting is not empty, validate the setting
        if(!empty($this->payload['setting'])) {
            $setting = trim($this->payload['setting']);
            // check if the setting is valid
            if(!in_array($setting, array_keys(listUserSettings()))) {
                return Routing::error("Invalid setting:- {$setting}");
            }
        }

        // if the user settings is empty, create a new one
        if(empty($userSettings)) {
            $setting = $this->payload['setting'] ?? null;
            $settingValue = $this->payload['value'] ?? null;

            if(!empty($setting) && !empty($settingValue)) {
                $this->usersModel->createUserSettings($userId, trim($setting), $settingValue);
            }
        } else {
            // user settings
            $settingsSet = array_column($userSettings, 'setting');

            // loop through the settings and confirm if the setting is not already saved
            if(!empty($this->payload['setting'])) {
                $isetting = !is_array($this->payload['setting']) ? trim($this->payload['setting']) : $this->payload['setting'];
                if(in_array($isetting, $settingsSet)) {
                    $this->usersModel->updateUserSettings($userId, $isetting, $this->payload['value']);
                } else {
                    $this->usersModel->createUserSettings($userId, $isetting, $this->payload['value']);
                }
            }
        }

        // get the settings
        $userSettings = $this->usersModel->getUserSettings($userId);

        return Routing::created(['data' => 'User settings successfully saved.', 'record' => formatUserSettings($userSettings)]);
    }

    /**
     * Search users
     * 
     * @return array
     */
    public function search() {
        return $this->usersModel->searchUsers($this->payload['query'], 1, 20, $this->payload['first_part']);
    }
} 