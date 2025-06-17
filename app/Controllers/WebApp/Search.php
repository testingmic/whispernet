<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Search extends WebAppController
{
    public function index()
    {
        return $this->templateObject->loadPage('search', ['pageTitle' => 'Search']);
    }
}