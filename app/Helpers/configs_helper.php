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
            'baseUrl' => 'http://localhost:8085/public',
            
            'db_group' => config('Database')?->defaultGroup,
            'testing_mode' => config('General')?->testing_mode,
            'membership' => getenv('MEMBERSHIP_URL'),
            'app_url' => getenv('APP_URL'),
            'is_local' => config('Database')?->defaultGroup == 'tests',
            'login_attempts' => 5,

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