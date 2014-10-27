<?php
/**
* @file
* Take the user when they return from Twitter. Get access tokens.
* Verify credentials and redirect to based on response from Twitter.
*/
/* Start session and load lib */
session_start();
require_once('twa.php');
require_once('config.php');
require_once('mysql.php');
require_once('functions.php');

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
$_SESSION['oauth_status'] = 'oldtoken';
header('Location: ./clearsessions.php');
}
/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
$content = $connection->get('account/verify_credentials');
/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;
/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);
/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
/* The user has been verified and the access tokens can be saved for future use */
$_SESSION['status'] = 'verified';

$arrays = objectToArray($content);

$user_id = $arrays['id'];
$name = $arrays['name'];
$propic = $arrays['profile_image_url'];

$token = sha1(generateRandomString(10).$userid);
$twitter_token = $access_token["oauth_token"];
$twitter_secret = $access_token["oauth_token_secret"];

//check if the user exists
$query = $conn->prepare("SELECT * FROM users WHERE userid = :userid LIMIT 1");
$query->bindValue(':userid', $user_id);
$query->execute();

$row = $query->fetchAll(PDO::FETCH_ASSOC);
    
if($row==true)
{
    $_SESSION['user_id'] = $user_id;
    $_SESSION['propic'] = $propic;
    $_SESSION['myname'] = $name;
    $_SESSION['user_status'] = true;
    
    $query = $conn->prepare("UPDATE users
        SET token = :token
        , propic = :propic WHERE userid = :userid");
    $query->bindValue(':token', $token);
    $query->bindValue(':propic', $propic);
    $query->bindValue(':userid', $user_id);
    
    $query->execute();
    
        $cookie_name = 'info';
        $cookie_value = $token;
        setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/'); // 86400 = 1 day
           header('Location: /');

}
else
{
    $_SESSION['user_id'] = $user_id;
    $_SESSION['propic'] = $propic;
    $_SESSION['myname'] = $name;
    $_SESSION['user_status'] = true;
    
   $query = $conn->prepare("INSERT into users (name,userid,propic,token,twitter_token,twitter_secret) VALUES (:name, :userid, :propic, :token, :twtoken, :twsecret)");

    $query->execute(array(':name' => $name, ':userid' => $user_id, ':propic' => $propic, ':token' => $token, ':twtoken' => $twitter_token, ':twsecret' => $twitter_secret));
    
    $cookie_name = 'info';
    $cookie_value = $token;
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/'); // 86400 = 1 day
   
   header('Location: /');
}


} else {
/* Save HTTP status for error dialog on connnect page.*/
header('Location: ./clearsessions.php');
}
