<?php
//Gets comments from the database for each post

session_start();
require_once('mysql.php');
require_once('functions.php');

$id = strip_tags($_GET['postid']);

$query = $conn->prepare("SELECT * FROM comments WHERE post_id = :id");
$query->bindValue(':id', $id);
$query->execute();


while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $user_id = $row['user_id'];
    $post_id = $row['post_id'];
    $comment = $row['comment'];
    $comment_id = $row['commentid'];
    $ctime = $row['ctime'];
    
    $gu = $conn->prepare("SELECT * FROM users WHERE userid = :user LIMIT 1");
    $gu->bindValue(':user', $user_id);
    $gu->execute();
    $ru = $gu->fetchAll(PDO::FETCH_ASSOC);
    
    $user_name = $ru[0]['name'];
    $propic = $ru[0]['propic'];
    
    $commentz[] = [comment => $comment, 
                    commentid => $comment_id,
                    ctime => $ctime,
                    post_id => $post_id,
                    user_id => $user_id,
                    user_name => $user_name,
                    propic => $propic
                    ];
                    
}

$jcom = json_encode($commentz);
print $jcom;



?>
