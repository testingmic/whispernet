<?php


namespace App\Controllers\Notifications;

use App\Controllers\LoadController;
use App\Libraries\Routing;

class Notifications extends LoadController {

    public function recent() {
        
        return Routing::success([]);

    }
    
}