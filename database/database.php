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

// Function: getPeople
// Inputs:
//      conn - the connection structure for the SQL database
// Description: Get the people table, sorted by first name
function getPeople($conn) {
    $sql = "SELECT * FROM people ORDER BY first_name";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Function: getParties
// Inputs:
//      conn - the connection structure for the SQL database
// Description: Get the parties table, sorted by year
function getParties($conn) {
    $sql = "SELECT * FROM party ORDER BY year";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Function: getPairs
// Inputs:
//      conn - the connection structure for the SQL database
//      year - the year of the party pairs are being fetched for
// Description: Get the pairs for a given party
function getPairs($conn, $year) {
    $sql = "SELECT * FROM pairs WHERE party=" . $year;
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Function: getPerson
// Inputs:
//      conn - the connection structure for the SQL database
//      id - the id of the person being fetched
// Description: Get the people table, sorted by first name
function getPerson($conn, $id) {
    $query = "SELECT * FROM people WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    }
    else {
        return null;
    }
}

// Function: getCurrentParty
// Inputs:
//      conn - the connection structure for the SQL database
// Description: Get the row from the party table for this year's party
function getCurrentParty($conn) {
    $year = date("Y");
    $query = "SELECT * FROM party WHERE year=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $year);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    }
    else {
        return null;
    }
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

// Function: getTarget
// Inputs: 
//      conn - the connection structure for the SQL database 
//      santa_id - the id of the santa
// Description: gets the row for the target of the given santa... if no target has been assigned yet it returns null
function getTarget($conn, $santa_id) {
    $year = date("Y");

    // Find the row from pairs
    $query = "SELECT * FROM pairs WHERE santa=? AND party=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $santa_id, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Return the row for the santa's target
        return getPerson($conn, $row['target']);
    }
    else {
        return null;
    }
}
?>