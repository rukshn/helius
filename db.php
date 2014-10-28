<?php
// Make a MySQL Connection
$db_user = "USERNAME";
$db_pass = "PASSWORD";
$db_name = "DATABASENAME";

mysql_connect("localhost", $db_user, $db_pass) or die(mysql_error());
mysql_select_db($db_name) or die(mysql_error());


// Create users table
mysql_query("CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `userid` int(11) NOT NULL,
  `propic` text,
  `token` text,
  `twitter_token` text NOT NULL,
  `twitter_secret` text NOT NULL,
  PRIMARY KEY (`id`)
)")
 or die(mysql_error());  

//Create post table
mysql_query("CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postid` varchar(20) NOT NULL,
  `link` text NOT NULL,
  `tagline` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  `user` varchar(30) NOT NULL,
  `post_time` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
)") or die (mysql_error());

//create vote table

mysql_query("CREATE TABLE `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` varchar(20) NOT NULL,
  `user_id` varchar(30) NOT NULL,
  `op_id` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
)")
or die(mysql_error());

//create comment table

mysql_query("CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `user_id` varchar(30) NOT NULL,
  `post_id` varchar(20) NOT NULL,
  `commentid` varchar(20) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)") or die(mysql_error());

?>
