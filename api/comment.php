<?php
session_start();
require_once('mysql.php');
require_once('functions.php');

$post = strip_tags($_POST['postid']);
$comment = strip_tags($_POST['comment']);
$time = time();

function postex($conn)
{
    $query = $conn->prepare("SELECT 1 FROM comments WHERE commentid = :rand");
    do {
    $random_string = generateRandomString(5);
    $query->bindValue(':rand', $random_string);
    $query->execute();
}
while
(
        $query->rowCount() > 0
        
);
    return $random_string;
}


if($_SESSION['user_status']==true)
{
    $user = $_SESSION['user_id'];
    $comment_id = postex($conn);
    
    $query = $conn->prepare("INSERT INTO comments (comment, post_id, user_id, commentid, ctime) VALUES (:comment, :post, :user, :commentid, :time)");
    $query->execute(array(':comment' => $comment, ':time' => $time, ':user' => $user, ':post' => $post, ":commentid" => $comment_id));
    echo $comment_id;
}
else
{
    echo "nouser";
}


?>
