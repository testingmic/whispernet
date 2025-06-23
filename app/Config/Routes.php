<?php

use CodeIgniter\Router\RouteCollection;

$routes->setAutoRoute(true);

/**
 * @var RouteCollection $routes
 */
$routes->get("/", "Landing::index");

// Handle 404 errors and pass URL segments to Landing::routing
$routes->set404Override(function() {
    $uri = service('uri');
    $segments = $uri->getSegments();
    return (new \App\Controllers\Landing())->routing($segments);
});

$routes->get("/dashboard/install", "WebApp\Dashboard::install");

// Dashboard routes
foreach(['report', 'privacy', 'terms', 'updates', 'install'] as $route) {
    $routes->get("/{$route}", "WebApp\Dashboard::{$route}");
}

// WebApp routes
foreach(['profile', 'chat', 'notifications'] as $route) {
    $iroute = ucwords($route);
    $routes->get("/{$route}", "WebApp\\{$iroute}::index");
}

$routes->get("/login", "Landing::load/login");
$routes->get("/signup", "Landing::load/signup");
$routes->get("/forgot-password", "Landing::load/forgot-password");


use App\Controllers\Api;

// request to api routing
$routes->get("/api", [Api::class, "index"]);

// request to api routing
$routes->match(["PUT", "DELETE", "GET", "POST", "OPTIONS"], "/api(:any)", [Api::class, "index/$1/$2/$3"]);

// crontab requests
$routes->cli('userjourney/(:segment)', 'Dashboard\Dashboard::journey/$1');

// Profile routes
$routes->get('profile', 'Profile::index');
$routes->get('profile/edit', 'Profile::edit');
$routes->post('profile/update', 'Profile::update');
$routes->post('profile/settings', 'Profile::updateSettings');