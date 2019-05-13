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
    $gClient->setAccessToken($_SESSION['token']);
}

if($gClient->getAccessToken()){
    header('Location: ../../../lib/logout');
}else{
    $authUrl = $gClient->createAuthUrl();
    
    header('Location: ' .filter_var($authUrl, FILTER_SANITIZE_URL));
}
?>

<div class="container">
    <?php echo $output; ?>
</div>