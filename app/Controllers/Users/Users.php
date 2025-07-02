<?php

namespace App\Controllers\Users;

use App\Controllers\LoadController;
use App\Models\UsersModel;
use App\Libraries\Routing;

class Users extends LoadController {

    protected $usersModel;

    public function __construct()
    {
        parent::__construct();
        $this->usersModel = new UsersModel();
    }

    /**
     * Get users list with pagination and filters
     * 
     * @return array
     */
    public function index()
    {
        $page = (int)($this->request->getGet('page') ?? 1);
        $limit = (int)($this->request->getGet('limit') ?? 10);
        $search = $this->request->getGet('search') ?? '';
        $status = $this->request->getGet('status') ?? 'all';
        $role = $this->request->getGet('role') ?? 'all';

        // Build filters
        $filters = [];
        if (!empty($search)) {
            $filters['search'] = $search;
        }
        if ($status !== 'all') {
            $filters['status'] = $status;
        }
        if ($role !== 'all') {
            $filters['role'] = $role;
        }

        // Get users with pagination
        $users = $this->usersModel->getUsers($filters, $page, $limit);
        $total = $this->usersModel->getUsersCount($filters);

        return Routing::success([
            'users' => $users,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }

    /**
     * Get user statistics
     * 
     * @return array
     */
    public function stats()
    {
        $stats = $this->usersModel->getStats();
        return Routing::success($stats);
    }

    /**
     * Get single user by ID
     * 
     * @param int $userId
     * @return array
     */
    public function show($userId)
    {
        $user = $this->usersModel->getUserById($userId);
        
        if (!$user) {
            return Routing::error('User not found', 404);
        }

        return Routing::success($user);
    }

    /**
     * Create new user
     * 
     * @return array
     */
    public function create()
    {
        $data = [
            'full_name' => $this->payload['fullName'] ?? '',
            'username' => $this->payload['username'] ?? '',
            'email' => $this->payload['email'] ?? '',
            'password' => $this->payload['password'] ?? '',
            'role' => $this->payload['role'] ?? 'user',
            'status' => $this->payload['status'] ?? 'active'
        ];

        // Validate required fields
        if (empty($data['full_name']) || empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            return Routing::error('All fields are required');
        }

        // Check if username or email already exists
        if ($this->usersModel->userExists($data['username'], $data['email'])) {
            return Routing::error('Username or email already exists');
        }

        $userId = $this->usersModel->createUser($data);
        
        if ($userId) {
            return Routing::success(['user_id' => $userId], 'User created successfully');
        } else {
            return Routing::error('Failed to create user');
        }
    }

    /**
     * Update user (Admin)
     * 
     * @param int $userId
     * @return array
     */
    public function updateUser($userId)
    {
        $user = $this->usersModel->getUserById($userId);
        
        if (!$user) {
            return Routing::error('User not found', 404);
        }

        $data = [
            'full_name' => $this->payload['fullName'] ?? $user['full_name'],
            'username' => $this->payload['username'] ?? $user['username'],
            'email' => $this->payload['email'] ?? $user['email'],
            'role' => $this->payload['role'] ?? $user['role'],
            'status' => $this->payload['status'] ?? $user['status']
        ];

        // Check if password is provided
        if (!empty($this->payload['password'])) {
            $data['password'] = $this->payload['password'];
        }

        // Check if username or email already exists (excluding current user)
        if ($this->usersModel->userExists($data['username'], $data['email'], $userId)) {
            return Routing::error('Username or email already exists');
        }

        $success = $this->usersModel->updateUser($userId, $data);
        
        if ($success) {
            return Routing::success([], 'User updated successfully');
        } else {
            return Routing::error('Failed to update user');
        }
    }

    /**
     * Delete user
     * 
     * @param int $userId
     * @return array
     */
    public function delete($userId)
    {
        $user = $this->usersModel->getUserById($userId);
        
        if (!$user) {
            return Routing::error('User not found', 404);
        }

        // Prevent deleting admin users
        if ($user['role'] === 'admin') {
            return Routing::error('Cannot delete admin users');
        }

        $success = $this->usersModel->deleteUser($userId);
        
        if ($success) {
            return Routing::success([], 'User deleted successfully');
        } else {
            return Routing::error('Failed to delete user');
        }
    }

    /**
     * Update user status
     * 
     * @param int $userId
     * @return array
     */
    public function updateStatus($userId)
    {
        $user = $this->usersModel->getUserById($userId);
        
        if (!$user) {
            return Routing::error('User not found', 404);
        }

        $status = $this->payload['status'] ?? '';
        
        if (!in_array($status, ['active', 'blocked', 'pending'])) {
            return Routing::error('Invalid status');
        }

        $success = $this->usersModel->updateUserStatus($userId, $status);
        
        if ($success) {
            return Routing::success([], 'User status updated successfully');
        } else {
            return Routing::error('Failed to update user status');
        }
    }

    /**
     * Bulk actions on users
     * 
     * @return array
     */
    public function bulkAction()
    {
        $action = $this->payload['action'] ?? '';
        $userIds = $this->payload['userIds'] ?? [];

        if (empty($userIds) || !is_array($userIds)) {
            return Routing::error('No users selected');
        }

        switch ($action) {
            case 'block':
                $success = $this->usersModel->bulkUpdateStatus($userIds, 'blocked');
                break;
            case 'unblock':
                $success = $this->usersModel->bulkUpdateStatus($userIds, 'active');
                break;
            case 'delete':
                $success = $this->usersModel->bulkDelete($userIds);
                break;
            default:
                return Routing::error('Invalid action');
        }

        if ($success) {
            return Routing::success([], 'Bulk action completed successfully');
        } else {
            return Routing::error('Failed to perform bulk action');
        }
    }

    /**
     * Export users
     * 
     * @return array
     */
    public function export()
    {
        $search = $this->request->getGet('search') ?? '';
        $status = $this->request->getGet('status') ?? 'all';
        $role = $this->request->getGet('role') ?? 'all';
        $format = $this->request->getGet('format') ?? 'json';

        // Build filters
        $filters = [];
        if (!empty($search)) {
            $filters['search'] = $search;
        }
        if ($status !== 'all') {
            $filters['status'] = $status;
        }
        if ($role !== 'all') {
            $filters['role'] = $role;
        }

        $users = $this->usersModel->getUsersForExport($filters);

        if ($format === 'csv') {
            return $this->exportToCsv($users);
        }

        return Routing::success($users);
    }

    /**
     * Export users to CSV
     * 
     * @param array $users
     * @return array
     */
    private function exportToCsv($users)
    {
        $filename = 'users-export-' . date('Y-m-d') . '.csv';
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, [
            'User ID',
            'Full Name',
            'Username',
            'Email',
            'Role',
            'Status',
            'Created At',
            'Last Activity'
        ]);

        // CSV data
        foreach ($users as $user) {
            fputcsv($output, [
                $user['user_id'],
                $user['full_name'],
                $user['username'],
                $user['email'],
                $user['role'],
                $user['status'],
                $user['created_at'],
                $user['last_activity'] ?? 'Never'
            ]);
        }

        fclose($output);
        exit;
    }

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

        // get the user settings
        $profile['settings'] = $this->settings()['data'];

        // return the profile
        return Routing::success($profile);

    }

    /**
     * Get user location
     * 
     * @return array
     */
    public function location() {
        // get the user location
        $location = manageUserLocation($this->payload, $this->cacheObject);
        
        // set the result
        $result = $location['finalLocation'];
        $result['agent'] = $location['agent'];
        $result['ipaddress'] = $location['ipaddress'];

        return Routing::success($result);
    }

    /**
     * Delete user account
     * 
     * @return array
     */
    public function goodbye() {
    
        // delete the cache
        $this->cacheObject->dbObject->query("DELETE FROM cache WHERE account_id = ?", [$this->currentUser['user_id']]);

        // delete the user
        $this->usersModel->deleteAccount($this->currentUser['user_id']);

        // destroy the session
        session()->destroy();

        // return the success message
        return Routing::success('Account deleted successfully');
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
                $this->payload['value'] = empty($this->payload['value']) ? 0 : $this->payload['value'];

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

            // Update user profile with image path
            $imageUrl = '/assets/uploads/profiles/' . $filename;
            $this->usersModel->updateProfile($userId, ['profile_image' => $imageUrl]);

            // Resize image to create thumbnail
            $image = \Config\Services::image()
                ->withFile($filePath)
                ->resize(350, 350, true, 'center')
                ->save(rtrim(PUBLICPATH, '/') .$imageUrl);

            return ['success' => true, 'image_url' => $imageUrl, 'thumbnail_url' => rtrim(PUBLICPATH, '/') . $imageUrl];

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