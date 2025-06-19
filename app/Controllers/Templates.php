<?php

namespace App\Controllers;

class Templates extends BaseController
{
    /**
     * Check if the user is logged in
     * 
     * @return bool
     */
    public function user_loggedin() {
        $session = session();
        if(empty($session->get('user_id')) && empty($session->get('user_loggedin'))) {
            return false;
        }
        return true;
    }

    /**
     * Logout the user
     * 
     * @return void
     */
    public function logout() {
        $session = session();
        $session->remove('user_id');
        $session->remove('user_loggedin');
        $session->remove('user_token');
        $session->remove('userLongitude');
        $session->destroy();
    }

    /**
     * Global variables for the templates
     * 
     * @return array
     */
    public function globalVariables() {
        $urlPath = rtrim(getenv('baseURL'), '/');
        $socketUrl = "ws://localhost:3000";

        // Remove the trailing slash if it's not localhost
        if(strpos($urlPath, 'localhost') == false) {
            $urlPath = rtrim(str_replace('public', '', $urlPath), '/');
            $socketUrl = "wss://whispernet-socket.onrender.com:3000";
        }

        return [
            'baseUrl' => $urlPath,
            'version' => '1.1.17',
            'websocketUrl' => $socketUrl,
            'userData' => session()->get('userData'),
            'userLoggedIn' => $this->user_loggedin(),
            'userToken' => session()->get('user_token'),
            'appName' => 'WhisperChat',
        ];
    }

    /**
     * Load the header template
     * 
     * @return void
     */
    public function loadHeader($data = []) {
        return view('templates/header', $data);
    }

    /**
     * Load the footer template
     * 
     * @return void
     */
    public function loadFooter($data = []) {
        return view('templates/footer', $data);
    }

    /**
     * Load a page
     * 
     * @param string $page
     * @param array $data
     * @return void
     */
    public function loadPage($page, $data = []) {
        // merge the global variables with the data
        $data = array_merge($this->globalVariables(), $data);

        // print the files contents
        echo $this->loadHeader($data);
        echo view($page, $data);
        echo $this->loadFooter($data);
    }

    /**
     * Load the 404 page
     * 
     * @return void
     */
    public function load404Page() {
        $data['topMargin'] = 0;

        // merge the global variables with the data
        $data = array_merge($this->globalVariables(), $data);

        // print the files contents
        echo $this->loadHeader($data);
        echo view('errors/404', $data);
        echo $this->loadFooter($data);
    }
}