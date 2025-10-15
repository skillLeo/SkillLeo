<?php

return [
    'nominatim' => [
        'email' => env('NOMINATIM_EMAIL', 'support@example.com'), // used in User-Agent
    ],
 
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI'),
    ],

    'github' => [
        'client_id'     => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect'      => env('GITHUB_REDIRECT_URI'),
    ],

'linkedin-openid' => [  
    'client_id'     => env('LINKEDIN_CLIENT_ID'),
    'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
    'redirect'      => env('LINKEDIN_REDIRECT_URI'),
    'scopes'        => ['openid','profile','email'],

],

'cv_ai' => [
    'base_url'    => env('CV_AI_BASE_URL', 'http://127.0.0.1:8000'),
    'upload_path' => env('CV_AI_UPLOAD_PATH', '/upload_resume/'),
    'timeout'     => (int) env('CV_AI_TIMEOUT', 120),
],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'driver' => env('SESSION_DRIVER', 'file'),
'domain' => env('SESSION_DOMAIN', null), // null works great for localhost
'same_site' => 'lax',                    // keeps cookies on OAuth redirects

'oauth_stateless' => env('OAUTH_STATELESS', false),

];
