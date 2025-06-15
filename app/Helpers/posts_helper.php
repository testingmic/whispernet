<?php

function formatPosts($posts = [], $single = false) {
    $formattedPosts = [];

    if(!is_array($posts)) {
        $posts = [$posts];
    }

    if(empty($posts)) {
        return $single ? [] : [];
    }

    foreach($posts as $post) {
        $formattedPosts[] = [
            'post_id' => $post['post_id'],
            'content' => $post['content'],
            'created_at' => $post['created_at'],
            'updated_at' => $post['updated_at'],
            'user_id' => $post['user_id'],
            'city' => $post['city'],
            'media_url' => $post['media_url'],
            'media_type' => $post['media_type'],
            'longitude' => $post['longitude'],
            'latitude' => $post['latitude'],
            'username' => $post['username'],
            'profile_image' => $post['profile_image'],
            'score' => $post['score'] ?? 0,
            'distance' => $post['distance'] ?? 0,
            'upvotes' => $post['upvotes'],
            'downvotes' => $post['downvotes'],
        ];
    }

    return $single ? $formattedPosts[0] : $formattedPosts;
}

/**
 * Generate random location and sentence
 * 
 * @return array
 */
function generateRandomLocationAndSentence() {
    // Define city bounding boxes
    $cities = [
        'Accra' => [
            'minLat' => 5.5,
            'maxLat' => 5.7,
            'minLng' => -0.3,
            'maxLng' => 0.3
        ],
        'Kumasi' => [
            'minLat' => 6.6,
            'maxLat' => 6.8,
            'minLng' => -1.8,
            'maxLng' => -1.5
        ]
    ];

    // Randomly pick a city
    $cityName = array_rand($cities);
    $city = $cities[$cityName];

    // Generate coordinates within the selected city's bounds
    $latitude = round($city['minLat'] + mt_rand() / mt_getrandmax() * ($city['maxLat'] - $city['minLat']), 6);
    $longitude = round($city['minLng'] + mt_rand() / mt_getrandmax() * ($city['maxLng'] - $city['minLng']), 6);

    // Sample sentences
    $sentences = [
        "The weather is great today in Ghana.",
        "Exploring new places is always exciting.",
        "I just had the best jollof rice in town!",
        "There's something magical about this city.",
        "Walking through the streets feels like home.",
        "This location has a lot of cultural charm.",
        "The market was bustling with life this morning.",
        "Can't wait to visit again soon!",
        "The people here are incredibly welcoming.",
        "So much history and beauty in one place."
    ];
    $randomSentence = $sentences[array_rand($sentences)];

    return [
        'latitude' => $latitude,
        'longitude' => $longitude,
        'city' => $cityName,
        'sentence' => $randomSentence
    ];
}