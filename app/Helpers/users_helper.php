<?php
/**
 * Get the user IP address
 * 
 * @return string
 */
function getUserIpaddress() {
    $userIpaddress = $_SERVER['REMOTE_ADDR'] ?? '';
    if(empty($userIpaddress) || strlen($userIpaddress) < 6) {
        $userIpaddress = $_SERVER['HTTP_HOST'];
    }
    return $userIpaddress;
}

/**
 * Manage the user location
 * 
 * @param array $payload
 * @param object $cacheObject
 * 
 * @return array
 */
function manageUserLocation($payload, $cacheObject) {
    

    // set the final location to an empty array
    $payload['finalLocation'] = [];

    if(empty($payload['userUUID'])) {
        return $payload;
    }

    if(!empty($payload['longitude']) && strlen($payload['longitude']) == 4) {
        $payload['longitude'] = '';
    }

    if(!empty($payload['latitude']) && strlen($payload['latitude']) == 4) {
        $payload['latitude'] = '';
    }

    if(empty($payload['longitude']) && empty($payload['latitude'])) {
        $cacheKey = create_cache_key('user', 'location', ['user_id' => $payload['userUUID'].getUserIpaddress()]);
        $locationInfo = $cacheObject->get($cacheKey);

        // get the data to use
        $dataToUse = !empty($locationInfo) ? $locationInfo : getLocationByIP();

        if(!empty($dataToUse)) {
            $locs = explode(',', $dataToUse['loc']);
            $payload['latitude'] = $locs[0];
            $payload['longitude'] = $locs[1];
            $payload['city'] = $dataToUse['city'];
            $payload['country'] = $dataToUse['country'];
            $payload['district'] = $dataToUse['region'];
            $cacheObject->save($cacheKey, $dataToUse, 'user.location', null, 60 * 60);
        }

    }

    elseif(!empty($payload['longitude']) && !empty($payload['latitude'])) {

        // get the cache key
        $cacheKey = create_cache_key('user', 'location', ['latitude' => $payload['latitude'], 'longitude' => $payload['longitude']]);
        $locationInfo = $cacheObject->get($cacheKey);

        // get the data to use
        $dataToUse = !empty($locationInfo) ? $locationInfo : getLocationByIP($payload['longitude'], $payload['latitude']);
        
        print_r($dataToUse);
        exit;
        $theCity = $dataToUse['results'][0]['components']['town'] ?? ($dataToUse['results'][0]['components']['city'] ?? null);
       
        // handle the user location data
        if(!empty($theCity)) {
            $payload['city'] = $theCity;
            $payload['country'] = $dataToUse['results'][0]['components']['country'] ?? null;
            $payload['district'] = $dataToUse['results'][0]['components']['county'] ?? null;

            $cacheObject->save($cacheKey, $dataToUse, 'user.location', null, 60 * 60);
        }
    }

    $final = [
        'city' => $payload['city'] ?? '',
        'district' => $payload['district'] ?? '',
        'country' => $payload['country'] ?? '',
        'latitude' => $payload['latitude'] ?? '',
        'longitude' => $payload['longitude'] ?? '',
    ];

    $payload['finalLocation'] = $final;

    return $payload;
}

/**
 * Get the location by IP
 * 
 * @return array
 */
function getLocationByIP($longitude = null, $latitude = null) {

    $userIpaddress = $_SERVER['REMOTE_ADDR'] ?? '';
    if(empty($userIpaddress) || strlen($userIpaddress) < 6) {
        $userIpaddress = '';
    }

    // Fetch location data from ipapi.co
    $url = "https://ipinfo.io/{$userIpaddress}?token=2d64e9f7d9e7a2";
    $reverseUrl = "https://api.opencagedata.com/geocode/v1/json?q={$latitude},{$longitude}&pretty=1&key=8cc86300ce5d4a03af06f30acbdb5946";

    // set the url path
    $urlPath = empty($longitude) && empty($latitude) ? $url : $reverseUrl;

    // use curl to get the data
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlPath);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response !== false) {
        $data = json_decode($response, true);
        $data['api_url'] = $urlPath;
        $data['ip'] = !empty($userIpaddress) ? $userIpaddress : $_SERVER['HTTP_HOST'];
        return $data;
    }
}

/**
 * Mask the email address
 * 
 * @param array $users
 * 
 * @return array
 */
function mask_email_address($users) {
    // check if the users is empty or not an array
    if(empty($users) || !is_array($users)) return [];
    // loop through the users
    foreach($users as $key => $value) {
        $users[$key]['user_id'] = (int) $value['user_id'];
        if(isset($value['last_login'])) {
            // convert the last login to date
            $users[$key]['last_login'] = convertTimestampToDate($value['last_login']);
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
            $users[$key]['email'] = substr($value['email'], 0, 3) . '***@***.com';
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
 * Has some access
 * 
 * @param array $currentUser
 * @param int $account_id
 * @param string $access
 * @param int $idSite
 * 
 * @return bool
 */
function has_some_access($currentUser, $account_id, $access, $idSite = null) {

    // check if the user is an admin
    if($currentUser['isAdmin']) return true;

    // check if the user has access to the account
    if(empty($idSite) && !empty($account_id)) {
        if(!isset($currentUser['accounts_group'][$account_id])) return false;
        if(isset($currentUser['accounts_group'][$account_id]['access'])) {
            if(!in_array("{$access}_access", array_values($currentUser['accounts_group'][$account_id]['access']))) {
                return false;
            }
        }
        return $currentUser['accounts_group'][$account_id];
    }

    // check if the user has access to the site
    if(isset($currentUser['access_groups'][$access])) {
        return in_array($idSite, $currentUser['access_groups'][$access]);
    }

    // check if the user has access to the account
    return false;
}

/**
 * Has write access
 * 
 * @param array $currentUser
 * @param int $account_id
 * 
 * @return bool
 */
function has_write_access($currentUser, $account_id) {
    return has_some_access($currentUser, $account_id, "write");
}

/**
 * Has website write access
 * 
 * @param array $currentUser
 * @param int $idSite
 * 
 * @return bool
 */
function has_website_write_access($currentUser, $idSite = null) {
    return has_some_access($currentUser, null, "write", $idSite);
}

/**
 * Has view access
 * 
 * @param array $currentUser
 * @param int $account_id
 * @param int $idSite
 * @return bool
 */
function has_view_access($currentUser, $account_id, $idSite = null) {
    return has_some_access($currentUser, $account_id, "view", $idSite);
}

/**
 * Has website view access
 * 
 * @param array $currentUser
 * @param int $idSite
 * 
 * @return bool
 */
function has_website_view_access($currentUser, $idSite = null) {
    return has_some_access($currentUser, null, "view", $idSite);
}

/**
 * Has admin access
 * 
 * @param array $currentUser
 * @param int $account_id
 * @param int $idSite
 * 
 * @return bool
 */
function has_admin_access($currentUser, $account_id, $idSite = null) {
    return has_some_access($currentUser, $account_id, "admin", $idSite);
}

/**
 * Has website admin access
 * 
 * @param array $currentUser
 * @param int $idSite
 * 
 * @return bool
 */
function has_website_admin_access($currentUser, $idSite = null) {
    return has_some_access($currentUser, null, "admin", $idSite);
}

/**
 * Is student
 * 
 * @param array $currentUser
 * 
 * @return bool
 */
function is_student($currentUser) {
    return (bool) !empty($currentUser['isStudent']);
}

/**
 * Is instructor
 * 
 * @param array $currentUser
 * 
 * @return bool
 */
function is_instructor($currentUser) {
    return (bool) !empty($currentUser['isInstructor']);
}

/**
 * Is admin or instructor
 * 
 * @param array $currentUser
 * 
 * @return bool
 */
function is_admin_or_instructor($currentUser) {
    return is_admin($currentUser) || is_instructor($currentUser);
}

/**
 * Is admin
 * 
 * @param array $currentUser
 * 
 * @return bool
 */
function is_admin($currentUser) {
    return (bool) !empty($currentUser['isAdmin']);
}

/**
 * Is super admin
 * 
 * @param array $currentUser
 * 
 * @return bool
 */
function is_super_admin($currentUser) {
    return (bool) !empty($currentUser['isSuperAdmin']);
}

/**
 * Is super user
 * 
 * @param array $currentUser
 * 
 * @return bool
 */
function is_super_user($currentUser) {
    return (bool) !empty($currentUser['isSuperAdmin']);
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
 * Format the team response
 * 
 * @param array $fullData
 * @param array $currentUser
 * 
 * @return array
 */
function formatTeamResponse($fullData, $currentUser) {

    // create the team members
    $teamMembers = [];

    // loop through the full data
    foreach($fullData as $i => $row) {

        // create the owner
        $teamMembers[$row['account_id']]['account'] = [
            'id' => $row['account_id'],
            'name' => $row['account_name'],
        ];
        $teamMembers[$row['account_id']]['owner'] = [];
        if($row['account_id'] == $currentUser['account_id']) {
            $teamMembers[$row['account_id']]['owner'] = [
                'user_id' => $currentUser['user_id'],
                'name' => $currentUser['full_name'],
                'email' => $currentUser['email'],
                'access' => 'Account owner',
            ];
        }

        // if the owner is not set
        if(empty($teamMembers[$row['account_id']]['owner'])) {
            $teamMembers[$row['account_id']]['owner'] = [
                'user_id' => $row['owner_id'],
                'name' => $row['account_name'],
                'access' => 'Account owner',
            ];
        }

        // create the permission
        $permission = "read";
        $explode = explode(',', $row['permissions']);
        if(in_array('write', $explode)) $permission = "write";
        if(in_array('admin', $explode)) $permission = "admin";

        $user = [
            'user_id' => $row['user_id'],
            'name' => $row['full_name'],
            'access' => ucwords($permission),
            'email' => $row['email'],
            'status' => $row['status'],
            'site_ids' => !empty($row['site_ids']) ? array_map('intval', explode(',', $row['site_ids'])) : []
        ];

        $user['can_modify'] = false;
        if(has_write_access($currentUser, $row['account_id'])) {
            $user['can_modify'] = true;
        }

        // create the user
        $teamMembers[$row['account_id']]['users'][] = $user;
    }

    foreach($teamMembers as $record) {
        $finalData[] = $record;
    }

    return $finalData ?? [];
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
