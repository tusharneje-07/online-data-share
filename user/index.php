<?php
session_start();
require_once __DIR__ . '/../google_config.php';

// Redirect to Google login if not authenticated
if (!isset($_SESSION['access_token'])) {
    header("Location: " . 'login');
    exit();
}

// Redirect to profile page if already logged in
header("Location: profile.php");
exit();
?>
