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

    public function users() {

        // verify if the user is logged in
        $this->verifyLogin();

        // get the users data
        return $this->templateObject->loadPage('admin/users', ['pageTitle' => 'Users']);
    }

    public function analytics() {

        // verify if the user is logged in
        $this->verifyLogin();

        // get the analytics data
        return $this->templateObject->loadPage('admin/analytics', ['pageTitle' => 'Analytics']);
    }

    public function feedback() {

        // verify if the user is logged in
        $this->verifyLogin();

        // get the feedback data
        return $this->templateObject->loadPage('admin/feedback', ['pageTitle' => 'Feedback Management']);
    }

}
?>