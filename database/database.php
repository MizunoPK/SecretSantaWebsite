<?php 
date_default_timezone_set("America/Denver");

// Function: getConnection
// Description: Sets up a connection to the SQL database, and if it successfully connects it returns the connection structure
function getConnection() {
    include 'database_credentials.php';
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
  
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function: getAll
// Inputs:
//      conn - the connection structure for the SQL database 
//      table - The table we are fetching all from
// Description: Given a table, return the results of a SELECT * FROM
function getAll($conn, $table) {
    $sql = "select * from $table";
    $result = mysqli_query($conn, $sql);
    return $result;
}


?>