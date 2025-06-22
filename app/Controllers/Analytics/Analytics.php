<?php
namespace App\Controllers\Analytics;

use App\Controllers\LoadController;
use App\Libraries\Routing;

class Analytics extends LoadController {
    
    /**
     * Log pageview
     * 
     * @return void
     */
    public function pageview() {
        // get the payload
        $page = $this->payload['page'] ?? '';
        $userUUID = $this->payload['userUUID'] ?? '';
        $userID = $this->payload['user_id'] ?? 0;
        $userAgent = $this->payload['user_agent'] ?? '';
        $referer = $this->payload['referer'] ?? '';

        if(!empty($userAgent)) {
            foreach(['facebook', 'snapchat', 'instagram', 'tiktok'] as $platform) {
                if(strpos(strtolower($userAgent), $platform) !== false) {
                    $referer = 'https://www.'.$platform.'.com';
                }
            }
        }

        if(empty($userUUID)) return Routing::success('Required userUUID missing from payload.');

        $this->analyticsModel->logPageview($page, $userUUID, $userID, $userAgent, $referer);

        return Routing::success('Pageview logged');
    }
    
}
?>