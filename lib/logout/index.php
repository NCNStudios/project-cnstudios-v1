<?php
// Include configuration file
require_once '../login/google-login/config.php';

// Remove token and user data from the session
unset($_SESSION['token']);
unset($_SESSION['userData']);

// Reset OAuth access token
$gClient->revokeToken();

// Destroy entire session data
session_destroy();

// Redirect to homepage
header("Location:http://localhost:8080/NienLuan2/CNStudios");
?>