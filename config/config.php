<?php
declare(strict_types=1);

return [
    'environment' => getenv('APP_ENV') ?: 'production',
    'url' => rtrim(getenv('APP_URL') ?: 'http://localhost', '/'),
    'db_host' => getenv('DB_HOST') ?: '127.0.0.1',
    'db_name' => getenv('DB_NAME') ?: 'linkforge',
    'db_user' => getenv('DB_USER') ?: 'linkforge',
    'db_password' => getenv('DB_PASSWORD') ?: '',
    'session_name' => 'linkforge_session',
];
