<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Notifications extends WebAppController
{
    public function index()
    {
        return $this->templateObject->loadPage('notifications', ['pageTitle' => 'Notifications']);
    }
}