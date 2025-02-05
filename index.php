<?php
session_start();
include('google_config.php');

// Create login URL
$login_url = $client->createAuthUrl();

// Redirect to Google login if not authenticated
if (!isset($_SESSION['access_token'])) {
    header("Location: " . $login_url);
    exit();
}

// Redirect to profile page if already logged in
header("Location: profile.php");
exit();
?>
