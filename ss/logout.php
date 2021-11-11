<?php
// Set up session
if (session_status() <> PHP_SESSION_ACTIVE) session_start();

// Reset variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../index.php");

?>