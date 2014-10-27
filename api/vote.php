<?php
//Votes a story up or down accordingly

session_start();
require_once('mysql.php');
require_once('functions.php');

$post = strip_tags($_POST['post_id']);
$user = $_SESSION['user_id'];
$ustatus = $_SESSION['user_status'];

if($ustatus == true)
{
    
    $query = $conn->prepare("SELECT * FROM posts WHERE postid = :id LIMIT 1");
    $query->execute(array(':id' => $post));
    $result = $query -> fetch(PDO::FETCH_ASSOC);
    $user_id = $result['user'];
    
    if($user_id == $user)
    {
        echo "X";
    }
    elseif($user_id != $user)
    {
        $gp = $conn->prepare("SELECT * FROM votes WHERE post_id = :post AND user_id = :user LIMIT 1") or die();
        $gp->execute(array(':post' => $post , ':user' => $user));
        $result = $gp->rowCount();

        if($result > 0)
        {
            $dq = $conn->prepare("DELETE FROM votes WHERE post_id = :post AND user_id = :user");
            $dq->execute(array(':post'=> $post, ':user' => $user));
            
            $vc = $conn->prepare("SELECT * FROM votes WHERE post_id = :post");
            $vc->execute(array(':post' => $post));
            print $vc->rowCount();
        }
        else
        {
            $aq = $conn->prepare("INSERT INTO votes (post_id,user_id, op_id) VALUES (:post, :user, :op)");
            $aq->execute(array(':post' => $post, ':user' => $user, ':op' => $user_id));

            $vc = $conn->prepare("SELECT * FROM votes WHERE post_id = :post");
            $vc->execute(array(':post' => $post));
            print $vc->rowCount();
        }
    }
}
else
{
  echo "L";
}
?>
