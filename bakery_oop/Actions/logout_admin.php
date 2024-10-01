<?php
session_start(); 

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy(); 

// Redirect to the admin login page
header("Location: ../views/login.php"); // Assuming your admin login page is login.php
exit(); 
?>