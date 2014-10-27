<?php
//create new story

session_start();
require_once('mysql.php');
require_once('functions.php');



function postex($conn)
{
    $query = $conn->prepare("SELECT 1 FROM posts WHERE postid = :rand");
    do {
    $random_string = generateRandomString(5);
    $query->bindValue(':rand', $random_string);
    $query->execute();
} while
(
        $query->rowCount() > 0
        
);
    return $random_string;
}

if($_SESSION['user_status']== true)
{
    $url = strip_tags($_POST['purl']);
    $title = strip_tags($_POST['ptitle']);
    $tagling = strip_tags($_POST['ptagline']);
    
    $query = $conn->prepare("SELECT postid FROM posts WHERE link = :url LIMIT 1");
    $query->execute(array(':url' => $url));
    $row = $query->fetchAll(PDO::FETCH_ASSOC);

    if($row == true)
    {   
      print $row[0]['postid'];
    }
    else
    {
        $randid = postex($conn);
        $query = $conn->prepare("INSERT INTO posts (postid,title,tagline,link,user,post_time)
        VALUES (:raid, :title, :tagline, :url, :userid, :timest)");
        $query->execute(array(':raid' => $randid, ':title' => $title, ':tagline' => $tagling, ':url' => $url, ':userid' => $_SESSION['user_id'], ':timest' => time()));
        
        $query = $conn->prepare("INSERT INTO votes (post_id,user_id, op_id) VALUES (:post, :user, :opid)");
        $query->execute(array(':post'=>$randid, ':user' => $_SESSION['user_id'], ':opid' => $_SESSION['user_id']));
        print $randid;
        
    }
}
else
{
    echo "User out";
}
?>
