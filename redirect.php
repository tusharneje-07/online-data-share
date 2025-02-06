<?php
session_start();
require_once 'google_config.php';

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (isset($token['error'])) {
        echo "Error: " . $token['error'];
        exit();
    }

    $_SESSION['access_token'] = $token;
    
    // Store refresh token separately (if available)
    if (isset($token['refresh_token'])) {
        $_SESSION['refresh_token'] = $token['refresh_token'];
    }

    header('Location: user/profile');
    exit();
}
?>
