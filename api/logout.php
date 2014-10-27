<?php
//Log-out the user and clear the session, clear user cookie

session_start();
session_destroy();
setcookie('info', '', time()-3600, '/');   
header('Location: ../');
