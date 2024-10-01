<?php
session_start(); 

// Unset all session variables
$_SESSION = array(); 

// Destroy the session
session_destroy(); 

// Redirect to the home page (index.php)
header("Location: ../views/user/index.php"); 
exit(); 
?>