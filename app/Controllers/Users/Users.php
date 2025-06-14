<?php

namespace App\Controllers\Users;

use App\Controllers\LoadController;

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
     * Search users
     * 
     * @return array
     */
    public function searchUsers() {
        return $this->usersModel->searchUsers($this->payload['query'], $this->payload['page'], $this->payload['limit']);
    }
} 