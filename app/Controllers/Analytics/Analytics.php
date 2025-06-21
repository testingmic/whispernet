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
        try {
            $this->analyticsModel->logPageview($this->payload['page'], $this->payload['userUUID'], 0, $this->payload['user_agent']);
            return Routing::success('Pageview logged');
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }
}
?>