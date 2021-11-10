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

// Function: rsvp
// Inputs:
//      conn - the connection structure for the SQL database 
//      person_id - the id of the person rsvp'ing
// Description: Update all the information gleamed from the RSVP
function rsvp($conn, $person_id, $attending, $in_secret_santa, $password, $ideas) {
    $rsvp = 1;
    
    $query = "UPDATE people SET rsvp=?, attending=?, in_secret_santa=?, password=?, ideas=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiissi', $rsvp, $attending, $in_secret_santa, $password, $ideas, $person_id);
    $stmt->execute();
}

// Function: insertPairing
// Inputs:
//      conn - the connection structure for the SQL database 
//      santa - the id of the santa
//      target - the id of the target
//      year - the year of this pairing
// Description: Add a new pairing to the pairs table
function insertPairing($conn, $santa, $target, $year) {
    $query = "INSERT INTO pairs (santa, target, party) VALUES (?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iii', $santa, $target, $year);
    $stmt->execute();
}


?>