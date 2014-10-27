<?php
//Generates stories of the home page using reddit's hotrank algorithm

session_start();
require_once('mysql.php');
require_once('functions.php');

//Reddit hotrank algorithm 
function getscore($s,$time)
{
$order = log10(max(abs($s),1));
if($s > 0)
{
    $sign = 1;
}
elseif($s < 0)
{
    $sign = -1;
}
else
{
    $sign = 0;
}

$seconds = $time - 1412433234;
$signxorder = $sign * $order;
$signxorderpseconds = $signxorder + $seconds;
$score =  round($signxorderpseconds / 45000 ,7);
return $score;
    
}

$query = $conn->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT 100");
$query->execute();

while ($row = $query->fetch(PDO::FETCH_ASSOC)) 
{
    $postid = $row['postid'];
    $userid = $row['user'];
    $title = $row['title'];
    $tagling = $row['tagline'];
    $link = $row['link'];
    $post_time = $row['post_time'];
    
    $total_votes = totalvotes($postid,$conn); 
    $hotrank = getscore($total_votes, $post_time);
    $isvoted = isuservoted($postid,$conn);
    
    $gu = $conn->prepare("SELECT * FROM users WHERE userid = :id LIMIT 1");
    $gu->execute(array(':id' => $userid));
    
    $gur = $gu->fetch(PDO::FETCH_ASSOC);
    $user_name = $gur['name'];
    $user_propic = $gur['propic'];
    
    $gcoms = totalcomments($postid,$conn);
    
    $allposts[] = ['datalink' => $postid,
                    'user_id' => $userid,
                   'upropic' => $user_propic,
                   'user' => $user_name,
                   'title' => $title,
                   'tag_line' => $tagling,
                   'post_url' => $link,
                   'votecount' => $total_votes,
                   'hot_rank' => $hotrank,
                   'isVoted' => $isvoted,
                   'post_time' => $post_time,
                   'num_comments' => $gcoms];
}

usort($allposts, function($a, $b) {
    return ($a['hot_rank'] < $b['hot_rank']) ? -1 : 1;
 //   return $a['hot_rank'] - $b['hot_rank'];
});

$rsl = array_reverse($allposts);
$sl = array_slice($rsl, 0, 20);

if(isset($_REQUEST['pre']))
{
  echo "<pre>";
  var_dump($sl);
  echo "</pre>";
}
else
{
print(json_encode($sl));
}
?>
