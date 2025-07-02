<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Admin extends WebAppController {

    public function reports() {
        return $this->templateObject->loadPage('admin/reports', ['pageTitle' => 'Reports']);
    }

    public function moderation() {
        return $this->templateObject->loadPage('admin/moderation', ['pageTitle' => 'Moderation']);
    }

}
?>