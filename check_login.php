<?php 
    // Setup database connection
    include "database/database.php";
    $conn = getConnection();

    // Get the posted values from the index page
    $id = $_POST['person_id'];
    $password = $_POST['password'];

    // Get the corresponding person
    $personRow = getPerson($conn, $id);

    // Check the password
    if ( $password === $personRow['password'] ) {
        // If it's right... set up the php session and redirect to ss/santa.php
        if (session_status() <> PHP_SESSION_ACTIVE) session_start();
        $_SESSION['id'] = $id;
        $_SESSION['role'] = $personRow['role'];

        // Redirect to santa page
        header("Location: ss/santa.php");
    }
    else {
        // If it isn't right... send back to login page
        header("Location: index.php?err=pass");
    }
?>