<?php
/**
 * Manage the user location
 * 
 * @param array $payload
 * @param object $cacheObject
 * 
 * @return array
 */
function manageUserLocation($payload, $cacheObject) {
    

    // set the final location to an empty array
    $payload['finalLocation'] = [];

    if(empty($payload['userUUID'])) {
        return $payload;
    }

    if(!empty($payload['longitude']) && strlen($payload['longitude']) == 4) {
        $payload['longitude'] = '';
    }

    if(!empty($payload['latitude']) && strlen($payload['latitude']) == 4) {
        $payload['latitude'] = '';
    }

    $locationFound = false;

    // if the longitude and latitude are not set or if set and no location was found
    if((!empty($payload['longitude']) && !empty($payload['latitude']))) {

        // get the cache key
        $cacheKey = create_cache_key('user', 'location', ['latitude' => $payload['latitude'], 'longitude' => $payload['longitude']]);
        $locationInfo = $cacheObject->get($cacheKey);

        // get the data to use
        $dataToUse = !empty($locationInfo) ? $locationInfo : getLocationByIP($payload['longitude'], $payload['latitude']);
        
        $theCity = $dataToUse['results'][0]['components']['town'] ?? ($dataToUse['results'][0]['components']['city'] ?? (
            $dataToUse['results'][0]['components']['suburb'] ?? null
        ));
       
        // handle the user location data
        if(!empty($theCity)) {
            $usage = 'location';
            $payload['city'] = $theCity;
            $payload['country'] = $dataToUse['results'][0]['components']['country'] ?? null;
            $payload['district'] = $dataToUse['results'][0]['components']['county'] ?? null;

            // save the cache value
            $cacheObject->save($cacheKey, $dataToUse, 'user.location', null, 60 * 60);

            // set the location found to true
            $locationFound = true;
        }
    }

    if(empty($payload['longitude']) && empty($payload['latitude']) || !$locationFound) {

        // get the location cache values
        $cacheKey = create_cache_key('user', 'location', ['user_id' => $payload['userUUID'].getUserIpaddress()]);
        $locationInfo = $cacheObject->get($cacheKey);

        // get the data to use
        $dataToUse = !empty($locationInfo) ? $locationInfo : getLocationByIP();

        if(!empty($dataToUse)) {
            $usage = 'ipaddress';
            $locs = explode(',', $dataToUse['loc']);
            $payload['latitude'] = $locs[0];
            $payload['longitude'] = $locs[1];
            $payload['city'] = $dataToUse['city'];
            $payload['country'] = $dataToUse['country'];
            $payload['district'] = $dataToUse['region'];
            $cacheObject->save($cacheKey, $dataToUse, 'user.location', null, 60 * 60);
        }

    }

    $final = [
        'mode' => $usage,
        'city' => $payload['city'] ?? '',
        'district' => $payload['district'] ?? '',
        'country' => $payload['country'] ?? '',
        'latitude' => $payload['latitude'] ?? '',
        'longitude' => $payload['longitude'] ?? '',
    ];

    $payload['finalLocation'] = $final;

    return $payload;
}

/**
 * Get the location by IP
 * 
 * @return array
 */
function getLocationByIP($longitude = null, $latitude = null) {

    // get the user ip address
    $userIpaddress = $_SERVER['REMOTE_ADDR'] ?? '';
    if(empty($userIpaddress) || strlen($userIpaddress) < 6) {
        $userIpaddress = '';
    }

    // get the ipinfo and opencage keys
    $ipInfoKey = explode(';', configs('ipinfo'));
    $opencageKey = explode(';', configs('opencage'));

    // Fetch location data from ipapi.co
    $url = "https://ipinfo.io/{$userIpaddress}?token=" . trim($ipInfoKey[0]);
    $reverseUrl = "https://api.opencagedata.com/geocode/v1/json?q={$latitude},{$longitude}&pretty=1&key=" . trim($opencageKey[0]);

    // set the url path
    $urlPath = empty($longitude) && empty($latitude) ? $url : $reverseUrl;

    // use curl to get the data
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlPath);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response !== false) {
        $data = json_decode($response, true);
        $data['api_url'] = $urlPath;
        $data['ip'] = !empty($userIpaddress) ? $userIpaddress : $_SERVER['HTTP_HOST'];
        return $data;
    }
}
?>