<?php 

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Dashboard extends WebAppController {

    /**
     * Dashboard page
     * 
     * @return void
     */
    public function dashboard() {
        return $this->index();
    }
    
    /**
     * Index for the dashboard
     * 
     * @return void
     */
    public function index() {

        // get the posts
        $posts = [[], [], [], [], [], []];

        return $this->templateObject->loadPage('feed', ['pageTitle' => 'Dashboard', 'posts' => $posts, 'favicon_color' => 'dashboard', 'loadFeed' => true]);
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
     * Contact page
     * 
     * @return void
     */
    public function support() {
        return $this->templateObject->loadPage('contact', ['pageTitle' => 'Contact', 'noInstallation' => true, 'footerHidden' => true]);
    }

    /**
     * Terms of service page
     * 
     * @return void
     */
    public function terms() {
        return $this->templateObject->loadPage('terms', ['pageTitle' => 'Terms of Service', 'noInstallation' => true, 'footerHidden' => true]);
    }

    /**
     * Updates page
     * 
     * @return void
     */
    public function updates() {
        return $this->templateObject->loadPage('updates', ['pageTitle' => 'Updates', 'noInstallation' => true, 'footerHidden' => true]);
    }

    /**
     * Report page
     * 
     * @return void
     */
    public function report() {
        return $this->templateObject->loadPage('report', ['pageTitle' => 'Report', 'noInstallation' => true, 'footerHidden' => true]);
    }

    /**
     * Report page
     * 
     * @return void
     */
    public function feedback() {
        return $this->templateObject->loadPage('feedback', ['pageTitle' => 'Feedback', 'noInstallation' => true]);
    }
    
    /**
     * Report page
     * 
     * @return void
     */
    public function shared() {
        return $this->templateObject->loadPage('shared_post', ['pageTitle' => 'Shared', 'noInstallation' => true, 'footerHidden' => true]);
    }

}