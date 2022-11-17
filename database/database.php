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

// ! People Table
// Function: getPeople
// Inputs:
//      conn - the connection structure for the SQL database
// Description: Get the people table, sorted by first name
function getPeople($conn) {
    $sql = "SELECT * FROM people ORDER BY first_name";
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

// Function: insertPerson
// Description: Inserts a new person into the database and returns the id of the new person
function insertPerson($conn, $fname, $lname, $admin, $invited, $rsvp, $attending, $ss, $password, $ideas, $dietary_restrictions) {
    $query = "INSERT INTO people (first_name, last_name, role, invited, rsvp, attending, in_secret_santa, password, ideas, dietary_restrictions) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssiiiiisss', $fname, $lname, $admin, $invited, $rsvp, $attending, $ss, $password, $ideas, $dietary_restrictions);
    $result = $stmt->execute();
    if ($result) {
        return $conn->lastInsertId();
    }
    return "";
}

// Function: updatePerson
// Description: Updates a person in the database
function updatePerson($conn, $id, $fname, $lname, $admin, $invited, $rsvp, $attending, $ss, $password, $ideas, $dietary_restrictions) {
    $query = "UPDATE people SET first_name=?, last_name=?, role=?, invited=?, rsvp=?, attending=?, in_secret_santa=?, password=?, ideas=?, dietary_restrictions=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssiiiiisssi', $fname, $lname, $admin, $invited, $rsvp, $attending, $ss, $password, $ideas, $dietary_restrictions, $id);
    $stmt->execute();
}

// Function: rsvp
// Inputs:
//      conn - the connection structure for the SQL database 
//      person_id - the id of the person rsvp'ing
// Description: Update all the information gleamed from the RSVP
function rsvp($conn, $person_id, $attending, $in_secret_santa, $password, $ideas, $dietary_restrictions) {
    $rsvp = 1;
    
    $query = "UPDATE people SET rsvp=?, attending=?, in_secret_santa=?, password=?, ideas=?, dietary_restrictions=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiisssi', $rsvp, $attending, $in_secret_santa, $password, $ideas, $dietary_restrictions, $person_id);
    $stmt->execute();
}

// Function: resetFlags
// Description: Resets the invited, rsvp, attending, and in_secret_santa flags for all people
function resetFlags($conn) {
    $sql = "UPDATE people SET rsvp=0, attending=0, in_secret_santa=0, invited=0";
    mysqli_query($conn, $sql);
}

// Function: invitePerson
// Description: Invites a given person to the party
function invitePerson($conn, $id) {
    $sql = "UPDATE people SET invited=1 WHERE id=" . $id;
    mysqli_query($conn, $sql);
}

// Function: getSSPartipants
// Description: Fetches a list of the people participating in the secret santa
function getSSParticipants($conn) {
    $sql = "SELECT * FROM people WHERE in_secret_santa=1";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// ! Party Table
// Function: getParties
// Inputs:
//      conn - the connection structure for the SQL database
// Description: Get the parties table, sorted by year
function getParties($conn) {
    $sql = "SELECT * FROM party ORDER BY year";
    $result = mysqli_query($conn, $sql);
    return $result;
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

// Function: insertParty
// Description: inserts a new party in the database
function insertParty($conn, $year, $rsvp, $date, $location, $price_cap, $targetsAssigned) {
    $query = "INSERT INTO party (year, rsvp_deadline, party_date, party_location, price_cap, targets_assigned) VALUES (?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isssii', $year, $rsvp, $date, $location, $price_cap, $targetsAssigned);
    $stmt->execute();
}

// Function updateParty
// Description: Updates a party in the database with new info
function updateParty($conn, $year, $rsvp, $date, $location, $price_cap, $targetsAssigned) {
    $query = "UPDATE party SET rsvp_deadline=?, party_date=?, party_location=?, price_cap=?, targets_assigned=? WHERE year=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssiii', $rsvp, $date, $location, $price_cap, $targetsAssigned, $year);
    $stmt->execute();
}

// Function: flagSSGenerated
// Description: Flags the given year as having had its secret santa targets generated
function flagSSGenerated($conn, $year, $targetsAssigned) {
    $query = "UPDATE party SET targets_assigned=? WHERE year=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $targetsAssigned, $year);
    $stmt->execute();
}

// ! Pair Table
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

// Function: insertPair
// Inputs:
//      conn - the connection structure for the SQL database 
//      santa - the id of the santa
//      target - the id of the target
//      year - the year of this pairing
// Description: Add a new pairing to the pairs table
function insertPair($conn, $santa, $target, $year) {
    // Check if the year/santa combo already exists, and just update it if it does
    $query = "SELECT * FROM pairs WHERE santa=? AND party=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $santa, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $query = "UPDATE pairs SET target=? WHERE santa=? AND party=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iii', $target, $santa, $year);
        $stmt->execute();
        return;
    }

    // If it wasn't already in the table, go ahead and insert it
    $query = "INSERT INTO pairs (santa, target, party) VALUES (?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iii', $santa, $target, $year);
    $stmt->execute();
}

// Function: getTarget
// Inputs: 
//      conn - the connection structure for the SQL database 
//      santa_id - the id of the santa
//      year - the year we want the target from
// Description: gets the row for the target of the given santa... if no target has been assigned yet it returns null
function getTarget($conn, $santa_id, $year) {
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

// Function: getPreviousTargets
// Description: Gets a list of the ids of previous targets for a given santa
function getPreviousTargets($conn, $santa_id) {
    $query = "SELECT * FROM pairs WHERE santa=? ORDER BY party";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $santa_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $ids = array();
        foreach ( $result as $row ) {
            $ids[] = $row['target'];
        }
        return $ids;
    }
    else {
        return null;
    }
}

// Function: deletePairsFromYear
// Decription: Deletes all pairs from the given year
function deletePairsFromYear($conn, $year) {
    $query = "DELETE FROM pairs WHERE party=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $year);
    $stmt->execute();

    // Signal that the pairs are not generated anymore for that party
    flagSSGenerated($conn, $year, 0);
}

// Function: deletePair
// Description: Deletes a single pair from the database
function deletePair($conn, $santa, $year) {
    $query = "DELETE FROM pairs WHERE party=? AND santa=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $year, $santa);
    $stmt->execute();
}
?>