<?php 


namespace App\Controllers\Auth;

use App\Controllers\LoadController;

use App\Models\AuthModel;
use App\Libraries\Routing;
use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\Providers\Qr\QRServerProvider;

class Auth extends LoadController {

    private $internal = false;

    /**
     * Login attempts
     * 
     * @param string $attemptKey
     * @param array $loginLogs
     * @param int $loginAttempts
     * @return void
     */
    public function loginAttempts($attemptKey, $loginLogs, $loginAttempts, $setup = 'login.attempt') {
        // increment the login attempts
        $loginAttempts = ($loginLogs['value'] ?? $loginAttempts) + 1;

        // if the login logs are not empty, update the cache
        if(!empty($loginLogs)) {
            $this->cacheObject->dbObject->query("UPDATE cache SET value = {$loginAttempts} WHERE cache_key = '{$attemptKey}'");
        } else {
            // insert the cache
            $this->cacheObject->dbObject->table('cache')->insert([
                'cache_key' => $attemptKey,
                'value' => 1,
                'setup' => $setup,
                'secondary_key' => $this->payload['email'],
                'account_id' => 0,
                'expires' => date('Y-m-d H:i:s', strtotime("+1 hour")),
                'server_time' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Login the user
     * 
     * @return array
     */
    public function login($email = null, $password = null, $payload = []) {

        // check if the email is valid
        if(!isValidEmail($this->payload['email'] ?? $email)) {
            return Routing::error('Sorry! You cannot use a disposable email address.');
        }

        // if the fingerprint is not empty, set the fingerprint
        $fingerprint = !empty($payload) ? $payload['fingerprint'] : $this->payload['fingerprint'];
        
        // get the login attempts counter
        $attemptKey = md5('login_' . $fingerprint);
        $loginLogs = $this->cacheObject->dbObject->query("SELECT * FROM cache WHERE setup = 'login.attempt' AND cache_key = '{$attemptKey}'")->getRowArray();

        $loginAttempts = 1;

        // if the login attempts counter is empty, set it to 0
        if(!empty($loginLogs) && (int)$loginLogs['value'] >= configs('login_attempts')) {

            // get the time ago
            $agoTimer = daysDifference($loginLogs['server_time'], 'everything');

            // if the time ago is less than 1 hour, return an error
            if($agoTimer->i < 10) {
                return Routing::error('Too many login attempts. Please try again later.', [], 429);
            }

            if($agoTimer->i >= 10) {
                $this->cacheObject->dbObject->query("DELETE FROM cache WHERE cache_key = '{$attemptKey}' AND setup = 'login.attempt'");
                $loginLogs = [];
            }
        }

        // Find user by email
        $user = $this->usersModel->findByEmail($this->payload['email'] ?? $email);
        if(empty($user)) {
            $this->loginAttempts($attemptKey, $loginLogs, $loginAttempts);
            return Routing::error('Invalid login credentials.');
        }

        // if the user is not active, return an error
        if($user['status'] !== 'active') {
            return Routing::error('Sorry! Your account is not active. Please contact support.');
        }

        // check if the user is an admin
        $isLogger = false;

        // decode the password
        $this->payload['password'] = html_entity_decode($this->payload['password'] ?? $password);

        // Verify password
        if(!password_verify(md5($this->payload['password'] ?? $password), $user['password_hash'])) {
            $this->loginAttempts($attemptKey, $loginLogs, $loginAttempts);
            return Routing::error('Invalid login credentials.');
        }

        // Generate response
        $response = [
            'user_id'   => (int) $user['user_id'],
            'full_name' => $user['full_name'],
            'username' => $user['username'],
            'two_factor_setup' => false,
            'email' => $user['email'],
            'user_type' => $user['user_type']
        ];

        // update the user last login date
        if(!$isLogger) {
            $this->usersModel->update($user['user_id'], ['last_login' => date('Y-m-d H:i:s')]);
        }

        // Check if two factor setup
        $twoFactorSetup = (bool) ((int)$user['two_factor_setup'] == 1);

        // if two factor setup is true, set the two factor setup and twofactor_secret
        if ($twoFactorSetup) {
            $response['token'] = false;
            $response['two_factor_setup'] = true;
            $response['twofactor_secret'] = md5($user['twofactor_secret']);
        } else {
            // generate the token
            $response['token'] = $this->generateTokenAuth($user, $isLogger ? 'Admin' : 'User');
        }

        // Delete the user token hash
        $this->authModel->deleteByLogin(md5($user['email']));

        // if the webapp is true, set the session
        if(!empty($this->payload['webapp']) || $this->internal) {
            $this->setSession($response['token'], $user, $this->payload);
        }

        // delete the login logs
        $this->cacheObject->dbObject->query("DELETE FROM cache WHERE cache_key = '{$attemptKey}' AND setup = 'login.attempt'");
        
        // Return the response
        return [
            'message' => !empty($email) && !empty($password) ? 'Registration successful' : 'Login successful',
            'data' => $response,
            'success' => true
        ];

    }

    /**
     * Set the session
     * 
     * @param string $token
     * @param array $user
     * @return void
     */
    private function setSession($token, $user, $payload = []) {
        $sessionObject = session();
        $sessionObject->set('user_token', $token);
        $sessionObject->set('user_id', $user['user_id']);
        $sessionObject->set('user_loggedin', true);
        $sessionObject->set('user_type', $user['user_type']);

        // if the longitude and latitude are provided, set the session
        if(!empty($payload['longitude']) && !empty($payload['latitude'])) {
            $sessionObject->set('userLongitude', $payload['longitude'] ?? '');
            $sessionObject->set('userLatitude', $payload['latitude'] ?? '');
        }

        // set the user type
        $user['isAdmin'] = ($user['user_type'] == 'admin');
        $user['isModerator'] = ($user['user_type'] == 'moderator');
        $user['isAdminOrModerator'] = ($user['user_type'] == 'admin') || ($user['user_type'] == 'moderator');

        $sessionObject->set('userData', $user);
    }

    /**
     * Confirm the user
     * 
     * @return array
     */
    public function confirm() {
        
        // if the current user is empty, return an error
        if(empty($this->currentUser)) {
            return Routing::error('User not found.');
        }
        
        // if the webapp is true, set the session
        if(!empty($this->payload['webapp'])) {
            $this->setSession($this->payload['token'], $this->currentUser, $this->payload);
        }

        return [
            'message' => 'Login successful',
            'data' => [
                'user_id'   => (int) $this->currentUser['user_id'],
                'full_name' => $this->currentUser['full_name'],
                'username' => $this->currentUser['username'],
                'two_factor_setup' => false,
                'token' => $this->payload['token'],
                'email' => $this->currentUser['email']
            ],
            'success' => true
        ];
    }

    /**
     * Register the user
     * 
     * @return array
     */
    public function register() {

        // check if the email is valid
        if(!isValidEmail($this->payload['email'])) {
            return Routing::error('Sorry! You cannot use a disposable email address.');
        }

        // Find user by email
        $user = $this->usersModel->findByEmail($this->payload['email']);
        if(!empty($user)) {
            return Routing::error('User already exists.');
        }

        $payload = [
            'username' => $this->payload['email'],
            'email' => $this->payload['email'],
            'password_hash' => hash_password($this->payload['password']),
            'full_name' => $this->payload['full_name'],
            'is_verified' => 0,
            'is_active' => 1,
            'last_login' => date('Y-m-d H:i:s'),
        ];

        // Insert the user
        $this->usersModel->insert($payload);
        $userId = $this->usersModel->getInsertID();

        // update the username of the user
        $this->usersModel->update($userId, ['username' => "user{$userId}"]);

        // create the user settings
        $this->usersModel->createUserSettings($userId, 'profile_visibility', 1);

        // set the internal to true
        $this->internal = true;

        // Login the user
        return $this->login($this->payload['email'], $this->payload['password'], $this->payload);

    }

    /**
     * Two Factor Authentication Setup
     *
     * @return array
     */
    public function setup2fa() {

        try {

            if ($this->currentUser['two_factor_setup']) {
                return Routing::error("You already have Two Factor Authentication setup", "info");
            }
            
            $two2fa = new TwoFactorAuth(new QRServerProvider());
            $secret = $two2fa->createSecret();
            $qrcode = $two2fa->getQRCodeImageAsDataUri($this->currentUser['email'], $secret);

            $this->usersModel->update($this->currentUser['user_id'], ['twofactor_secret' => $secret]);

            return Routing::success(['secret' => $secret, 'qrcode' => $qrcode, 'image' => "<img width='250' src='{$qrcode}' />"]);

        } catch (\Exception $e) {
            return Routing::error($e->getMessage());
        }
    }

    /**
     * Verify Two Factor Authentication
     *
     * @param string        $secret
     * @param int           $code
     *
     * @return array
     */
    public function verify2fa() {

        try {

            // verify the code
            $two2fa = new TwoFactorAuth(new QRServerProvider());

            // get the user
            $user = $this->usersModel->globalSearch(['twofactor_secret' => $this->payload['secret']]);

            // if the user is empty, return an error
            if(empty($user)) {
                // if the user_id is not provided, return an error
                if(empty($this->payload['user_id'])) {
                    return Routing::error("Sorry! An invalid secret was provided.");
                }
                // get the user using an alternative method
                $user = $this->usersModel->globalSearch(['id' => $this->payload['user_id']]);
                // if the user is still empty, return an error
                if(empty($user)) {
                    return Routing::error("Sorry! An invalid secret was provided.");
                }
                if(md5($user['twofactor_secret']) !== $this->payload['secret']) {
                    return Routing::error("Sorry! An invalid secret was provided.");
                }
            }

            // verify the code
            if (!$two2fa->verifyCode($user['twofactor_secret'], $this->payload['code'])) {
                return Routing::error("Sorry! 2FA setup could not be verified.");
            }

            // update the user two factor setup
            $this->usersModel->update($user['id'], ['two_factor_setup' => 1]);

            // if verifyOnly is true, generate the token
            if(!empty($this->payload['is_login'])) {
                $response['token'] = $this->generateTokenAuth($user);
            }

            // clear the cache if the token is provided
            if(!empty($this->payload['token'])) {
                $this->cacheObject->handle('auth', 'validateToken', ['token' => $this->payload['token']], 'delete');
            }

            return Routing::success("2FA setup verification was successful.", $response ?? []);
            
        } catch (\Exception $e) {
            return Routing::error($e->getMessage());
        }
    }

    /**
     * Disable Two Factor Authentication
     *
     * @return array
     */
    public function disable2fa()
    {

        try {

            // get the user id
            $userId = $this->currentUser['user_id'];

            // if the user is an admin, check if the user_id is provided
            if(is_admin($this->currentUser) && !empty($this->payload['user_id'])) {
                $userId = $this->payload['user_id'];
            }

            // update the user two factor setup
            $this->usersModel->update($userId, ['twofactor_secret' => '', 'two_factor_setup' => 0]);

            // clear the cache if the token is provided
            if(!empty($this->payload['token'])) {
                $this->cacheObject->handle('auth', 'validateToken', ['token' => $this->payload['token']], 'delete');
            }

            // clear the cache if the token is provided
            return Routing::success("Two Factor Authentication successfully deactivated.");

        } catch (\Exception $e) {
            return Routing::error($e->getMessage());
        }
    }

    /**
     * Generate a user token
     * 
     * @param array $user
     * @return string
     */
    private function generateTokenAuth($user, $description = '') {
        
        // hours to expire
        $hours = 2160;

        $rawToken = generateTokenAuth();
        $hashTokenAuth = hash(configs('algo'), $rawToken . configs('salt'));

        // Insert the user token hash
        $this->authModel->insertToken([
            'system_token' => 0,
            'login' => $user['username'],
            'password' => $hashTokenAuth,
            'date_created' => date('Y-m-d H:i:s'),
            'hash_algo' => configs('algo'),
            'description' => $description,
            'description' => 'This is a user generated token.',
            'date_expired' => date('Y-m-d H:i:s', strtotime("+{$hours} hours"))
        ]);

        return $rawToken;
        
    }

    /**
     * Forgotten password
     * 
     * @return array
     */
    public function forgotten() {

        // check if the email is valid
        if(!isValidEmail($this->payload['email'])) {
            return Routing::error('Sorry! You cannot use a disposable email address.');
        }

        // if the fingerprint is not empty, set the fingerprint
        $fingerprint = !empty($payload) ? $payload['fingerprint'] : $this->payload['fingerprint'];

        // set the login attempts counter
        $loginAttempts = 1;

        // get the login attempts counter
        $attemptKey = md5('forgotten_' . $fingerprint);
        $loginLogs = $this->cacheObject->dbObject->query("SELECT * FROM cache WHERE setup = 'forgotten.attempt' AND cache_key = '{$attemptKey}'")->getRowArray();


        // if the login attempts counter is empty, set it to 0
        if(!empty($loginLogs) && (int)$loginLogs['value'] >= configs('login_attempts')) {

            // get the time ago
            $agoTimer = daysDifference($loginLogs['server_time'], 'everything');

            // if the time ago is less than 1 hour, return an error
            if($agoTimer->i < 10) {
                return Routing::error('Too many login attempts. Please try again later.', [], 429);
            }

            if($agoTimer->i >= 10) {
                $this->cacheObject->dbObject->query("DELETE FROM cache WHERE cache_key = '{$attemptKey}' AND setup = 'forgotten.attempt'");
                $loginLogs = [];
            }
        }
        

        // Find user by email
        $getUser = $this->usersModel->findByEmail($this->payload['email']);
        if(empty($getUser)) {
            $this->loginAttempts($attemptKey, $loginLogs, $loginAttempts, 'forgotten.attempt');
            return Routing::success('Check your email for a link to reset your password.');
        }

        // delete the alt user record
        $this->usersModel->deleteAltUser(['email' => $this->payload['email']]);

        // generate the verification code
        $ver_code = random_string("nozero", 6);

        // Send email
        $utilsObject = new \App\Libraries\Emailing();
        $utilsObject->send($getUser['email'], [
            '__code__' => $ver_code, '__fullname__' => $getUser['full_name'],
            '__subject__' => 'Password Reset Request Confirmation'
        ], "verify.reset");

        // Insert the altuser record
        $this->usersModel->insertAltUser([
            'user_id' => $getUser['id'],
            'ver_code' => md5($ver_code),
            'email' => $getUser['email'],
            'pass' => "no",
            'username' => "no",
            'auth' => "password_reset",
            'request' => "reset"
        ]);

        return Routing::success('A 6 digits OTP have been sent to your email.');
    }

    /**
     * Logout the user
     * 
     * @return array
     */
    public function logout() {

        // check if the token is provided
        if(empty($this->payload['token'])) {
            return Routing::error('Logout failed. Token is required.');
        }

        // Hash the token
        $token = hash(configs('algo'), $this->payload['token'] . configs('salt'));

        // Delete the token
        $sql = sprintf("DELETE FROM %s WHERE password = ?", $this->authModel->userTokenAuthTable);
        $this->authModel->db->query($sql, [$token]);

        // clear the cache if the token is provided
        if(!empty($this->payload['token'])) {
            $this->cacheObject->handle('auth', 'validateToken', ['token' => $this->payload['token']], 'delete');
        }

        if(!empty($this->payload['webapp'])) {
            $session = session();
            $session->remove('user_id');
            $session->remove('user_loggedin');
            $session->remove('user_token');
            $session->remove('userLongitude');
            $session->destroy();
        }

        return Routing::success('Logout successful.');
    }

    /**
     * Validate token
     * 
     * @param string $token
     * @param string $route
     * 
     * @return array|object
     */
    public function validateToken($token, $route = '') {

        // get the cache
        $cacheData = empty($this->routingInfo['force_invalidate']) ? $this->cacheObject->handle('auth', 'validateToken', ['token' => $token]) : false;
        
        // if the cache data is empty, get the record
        if(empty($cacheData)) {

            // hash the token
            $hashTokenAuth = hashTokenAuth($token);

            // get the record
            $getRecord = $this->authModel->findRecordByToken($hashTokenAuth);
            if(empty($getRecord)) return false;

            // if the route is in the array, return the record
            if(in_array($route, ['auth/login'])) {
                return $getRecord;
            }

            // get the user record
            $getRecord = $this->usersModel->findByEmail($getRecord['login'], 'username');
            if(empty($getRecord)) {
                return false;
            }
            
            // if the user is not active, return an error
            if($getRecord['status'] !== 'active') {
                return false;
            }

            $getRecord['userId'] = $getRecord['user_id'];

        } else {
            $getRecord = $cacheData;
            // if the date_expired is empty, set it to the default expiry date
            if(empty($getRecord['date_expired'])) {
                $getRecord['date_expired'] = date('Y-m-d H:i:s', strtotime("+1 day"));
            }
        }

        $getRecord['isAdmin'] = $getRecord['user_type'] == 'admin';
        $getRecord['isModerator'] = $getRecord['user_type'] == 'moderator';

        // decode the statistics
        $getRecord['statistics'] = !empty($getRecord['statistics']) ? json_decode($getRecord['statistics'], true) : [];

        // set the current user
        $this->currentUser = $getRecord;

        // set the cache
        if(empty($cacheData) && !empty($getRecord)) {
            $this->cacheObject->accountId = $getRecord['user_id'];
            $this->cacheObject->handle('auth', 'validateToken', ['token' => $token], 'set', $getRecord);
        }

        return $getRecord;
    }

}
