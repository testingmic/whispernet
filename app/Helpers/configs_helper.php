<?php

if (!function_exists('configs')) {
    /**
     * Get configuration value
     * 
     * @param string $key
     * @return mixed
     */
    function configs($key) {
        $configuration = [
            // get the base url
            'baseUrl' => getenv('baseURL'),

            // get the ipinfo token
            'ipinfo' => getenv('IPINFO'),
            'opencage' => getenv('OPENCAGE'),

            // get the vapid keys
            'vapid_private' => getenv('VAPID_PRIVATE'),
            'vapid_public' => getenv('VAPID_PUBLIC'),

            'sms_api_key' => getenv('MNOTIFY'),
            'sms_sender' => 'TalkLowKey',

            // get the firebase config
            'firebase_app_id' => getenv('FIREBASE_APP_ID'),
            'firebase_api_key' => getenv('FIREBASE_API_KEY'),
            'firebase_sender_id' => getenv('FIREBASE_SENDER_ID'),
            'firebase_auth_domain' => getenv('FIREBASE_AUTH_DOMAIN'),
            'firebase_storage_bucket' => getenv('FIREBASE_STORAGE_BUCKET'),
            'firebase_measurement_id' => getenv('FIREBASE_MEASUREMENT_ID'),
            'firebase_project_id' => getenv('FIREBASE_PROJECT_ID'),

            // get the database group
            'db_group' => config('Database')?->defaultGroup,
            'testing_mode' => config('General')?->testing_mode,
            'app_url' => getenv('APP_URL'),
            'is_local' => config('Database')?->defaultGroup == 'tests',
            'login_attempts' => 5,

            // get the security config
            'algo' => config('Security')?->algo,
            'salt' => config('Security')?->salt,

            // email config
            'email.port' => getenv('email.SMTP_PORT'),
            'email.host' => getenv('email.SMTP_HOST'),
            'email.user' => getenv('email.SMTP_USER'),
            'email.pass' => getenv('email.SMTP_PASSWORD'),
            'email.crypto' => getenv('email.SMTP_CRYPTO'),
        ];

        return $configuration[$key] ?? null;
    }
}

?>