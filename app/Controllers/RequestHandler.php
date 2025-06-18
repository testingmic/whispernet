<?php

namespace App\Controllers;

use App\Libraries\Routing;
// use App\Models\UsersModel;
// use Stripe\StripeClient;
use App\Models\DbTables;

class RequestHandler extends BaseController
{

    public $parsedPayload;
    public $cleanPayload;

    /**
     * @var bool
     */
    public $routingInfo = [];

    /**
     * Validate the request.
     *
     * @param string $class
     * @param string $method
     * @param string $requestMethod
     * @param array  $payload
     * @param object $classObject
     * @param array  $splitPath
     * 
     * @return bool
     */
    public function validateRequest($class, $method, $requestMethod, $payload = [], $classObject = null, $splitPath = [])
    {
        // get the class name
        $validationClass = '\\App\\Libraries\\Validation\\'.ucfirst($class).'Validation';

        // if the class does not exist, return true
        if (!class_exists($validationClass)) {
            // set the parsed payload
            $this->parsedPayload = $payload;
            $this->cleanPayload = $payload;
            $this->payload = $payload;

            // set the status code to 200
            return true;
        }

        // get the routes
        $routes = (new $validationClass())->routes ?? [];

        // set the routing info
        $this->routingInfo = $routes;

        // if the method does not exist, return true
        if (!isset($routes[$method])) {
            $skip = true;

            $splitValues = [];
            foreach($splitPath as $key => $value) {
                if(!in_array($value, ['api', $class, $method])) {
                    $splitValues[] = $value;
                }
            }

            $endQuery = false;

            // loop through the routes
            foreach($routes as $ikey => $value) {
                // if the key contains the method, split the key and add the next step to the payload
                if(strpos($ikey, $method) !== false) {

                    // split the key
                    $split = explode(":", $ikey);

                    // if the request method is not in the method, continue
                    if(!in_array($requestMethod, explode(',', $value['method']))) {
                        continue;
                    }

                    $routesUrl = "{$method}:";
                    foreach($split as $key => $value) {
                        if($key == 0) continue;
                        $payload[$value] = $splitValues[$key - 1] ?? ($payload[$value] ?? 0);
                        $routesUrl .= "{$value}:";
                    }
                    $routesUrl = rtrim($routesUrl, ":");

                    if(isset($routes[$routesUrl])) {
                        $endQuery = true;
                        $routes[$method] = $routes[$routesUrl];
                        $routes[$method]['payload'] = $routes[$routesUrl]['payload'];
                        unset($routes[$routesUrl]);
                    }

                    if($endQuery) {
                        break;
                    }
                }
            }
        }

        // if the method does not exist, return an error
        if(!isset($routes[$method])) {
            $this->statusCode = 405;
            return Routing::error('The route you are trying to access is unavailable.');
        }

        // get the route method
        $routeMethod = explode(',', $routes[$method]['method']);
        foreach ($routeMethod as $acceptedMethod) {
            $routesList[] = trim($acceptedMethod);
        }
        // validate the request method
        if (!in_array(strtoupper($requestMethod), $routesList)) {
            $this->statusCode = 405;
            return Routing::error('The route you are trying to access is unavailable.');
        }

        // validate the payload
        if (isset($routes[$method]['payload'])) {
            // loop through the payload
            foreach ($routes[$method]['payload'] as $key => $value) {
                // if the key is not in the payload, check if it is required
                if (!in_array($key, array_keys($payload))) {
                    if (strpos($value, 'required') !== false) {
                        $result[] = 'The variable '.$key.' is required.';
                    }
                }
            }

            // set the accepted payload
            $classObject->acceptedPayload = $routes[$method]['payload'];

            // validate the data
            if (!$this->validateData($payload, $routes[$method]['payload'])) {
                $result = $this->validator->getErrors();
            }

            // set the payload set
            foreach($classObject->acceptedPayload as $key => $value) {
                if(in_array($key, array_keys($payload))) {
                    $classObject->submittedPayload[$key] = $payload[$key];
                }
            }

            // if the result is not empty, return the error
            if (!empty($result)) {
                $this->statusCode = 400;

                return [
                    'status' => 'error',
                    'data' => $result,
                ];
            }
        }

        // get the should authenticate
        $shouldAuthenticate = $routes[$method]['authenticate'] ?? false;
        $partialAuthenticate = $routes[$method]['partial_authenticate'] ?? false;

        // set the request method in the class object
        $classObject->requestMethod = $requestMethod;

        // if the route should authenticate and the token is not in the payload, return an error
        if ($shouldAuthenticate || $partialAuthenticate) {
            // set the status code to 401
            $this->statusCode = 401;

            // if the token auth is in the payload, set the token
            if (in_array('token_auth', array_keys($payload)) && !in_array('token', array_keys($payload))) {
                $payload['token'] = $payload['token_auth'];
            }

            // if the user data is not set, return an error
            if (!in_array('token', array_keys($payload)) || empty($payload['token'])) {
                Routing::$must_login = true;
                return Routing::unauthorized();
            }
            
            // if the force invalidate is set, set the payload
            if(isset($routes[$method]['force_invalidate']) && $routes[$method]['force_invalidate']) {
                $payload['force_invalidate'] = true;
            }
            
            // perform a validation of the token here
            $authModel = (new \App\Controllers\Auth\Auth());
            $authModel->payload = $payload;
            $authModel->cacheObject = $this->cacheObject;
            $userToken = $authModel->validateToken($payload['token'], "{$class}/{$method}");
            
            // if the token is invalid, return an error
            if (empty($userToken) && !$partialAuthenticate) {
                Routing::$must_login = true;
                return Routing::unauthorized('The token you provided is invalid.');
            }

            // set the user id in the payload
            $payload['userId'] = $userToken['userId'];
            
            // if the authenticate user is not 1, return an error
            $forceAuth = getenv('AUTHENTICATE_USER');
            
            // if the route requires admin and the user is not an admin, return an error
            if(!$partialAuthenticate) {
                foreach (['isAdmin', 'isSuperAdmin'] as $key) {
                    if (!empty($routes[$method][$key]) && !$userToken[$key] && $forceAuth) {
                        return Routing::denied('You do not have permission to access this resource.');
                    }
                }
            }

            // Forcefully set must login to false
            Routing::$must_login = false;

            // set the force auth
            $classObject->forceAuth = $forceAuth;

            // if the latitude and longitude are not set, get the location by IP
            if(empty($payload['latitude']) && empty($payload['longitude'])) {
                // get the session object
                $payload = $this->setLocationByIP($payload);
            } else {
                $payload = $this->setLocationByIP($payload);
            }

            // set the current user
            $classObject->currentUser = $userToken;
            $this->cacheObject->currentUser = $userToken;
                 
            // set the access groups if the logged in user is not an admin
            if (!empty($authModel->accessGroups) && !$userToken['isAdmin']) {
                $classObject->accessGroups = $authModel->accessGroups;
            }
        }

        $classObject->routingInfo = $this->routingInfo;

        // set the request container this will be used for logging and debugging
        $classObject->logContainer = [
            'className' => $class,
            'classMethod' => $method,
            'payload' => $payload,
            'uniqueId' => $this->uniqueId,
            'mainRawId' => $this->mainRawId,
            'requestMethod' => $requestMethod
        ];

        // set the parsed payload
        $this->parsedPayload = $payload;
        $this->cleanPayload = $payload;

        // set the status code to 200
        $this->statusCode = 200;
        
        // return true
        return true;
    }

    /**
     * Set the location by IP
     * 
     * @param array $payload
     * @return array
     */
    private function setLocationByIP($payload) {

        // get the cache key
        $cacheKey = create_cache_key('user', 'location', ['user_id' => $payload['userId']]);
        $locationInfo = $this->cacheObject->get($cacheKey);

        if(!empty($locationInfo)) {
            $location = $locationInfo;
        } else {
            // get the location by IP
            $location = getLocationByIP($payload['longitude'] ?? '', $payload['latitude'] ?? '');

            // handle the user location data
            if(isset($location['results'][0]['components'])) {
                $location['latitude'] = $location['results'][0]['geometry']['lat'] ?? ($payload['latitude'] ?? '');
                $location['longitude'] = $location['results'][0]['geometry']['lng'] ?? ($payload['longitude'] ?? '');
                $location['city'] = $location['results'][0]['components']['town'] ?? null;
                $location['country'] = $location['results'][0]['components']['country'] ?? null;
                $location['district'] = $location['results'][0]['components']['county'] ?? null;
            }

            // save the location to the cache for 5 minutes
            $this->cacheObject->save($cacheKey, $location, 'user.location', null, 60 * 20);
        }

        if(!empty($location['loc'])) {
            $longs = explode(',', $location['loc']);
        }

        $payload['city'] = $location['city'] ?? null;
        $payload['country'] = $location['country'] ?? null;
        $payload['latitude'] = $location['latitude'] ?? $longs[0];
        $payload['longitude'] = $location['longitude'] ?? $longs[1];

        return $payload;
    }

}
