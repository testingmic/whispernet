<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Admin extends WebAppController {

    public function reports() {

        // verify if the user is logged in
        $this->verifyLogin();

        // get the reports data
        return $this->templateObject->loadPage('admin/reports', ['pageTitle' => 'Reports']);
    }

    public function moderation() {

        // verify if the user is logged in
        $this->verifyLogin();

        // get the moderation data
        return $this->templateObject->loadPage('admin/moderation', ['pageTitle' => 'Moderation']);
    }

}
?>