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