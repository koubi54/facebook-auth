<?php

include('fb_config.php');

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email','pages_read_engagement']; // optional permissions

try {
    if (isset($_SESSION['facebook_access_token'])) {
        $accessToken = $_SESSION['facebook_access_token'];
    } else {
        $accessToken = $helper->getAccessToken();
    }
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
    if (isset($_SESSION['facebook_access_token'])) {
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    } else {
        // getting short-lived access token
        $_SESSION['facebook_access_token'] = (string) $accessToken;

        // OAuth 2.0 client handler
        $oAuth2Client = $fb->getOAuth2Client();

        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

        // setting default access token to be used in script
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }

    try {
        // Get the Facebook user details
        $response = $fb->get('/me?fields=id,name,email');
        $userNode = $response->getGraphUser();
        $userId = $userNode->getId();
        $userName = $userNode->getName();
        $userEmail = $userNode->getEmail();

        // Now you can do whatever you want with this user data
        // For example, you can store it in your database or start a session for the user
        // and redirect to another page
        // For simplicity, I'm just printing the data here
        echo 'Logged in as ' . $userName . '<br>';
        echo 'User ID: ' . $userId . '<br>';
        echo 'Email: ' . $userEmail . '<br>';
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        session_destroy();
        // redirect back to the login page
        header("Location: ./");
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
} else {
    $loginUrl = $helper->getLoginUrl('http://localhost/leads/', $permissions); // Change this to your callback URL
    echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
}
?>
