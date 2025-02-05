<?php
session_start();
require_once __DIR__ . '/../google_config.php';

// Redirect to login if access token is missing
if (!isset($_SESSION['access_token'])) {
    header("Location: index.php");
    exit();
}

// Set the access token to the client
$client->setAccessToken($_SESSION['access_token']);

// **Check if the token is expired**
if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    $_SESSION['access_token'] = $client->getAccessToken(); // Update session with new token
}

// Now you can safely make API calls
$oauth2 = new Google\Service\Oauth2($client);
$userInfo = $oauth2->userinfo->get();

// Display user info
echo "Hello, " . $userInfo->name;
echo "<br>Email: " . $userInfo->email;
echo "<br>USER ID : " . $userInfo->id;
echo "<br>Profile picture: <img src='" . $userInfo->picture . "'>";
?>
