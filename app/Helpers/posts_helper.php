<?php
/**
 * Format posts
 * 
 * @param array $posts
 * @param bool $single
 * @return array
 */
function formatPosts($posts = [], $single = false, $userId = null) {
    $formattedPosts = [];

    if(!is_array($posts)) {
        $posts = [$posts];
    }

    if(empty($posts)) {
        return $single ? [] : [];
    }

    $key = 0;

    foreach($posts as $post) {

        if(!is_array($post)) {
            continue;
        }

        $username = strpos($post['username'], 'user') !== false ? configs('annon_name') : $post['username'];

        $formattedPosts[$key] = [
            'post_id' => $post['post_id'],
            'content' => linkifyContent(htmlspecialchars_decode($post['content'])),
            'created_at' => $post['created_at'],
            'updated_at' => $post['updated_at'],
            'user_id' => $post['user_id'],
            'city' => $post['city'],
            'hashtags' => extractHashtags($post['content']),
            'manage' => [
                'delete' => (bool)($post['user_id'] == $userId),
                'report' => (bool)($post['user_id'] !== $userId),
                'save' => !(bool)($post['is_bookmarked'] ?? false),
                'bookmarked' => (bool)($post['is_bookmarked'] ?? false),
                'voted' => $post['voted'] ?? false,
            ],
            // 'media_url' => $post['media_url'],
            // 'longitude' => $post['longitude'],
            // 'latitude' => $post['latitude'],
            'post_uuid' => $post['post_uuid'],
            'pageviews' => $post['pageviews'] ?? 0,
            'views' => $post['views'] ?? 0,
            // 'name' => empty($post['full_name']) ? $username : trim(explode(' ', $post['full_name'])[0]),
            'username' => $username,
            'ago' => formatTimeAgo($post['created_at']),
            'comments_count' => $post['comments_count'] ?? 0,
            'profile_image' => $post['profile_image'],
            'post_media' => !empty($post['post_media']) ? json_decode($post['post_media'], true) : [],
            'has_media' => !empty($post['post_media']) ? true : false,
            'score' => $post['score'] ?? 0,
            'distance' => round($post['distance'] ?? 0, 2),
            'upvotes' => $post['upvotes'],
            'downvotes' => $post['downvotes'],
        ];

        if(!empty($post['post_media'])) {
            $formattedPosts[$key]['media_types'] = array_keys($formattedPosts[$key]['post_media']);
        }

        $formattedPosts[$key]['comments'] = $post['comments'] ?? [];
        $key++;
    }

    return $single ? $formattedPosts[0] : $formattedPosts;
}

/**
 * Extract hashtags from content
 * 
 * @param string $comment
 * @return string|array
 */
function extractHashtags($comment = "", $itag = "#") {
    if(empty($comment)) return [];
    $escapedItag = preg_quote($itag, '/');
    preg_match_all("/{$escapedItag}([a-zA-Z0-9_]+)/", $comment, $matches);

    // return the lowercase hashtags
    return array_map('strtolower', $matches[1]); 
}

/**
 * Linkify content
 * 
 * @param string $content
 * @return string
 */
function linkifyContent($comment, $itag = "#") {

    // Escape the tag character for the regex
    $escapedItag = preg_quote($itag, '/');
    $label = $itag == "#" ? "posts/tags" : "users";

    // Apply link conversion
    return preg_replace_callback("/{$escapedItag}([a-zA-Z0-9_]+)/", function ($match) use ($itag, $label) {
        $value = $match[1];
        return "<a href=\"/{$label}/{$value}\" title=\"View posts related to {$value}\" class=\"text-blue-500 hover:text-purple-600 hashtag\">{$itag}{$value}</a>";
    }, $comment);

}

/**
 * Converts /chat/join/{string} to clickable links in a text.
 *
 * @param string $text The input text.
 * @return string The text with /chat/join links converted to anchor tags.
 */
function linkifyChatJoin($text) {
    return preg_replace_callback(
        '#(/chat/join/[^\s]+)#',
        function ($matches) {
            $url = htmlspecialchars($matches[1]);
            return '<a class="text-gray-700 hover:cursor-pointer dark:text-white hover:text-white hover:underline" href="' . $url . '">' . $url . '</a>';
        },
        $text
    );
}

/**
 * Format time ago
 * 
 * @param string $datetime
 * @return string
 */
function formatTimeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;

    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M j, Y', $time);
    }
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
        ],
        'Tema' => [
            'minLat' => 5.6,
            'maxLat' => 5.7,
            'minLng' => 0.0,
            'maxLng' => 0.05
        ],
        'Takoradi' => [
            'minLat' => 4.9,
            'maxLat' => 5.1,
            'minLng' => -1.8,
            'maxLng' => -1.6
        ],
        'Sunyani' => [
            'minLat' => 7.3,
            'maxLat' => 7.4,
            'minLng' => -2.4,
            'maxLng' => -2.3
        ],
        'Tamale' => [
            'minLat' => 9.3,
            'maxLat' => 9.5,
            'minLng' => -0.9,
            'maxLng' => -0.8
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
        'content' => $randomSentence
    ];
}