<?php
session_start();
include('google_config.php');

// Check if the authorization code is available
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    // Check if token retrieval was successful
    if (isset($token['access_token'])) {
        $_SESSION['access_token'] = $token['access_token']; // Store the access token

        // Redirect to the logged-in page (or home page)
        header('Location: profile.php');
        exit();
    } else {
        // Redirect back to login page if token fetch failed
        header('Location: google-login.php?error=invalid_token');
        exit();
    }
} else {
    // If no code is received, redirect to the login page
    header('Location: google-login.php');
    exit();
}
?>
