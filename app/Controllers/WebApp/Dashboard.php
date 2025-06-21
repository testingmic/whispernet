<?php 

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Dashboard extends WebAppController {

    /**
     * Index for the dashboard
     * 
     * @return void
     */
    public function index() {

        // get the posts
        $posts = [[], [], [], [], [], []];

        return $this->templateObject->loadPage('feed', ['pageTitle' => 'Dashboard', 'posts' => $posts, 'favicon_color' => 'dashboard']);
    }

    /**
     * Install page
     * 
     * @return void
     */
    public function install() {
        return $this->templateObject->loadPage('install', ['pageTitle' => 'Install', 'noInstallation' => true, 'footerHidden' => true]);
    }

    /**
     * Privacy policy page
     * 
     * @return void
     */
    public function privacy() {
        return $this->templateObject->loadPage('privacy', ['pageTitle' => 'Privacy Policy', 'noInstallation' => true, 'footerHidden' => true]);
    }

    /**
     * Terms of service page
     * 
     * @return void
     */
    public function terms() {
        return $this->templateObject->loadPage('terms', ['pageTitle' => 'Terms of Service', 'noInstallation' => true, 'footerHidden' => true]);
    }

}