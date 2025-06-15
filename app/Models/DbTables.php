<?php 
namespace App\Models;

class DbTables {

    public $payload = [];
    public static $userTable = 'users';
    public static $accessTable = 'access';
    public static $testimonialsTable = 'testimonials';
    public static $webhookTable = 'webhooks';
    public static $userTokenAuthTable = 'user_token_auth';

    /**
     * Initialize the tables
     * 
     * @return array
     */
    public static function initTables() {
        return [
            'userTable', 'accessTable', 'testimonialsTable', 'webhookTable', 'userTokenAuthTable'
        ];
    }
}
