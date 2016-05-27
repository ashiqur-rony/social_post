<?php
/**
 * Handles the Facebook login callback
 *
 * @author Ashiqur Rahman
 * @url http://goodkoding.com
 * @author_url http://ghumkumar.com
 **/
session_start();
include "libraries/Facebook/autoload.php";

$fb = new Facebook\Facebook([
    'app_id' => 'APP_ID',
    'app_secret' => 'SECRET',
    'default_graph_version' => 'v2.5',
]);

$helper = $fb->getRedirectLoginHelper();
try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (isset($accessToken)) {
    // Logged in!
    $_SESSION['facebook_access_token'] = (string) $accessToken;
}
header("Location: index.php");
exit;