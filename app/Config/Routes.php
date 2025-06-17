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

use App\Controllers\Api;

// request to api routing
$routes->get("/api", [Api::class, "index"]);

// request to api routing
$routes->match(["PUT", "DELETE", "GET", "POST", "OPTIONS"], "/api(:any)", [Api::class, "index/$1/$2/$3"]);

// crontab requests
$routes->cli('userjourney/(:segment)', 'Dashboard\Dashboard::journey/$1');