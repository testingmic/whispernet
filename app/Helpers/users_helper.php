<?php
/**
 * Format the user settings
 * 
 * @param array $settings
 * 
 * @return array
 */
function formatUserSettings($settings, $alldata = false) {
    $formattedSettings = [];
    foreach($settings as $setting) {
        $iValue = in_array($setting['setting'], ['sub_notification']) ? json_decode($setting['value'], true) : $setting['value'];
        if(!$alldata) {
            $formattedSettings[$setting['setting']] = !is_array($iValue) ? (int)$iValue : $iValue;
        } else {
            $setting['value'] = !is_array($iValue) ? (int)$iValue : $iValue;
            $formattedSettings[] = $setting;
        }
    }
    return $formattedSettings;
}

/**
 * List the user settings
 * 
 * @param array $settings
 * 
 * @return array
 */
function listUserSettings($settings = []) {
    $userSettings = [
        'sub_notification' => [
            'noDisplay' => true,
        ],
        'push_notifications' => [
            'title' => 'Push Notifications',
            'class' => 'w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-4',
            'description' => 'Receive push notifications for new messages and mentions',
            'icon_class' => 'w-5 h-5 text-blue-600 dark:text-blue-400',
            'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
            'value' => $settings['push_notifications'] ?? '0'
        ],
        'email_notifications' => [
            'title' => 'Email Notifications',
            'class' => 'w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-4',
            'description' => 'Receive email notifications for new messages and mentions',
            'icon_class' => 'w-5 h-5 text-blue-600 dark:text-blue-400',
            'icon' => 'M15 17h5l-5 5v-5zM4.19 4.19A2 2 0 004 6v10a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2z',
            'value' => $settings['email_notifications'] ?? '0'
        ],
        'profile_visibility' => [
            'title' => 'Profile Visibility',
            'class' => 'w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-4',
            'description' => 'Make your profile visible to other users',
            'icon_class' => 'w-5 h-5 text-green-600 dark:text-green-400',
            'top_icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z',
            'icon' => 'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
            'value' => $settings['profile_visibility'] ?? '1',
        ],
        'search_visibility' => [
            'title' => 'Search Visibility',
            'class' => 'w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-4',
            'description' => 'Make your profile appear in search results and for chat users',
            'icon_class' => 'w-5 h-5 text-purple-600 dark:text-purple-400',
            'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
            'value' => $settings['search_visibility'] ?? '0',
        ],
        'dark_mode' => [
            'title' => 'Dark Mode',
            'class' => 'w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mr-4',
            'description' => 'Switch between light and dark theme',
            'icon_class' => 'w-5 h-5 text-yellow-600 dark:text-yellow-400',
            'icon' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z',
            'value' => $settings['dark_mode'] ?? '0',
        ]
    ];
    return $userSettings;
}

/**
 * Mask the email address
 * 
 * @param array $users
 * 
 * @return array
 */
function mask_email_address($users, $first_part = false) {
    // check if the users is empty or not an array
    if(empty($users) || !is_array($users)) return [];
    
    // loop through the users
    foreach($users as $key => $value) {
        $users[$key]['user_id'] = (int) $value['user_id'];

        if(!empty($value['full_name'])) {
            $users[$key]['full_name'] = $first_part ? explode(' ', $value['full_name'])[0] : $value['full_name'];
        }

        if(isset($value['last_login'])) {
            // convert the last login to date
            // $users[$key]['last_login'] = convertTimestampToDate($value['last_login']);
            // check the last login to today
            $days_ago = timeDifference($users[$key]['last_login']);
            // $users[$key]['date_range'] = $days_ago;

            // check the online status
            if($days_ago['months'] > 0) {
                $users[$key]['online_status'] = $days_ago['months'] . ' Long time ago';
            } elseif($days_ago['days'] > 0) {
                $users[$key]['online_status'] = $days_ago['days'] . ' day'.($days_ago['days'] > 1 ? 's' : '').' ago';
            } else if($days_ago['hours'] > 0) {
                $users[$key]['online_status'] = $days_ago['hours'] . ' hour'.($days_ago['hours'] > 1 ? 's' : '').' ago';
            } else if($days_ago['minutes'] > 40) {
                $users[$key]['online_status'] = $days_ago['minutes'] . ' minute'.($days_ago['minutes'] > 1 ? 's' : '').' ago';
            } else {
                $users[$key]['online_status'] = 'Offline';
            }
        }
        if(isset($value['email'])) {
            $users[$key]['email'] = substr($value['email'], 0, 2) . '****@***.com';
        }
    }
    return $users;
}

/**
 * User state
 * 
 * @param string $last_login
 * @return string
 */
function userState($last_login) {

    // check the last login to today
    $days_ago = timeDifference($last_login);

    // check the online status
    if($days_ago['months'] > 0) {
        $state = $days_ago['months'] . ' Long time ago';
    } elseif($days_ago['days'] > 0) {
        $state = $days_ago['days'] . ' day'.($days_ago['days'] > 1 ? 's' : '').' ago';
    } elseif($days_ago['days'] > 0) {
        $state = $days_ago['days'] . ' day'.($days_ago['days'] > 1 ? 's' : '').' ago';
    } else if($days_ago['hours'] > 0) {
        $state = $days_ago['hours'] . ' hour'.($days_ago['hours'] > 1 ? 's' : '').' ago';
    } else if($days_ago['minutes'] > 0) {
        $state = $days_ago['minutes'] . ' minute'.($days_ago['minutes'] > 1 ? 's' : '').' ago';
    } else {
        $state = 'Online';
    }

    return $state;
}
    
/**
 * Format the user response
 * 
 * @param array $user
 * @param bool $single
 * @param array $currentUser
 * @param bool $userId
 * @param string|null $search
 * 
 * @return array
 * 
 */
function formatUserResponse($user, bool $single = false, $simpleData = false) {
    if(empty($user)) return [];

    // format the user response
    foreach($user as $key => $value) {

        // if the user is an admin and the user id is the same as the current user id, skip the user
        if(empty($value)) continue;

        // format the user response
        $result[] = [
            "user_id" => $value['id'],
            "email" => $value['email'],
            "firstname" => $value['firstname'],
            "lastname" => $value['lastname'],
            "status" => $value['status'],
            "user_type" => $value['user_type'],
            "gender" => $value['gender'],
            "job_title" => $value['job_title'],
            "skills" => $value['skills'],
            "rating" => $value['rating'],
            "billing_address" => $value['billing_address']
        ];

        if(!$simpleData) {
            foreach(['username', 'two_factor_setup', 'nationality', 'date_of_birth', 'phone', 'preferences', 'students_count', 'coursesCount',
                'timezone', 'website', 'company', 'language', 'last_login', 'permissions', 'admin_access', 'date_registered'] as $item) {
                $result[$key][$item] = $value[$item];
            }
            $result[$key]['preferences'] = json_decode($value['preferences'], true);
            $result[$key]['preferences'] = empty($result[$key]['preferences']) ? [] : $result[$key]['preferences'];
        }
    }

    return $single ? ($result[0] ?? []) : ($result ?? []);
}

/**
 * Thin profile
 * 
 * @param array $userProfile
 * 
 * @return array
 */
function thin_profile($userProfile) {
    foreach(['two_factor_setup', 'billing_address', 'nationality', 'gender', 'date_of_birth', 'phone'] as $item) {
        unset($userProfile[$item]);
    }
    return $userProfile;
}

/**
 * Is instructor
 * 
 * @param array $currentUser
 * 
 * @return bool
 */
function is_moderator($currentUser, $key = 'isModerator') {
    return (bool) !empty($currentUser[$key]);
}

/**
 * Is admin or instructor
 * 
 * @param array $currentUser
 * 
 * @return bool
 */
function is_admin_or_moderator($currentUser) {
    return is_admin($currentUser) || is_moderator($currentUser);
}

/**
 * Is admin
 * 
 * @param array $currentUser
 * 
 * @return bool
 */
function is_admin($currentUser, $key = 'isAdmin') {
    return (bool) !empty($currentUser[$key]);
}

/**
 * Is super admin
 * 
 * @param array $currentUser
 * 
 * @return bool
 */
function is_super_admin($currentUser, $key = 'isSuperAdmin') {
    return (bool) !empty($currentUser[$key]);
}

/**
 * Is super user
 * 
 * @param array $currentUser
 * 
 * @return bool
 */
function is_super_user($currentUser, $key = 'isSuperAdmin') {
    return (bool) !empty($currentUser[$key]);
}

/**
 * Format the account response
 * 
 * @param array $account
 * 
 * @return array
 * 
 */
function formatAccountResponse($account, string $ikey = 'account') {
    
    if(empty($account)) return [];

    // format the account response
    foreach($account as $key => $value) {

        $permission = "read";
        $explode = explode(',', $value['access']);
        if(in_array('write', $explode)) $permission = "write";
        if(in_array('admin', $explode)) $permission = "admin";

        // account_name
        $account_name = ($value['account_name'] ?? ($value['user_name'] ?? null));

        $result[] = [
            "account_id" => (int) $value['account_id'],
            "{$ikey}_name" => !empty($account_name) ? $account_name : ($value['full_name'] ?? null),
            "{$ikey}_email" => ($value['account_email'] ?? ($value['user_email'] ?? $value['email'])),
            "user_id" => (int) $value['user_id'],
            "access" => $permission,
            "site_ids" => !empty($value['site_ids']) ? explode(',', $value['site_ids']) : []
        ];

    }
    return $result;
}

/**
 * Group the user access
 * 
 * @param array $websites
 * 
 * @return array
 */
function groupUserAccess($websites) {

    if(empty($websites)) return [];

    $result = [];
    foreach($websites as $access) {
        $name = $access['access'];
        unset($access['access']);
        $result[$name][] = (int) $access['idsite'];
    }

    foreach(['view', 'write', 'admin'] as $key) {
        $result[$key] = array_unique($result[$key] ?? []);
    }

    return $result;
}

/**
 * Generate username from username
 *
 * @param string $email
 *
 * @return string
 */
function generateUsername($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $email;
    }
    $username = str_ireplace(["@", "_", "-", ".com", ".org", ".uk", ".co"], [""], substr($email, 0, strrpos($email, ".",)));
    return strtolower($username);
}

/**
 * Hash the password
 * 
 * @param string $password
 * 
 * @return string
 */
function hash_password($password) {
    return password_hash(md5($password), PASSWORD_DEFAULT);
}
?>
