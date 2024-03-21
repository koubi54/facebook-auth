<?php

include('fb_config.php');

$accessToken = $_SESSION['facebook_access_token'];

// Initialize Facebook API
$fb->setDefaultAccessToken($accessToken);

try {
    // Get user's pages
    $response = $fb->get('/me/accounts');
    $pages = $response->getGraphEdge()->asArray();
    // Print details of each page
    foreach ($pages as $page) {
        echo "Page ID: " . $page['id'] . "<br>";
        echo "Page Name: " . $page['name'] . "<br>";
        // Print other page details as needed
        echo "<hr>";
    }
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
}
?>
