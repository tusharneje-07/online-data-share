<?php
// Start session
session_start();
include('google_config.php');

$_SESSION['access_token'] = '';

if ($client->revokeToken()) {
    echo "Google account logged out successfully.";
} else {
    echo "Error revoking Google token.";
}

session_unset();
session_destroy();

header('Location: /OnlineBioDataSharingProject/');
exit();
?>
