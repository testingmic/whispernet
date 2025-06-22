<?php


namespace App\Controllers\Notifications;

use App\Controllers\LoadController;
use App\Libraries\Routing;

class Notifications extends LoadController {

    public function recent() {
        
        // update the last activity time of the user
        $this->usersModel->update($this->currentUser['user_id'], ['last_login' => date('Y-m-d H:i:s')]);

        return Routing::success([]);

    }
    
}