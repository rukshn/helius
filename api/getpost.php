<?php
//Get details of each post

session_start();
require_once('mysql.php');
require_once('functions.php');

$id = strip_tags($_GET['postid']);

$query = $conn->prepare("SELECT * FROM posts WHERE postid = :id LIMIT 1");
$query->execute(array(':id' => $id));
$row = $query->fetch(PDO::FETCH_ASSOC);

if( $row == true)
{
    $title = $row['title'];
    $tagline = $row['tagline'];
    $link = $row['link'];
    $time = $row['post_time'];
    $user = $row['user'];
    
    $query = $conn->prepare("SELECT * FROM users WHERE userid = :user LIMIT 1");
    $query->execute(array(':user' => $user));
    $getuser = $query->fetch(PDO::FETCH_ASSOC);
    
    $user_name = $getuser['name'];
    $pro_pic = $getuser['propic'];
    
    $isvoted = isuservoted($id,$conn);
    $total_votes = totalvotes($id,$conn);
    
    $post_status = [
        'title' => $title,
        'tagline' => $tagline,
        'isVoted' => $isvoted,
        'datalink' => $id,
        'user' => $user_name,
        'upropic' => $pro_pic,
        'votecount' => $total_votes,
        'post_date' => $time,
        'post_url' => $link
        
    ];
    $post_status = json_encode($post_status);
    print $post_status;
}
else
{
    print "nopost";
}
    
?>
