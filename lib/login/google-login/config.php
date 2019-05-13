<?php
/*
 * Basic Site Settings and API Configuration
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'cnstudios');
define('DB_PASSWORD', 'Chinguyen5161#');
define('DB_NAME', 'cnstudios');
define('DB_USER_TBL', 'users');

// Google API configuration
define('GOOGLE_CLIENT_ID', '386099830332-djq0np2fmr94b3h9mtd4grnjucc70vmn.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'Aai7oQ9M5J_grNSPtQjue4fB');
define('GOOGLE_REDIRECT_URL', 'http://localhost:8080/NienLuan2/CNStudios/');

// Start session
if(!session_id()){
    session_start();
}

// Include Google API client library
require_once 'google-api-php-client/Google_Client.php';
require_once 'google-api-php-client/contrib/Google_Oauth2Service.php';

// Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('CNStudios');
$gClient->setClientId(GOOGLE_CLIENT_ID);
$gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
$gClient->setRedirectUri(GOOGLE_REDIRECT_URL);

$google_oauthV2 = new Google_Oauth2Service($gClient);