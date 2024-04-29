<?php

return [
    'app_name' => env('APP_NAME', 'Web Pulse'),
    'app_env' => env('APP_ENV', 'production'),
    'app_key' => env('APP_KEY'),
    'app_debug' => env('APP_DEBUG', false),
    'app_url' => env('APP_URL', 'http://localhost'),
    'client_url' => env('CLIENT_URL', 'http://localhost:3001'),

    'log_channel' => env('LOG_CHANNEL', 'daily'),
    'log_deprecations_channel' => env('LOG_DEPRECATIONS_CHANNEL', null),
    'log_level' => env('LOG_LEVEL', 'debug'),

    'db_connection' => env('DB_CONNECTION', 'mysql'),
    'db_host' => env('DB_HOST', '45.93.138.198'),
    'db_port' => env('DB_PORT', '3306'),
    'db_database' => env('DB_DATABASE', 'webpulsedev'),
    'db_username' => env('DB_USERNAME', 'webpulsedevuser'),
    'db_password' => env('DB_PASSWORD', '73W71z7fb.PBB0+_'),

    'broadcast_driver' => env('BROADCAST_DRIVER', 'log'),
    'cache_driver' => env('CACHE_DRIVER', 'file'),
    'filesystem_disk' => env('FILESYSTEM_DISK', 'public'),
    'queue_connection' => env('QUEUE_CONNECTION', 'sync'),
    'session_driver' => env('SESSION_DRIVER', 'file'),
    'session_lifetime' => env('SESSION_LIFETIME', 120),

    'memcached_host' => env('MEMCACHED_HOST', '127.0.0.1'),

    'redis_host' => env('REDIS_HOST', '127.0.0.1'),
    'redis_password' => env('REDIS_PASSWORD', null),
    'redis_port' => env('REDIS_PORT', 6379),

    'mail_mailer' => env('MAIL_MAILER', 'smtp'),
    'mail_host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
    'mail_port' => env('MAIL_PORT', 2525),
    'mail_username' => env('MAIL_USERNAME', '50cdcf37a0ec60'),
    'mail_password' => env('MAIL_PASSWORD', '1405cae640f58b'),
    'mail_encryption' => env('MAIL_ENCRYPTION', null),
    'mail_from_address' => env('MAIL_FROM_ADDRESS', 'info@web-pulse.cz'),
    'mail_from_name' => env('MAIL_FROM_NAME', 'Web Pulse'),

    'aws_access_key_id' => env('AWS_ACCESS_KEY_ID', null),
    'aws_secret_access_key' => env('AWS_SECRET_ACCESS_KEY', null),
    'aws_default_region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    'aws_bucket' => env('AWS_BUCKET', null),
    'aws_use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),

    'pusher_app_id' => env('PUSHER_APP_ID', null),
    'pusher_app_key' => env('PUSHER_APP_KEY', null),
    'pusher_app_secret' => env('PUSHER_APP_SECRET', null),
    'pusher_host' => env('PUSHER_HOST', null),
    'pusher_port' => env('PUSHER_PORT', 443),
    'pusher_scheme' => env('PUSHER_SCHEME', 'https'),
    'pusher_app_cluster' => env('PUSHER_APP_CLUSTER', null),

    'vite_app_name' => env('VITE_APP_NAME', 'web-pulse'),
    'vite_pusher_app_key' => env('VITE_PUSHER_APP_KEY', null),
    'vite_pusher_host' => env('VITE_PUSHER_HOST', null),
    'vite_pusher_port' => env('VITE_PUSHER_PORT', 443),
    'vite_pusher_scheme' => env('VITE_PUSHER_SCHEME', 'https'),
    'vite_pusher_app_cluster' => env('VITE_PUSHER_APP_CLUSTER', null),

    'docker_port' => env('DOCKER_PORT', 8000),

    'jwt_secret' => env('JWT_SECRET'),
    'file_max_size' => env('FILE_MAX_SIZE', 2),

    'google_translate_api_key' => env('GOOGLE_TRANSLATE_API_KEY', 'AIzaSyAhsaWMdzdn8ZSQUOIQx7LmTW8F_ccCaRI'),
];
