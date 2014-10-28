<?php
//checks user staus, logged in user or not and generates user information accodringly

session_start();
require_once('twa.php');
require_once('config.php');
require_once('mysql.php');
require_once('functions.php');
$cookie_name = "info";
$user_status = $_SESSION["user_status"];


function userlogin($conn){
if($_SESSION['user_status'] !== true)
{
    if(!empty($cvalue)) {
        session_destroy();
        $user_status = false;
        $array = [user_status => $user_status];
        return json_encode($array);
   
    } 
    else 
    {
        $cookie_name = "info";
        $cvalue = $_COOKIE[$cookie_name];
    
        $query = $conn->prepare("SELECT * FROM users WHERE token = :token LIMIT 1");
        $query->bindValue(':token', $cvalue, PDO::PARAM_STR);
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if($rows)
        {
            $token = $rows[0]['twitter_token'];
            $secret = $rows[0]['twitter_secret'];
            $user_id = $rows[0]['userid'];

            
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token , $secret);
            $content = $connection->get('account/verify_credentials');
            
            $arrays = objectToArray($content);
            $userid = $arrays['id'];
            $name = $arrays['name'];
            $propic = $arrays['profile_image_url'];
            
            if($user_id==$userid)
            {
                
                $_SESSION['user_status'] = $user_status = true;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['myname'] = $name;
                $_SESSION['propic'] = $propic;
                
                $array = [
                user_id => $_SESSION['user_id'],
                user_status => $_SESSION['user_status'],
                name => $name,
                propic => $propic];
                
                $query = $conn->prepare("UPDATE users
                            SET name = :name
                            , propic = :propic WHERE userid= :usserid");
                
                $query->bindValue(':name', $name);
                $query->bindValue(':propic', $propic);
                $query->bindValue(':usserid', $userid);
                return json_encode($array);
            }   
            else
            {
                session_destroy();
                $user_status = false;
                $array = [user_status => $user_status];
                return json_encode($array);
                
            }
        }
        else
        {
            session_destroy();
            $user_status = false;
            $array = [user_status => $user_status];
            return json_encode($array);
        }
    }

}
elseif($_SESSION['user_status'] == true)
{
   
    $array = [
                'user_id' => $_SESSION['user_id'],
                'user_status' => $_SESSION['user_status'],
                'name' => $_SESSION['myname'],
                'propic' => $_SESSION['propic']
                    ];
    return json_encode($array);
}
}

function checkuser()
{
    if($_SESSION['user_status'] == true)
    {
        $array = [
                'user_id' => $_SESSION['user_id'],
                'user_status' => $_SESSION['user_status'],
                'name' => $_SESSION['myname'],
                'propic' => $_SESSION['propic']
                    ];
        return json_encode($array);
    }
    else
    {
        userlogin();
        $array = [
                'user_id' => $_SESSION['user_id'],
                'user_status' => $_SESSION['user_status'],
                'name' => $myname,
                'propic' => $propic
                    ];
        return json_encode($array);
    }

}

print(userlogin($conn));

?>
