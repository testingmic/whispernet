<?php

namespace App\Controllers;

use App\Controllers\WebAppController;

class Landing extends WebAppController
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

        $baseClassName = $className;

        // get the class name
        $className = '\\App\\Controllers\\WebApp\\'.ucfirst($className);

        // if the class name is a setup page, return the template page
        if(in_array($baseClassName, ['login', 'signup', 'forgot-password'])) {
            return $this->templateObject->loadPage('setup/'.$baseClassName, ['pageTitle' => ucfirst($className)]);
        }

        // if the user is not logged in, return the login page
        if(!user_loggedin()) {
            return $this->templateObject->loadPage('setup/login', ['pageTitle' => 'Account Login']);
        }

        // confirm if the class actually exists
        if (!class_exists($className)) {
            return $this->templateObject->load404Page();
        }

        // get the class object
        $classObject = new $className();

        // confirm if the method exists
        if (!method_exists($classObject, $classMethod)) {
            return $this->templateObject->load404Page();
        }

        return $classObject->{$classMethod}($params);

    }

}