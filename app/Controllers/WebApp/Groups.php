<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Groups extends WebAppController
{
    public function index()
    {
        return $this->templateObject->loadPage('groups', ['pageTitle' => 'Groups']);
    }
}