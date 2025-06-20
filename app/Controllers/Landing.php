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
        $uniqueId = null;

        // get the class name and method name
        $className = $path;
        if(is_array($path)) {
            $className = $path[0] ?? 'index';
            $classMethod = $path[1] ?? 'index';
            $uniqueId = $path[2] ?? null;
        }

        $baseClassName = $className;

        // get the class name
        $className = '\\App\\Controllers\\WebApp\\'.ucfirst($className);

        // if the class name is a setup page, return the template page
        if(in_array($baseClassName, ['login', 'signup', 'forgot-password', 'logout'])) {
            if($baseClassName == 'logout') {
                $this->templateObject->logout();
                $baseClassName = 'login';
            }
            return $this->templateObject->loadPage('setup/'.$baseClassName, [
                'pageTitle' => ucfirst($className), 
                'footerHidden' => true, 
                'logoutUser' => true, 
                'pageTitle' => ucwords($baseClassName),
                'userLoggedIn' => false
            ]);
        }

        // if the user is not logged in, return the login page
        if(!$this->user_loggedin()) {
            return $this->templateObject->loadPage('setup/login', ['pageTitle' => 'Account Login', 'footerHidden' => true, 'userLoggedIn' => false]);
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

        return $classObject->{$classMethod}($uniqueId, $params);

    }

    /**
     * Load a page
     * 
     * @param string $path
     * @param string $classMethod
     * @param array $params
     * @return string
     */
    public function load($path = null, $classMethod = null, $params = []) {
        return $this->routing($path, 'GET', []);
    }

}