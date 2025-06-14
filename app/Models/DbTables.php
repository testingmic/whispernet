<?php 
namespace App\Models;

class DbTables {

    public static $userTable = 'users';
    public static $accessTable = 'access';
    public static $testimonialsTable = 'testimonials';
    public static $webhookTable = 'webhooks';

    /**
     * Initialize the tables
     * 
     * @return array
     */
    public static function initTables() {
        return [
            'userTable', 'accessTable', 'testimonialsTable', 'webhookTable'
        ];
    }
}
