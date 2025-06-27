<?php

namespace App\Controllers\Pages;

use App\Controllers\LoadController;
use App\Libraries\Routing;

class Pages extends LoadController {

    /**
     * List pages
     * 
     * @return array
     */
    public function list() {
        return Routing::success();
    }

    /**
     * Get app features
     * 
     * @return array
     */
    public function features() {
        return Routing::success(app_features());
    }

}