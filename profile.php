<?php
session_start();

include('google_config.php');

// If the user is authenticated
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    // Get the user info
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    echo '<h3>Welcome, ' . $userInfo->name . '</h3>';
    echo '<p>Email: ' . $userInfo->email . '</p>';
    echo '<img src="' . $userInfo->picture . '" alt="Profile Picture"/>';
    echo $userInfo->id;

    var_dump($_SESSION['access_token']);

    echo '<a href="deleteacc.php">Deleted Account</a>';
} else {
    // If the user is not authenticated
    echo 'Please <a href="/OnlineBiodataSharingProject/">log in</a> first.';
}
?>
