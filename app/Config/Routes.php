<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Api;

$routes->setAutoRoute(true);

// Handle 404 errors and pass URL segments to Landing::routing
$routes->set404Override(function() {
    $uri = service('uri');
    $segments = $uri->getSegments();
    return (new \App\Controllers\Landing())->routing($segments);
});

/**
 * @var RouteCollection $routes
 */
$routes->get("/", "Landing::index");

// Dashboard routes
$routes->get("/dashboard/install", "WebApp\Dashboard::install");
$routes->get("/shared/posts/(:segment)/(:segment)", "WebApp\Shared::posts/$1/$2");

// add the remove account route
$routes->get("/profile/remove", "WebApp\Profile::remove");

// Dashboard routes
foreach(['dashboard', 'report', 'privacy', 'terms', 'updates', 'install', 'support', 'shared', 'feedback'] as $route) {
    $routes->get("/{$route}", "WebApp\Dashboard::{$route}");
}

// Admin routes
foreach(['reports', 'users', 'analytics', 'feedback'] as $route) {
    $routes->get("/admin/{$route}", "WebApp\Admin::{$route}");
}

// Posts tags routes
$routes->get("/posts/tags", "WebApp\Posts::tags");

// Posts routes
foreach(['posts', 'view'] as $pitem) {
    $routes->get("/posts/{$pitem}/(:segment)", "WebApp\Posts::view/$1");
}

// Chat routes
$routes->get("/chat/join/(:any)", "WebApp\Chat::join/$1/$2/$3");

// WebApp routes
foreach(['profile', 'chat', 'notifications'] as $route) {
    $iroute = ucwords($route);
    $routes->get("/{$route}", "WebApp\\{$iroute}::index");
}

// Landing routes
foreach(['login', 'signup', 'forgot-password'] as $route) {
    $routes->get("/{$route}", "Landing::load/{$route}");
}

// Profile routes
$routes->get('profile', 'Profile::index');

// Profile routes
foreach(['edit', 'update', 'settings'] as $pitem) {
    $routes->get("profile/{$pitem}", "Profile::{$pitem}");
}

// request to api routing
$routes->get("/api", [Api::class, "index"]);

// request to api routing
$routes->match(["PUT", "DELETE", "GET", "POST", "OPTIONS"], "/api(:any)", [Api::class, "index/$1/$2/$3"]);