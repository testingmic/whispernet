<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Templates;

class Landing extends BaseController
{
    /**
     * Index for the landing page
     * 
     * @return string
     */
    public function index()
    {
        return $this->routing(['dashboard', 'index'], 'GET', []);
    }

    public function globalVariables() {

    }

    /**
     * Routing for the landing page
     * 
     * @param string $path
    * @param string $method
     * @param array $params
     * @return string
     */
    public function routing($path = null, $classMethod = null, $params = [])
    {

        // get the params
        $params = $params + $_GET;

        // get the class name and method name
        $className = $path;
        if(is_array($path)) {
            $className = $path[0];
            $classMethod = $path[1] ?? 'index';
        }

        // get the class name
        $className = '\\App\\Controllers\\WebApp\\'.ucfirst($className);

        // confirm if the class actually exists
        if (!class_exists($className)) {
            return (new Templates())->load404Page();
        }

        return view('feed');
    }
}