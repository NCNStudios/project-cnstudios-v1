<?php
// Include configuration file
require_once 'config.php';

// Include User library file
require_once 'User.class.php';

if(isset($_GET['code'])){
    $gClient->authenticate($_GET['code']);
    $_SESSION['token'] = $gClient->getAccessToken();
    header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
}

if(isset($_SESSION['token'])){
    echo "Login Successed!";
}
else{
    echo "Login Failed!";
}
?>
