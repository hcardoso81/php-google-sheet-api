<?php
require_once 'vendor/autoload.php';
require_once 'class-db.php';

define('GOOGLE_CLIENT_ID', '');
define('GOOGLE_CLIENT_SECRET', '');

$config = [
    'callback' => 'http://localhost/google-sheets-api/callback.php',
    'keys'     => [
        'id' => GOOGLE_CLIENT_ID,
        'secret' => GOOGLE_CLIENT_SECRET
    ],
    'scope'    => 'https://www.googleapis.com/auth/spreadsheets',
    'authorize_url_parameters' => [
        'approval_prompt' => 'force', // to pass only when you need to acquire a new refresh token.
        'access_type' => 'offline'
    ]
];

$adapter = new Hybridauth\Provider\Google($config);
