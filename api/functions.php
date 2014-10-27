<?php


function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}
	
function isuservoted($id,$conn)
{
    $cuser = $_SESSION['user_id'];
    if(isset($cuser))
    {
        $query = $conn->prepare("SELECT * FROM votes WHERE user_id = :user AND post_id = :id LIMIT 1");
        $query->bindValue(':user', $cuser);
        $query->bindValue(':id', $id);
        $query->execute();
        $getcuser = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if($getcuser == true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }

}

function totalvotes($id,$conn)
{
    $getvotes = $conn->prepare("SELECT post_id FROM votes WHERE post_id = :id");
    $getvotes->bindValue(':id', $id);
    $getvotes->execute();
    $getvotes = $getvotes->rowCount();
                               
    return $getvotes;
}

function totalcomments($id, $conn)
{
$query = $conn->prepare("SELECT COUNT(*) FROM comments WHERE post_id = :id");
$query->bindValue(':id', $id);
$query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);
$gc  = $row["COUNT(*)"];
return $gc;
}
