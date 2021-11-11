<?php 
    function updateParty($conn) {
        // Get the parties
        $partyData = getParties($conn);

        // If no matches were found: send a message that there are no matches
        if ( is_null($partyData) ) {
            echo "null";
        }
        // Otherwise: compile the table of matches
        else {
            echo "<table class=\"dataTable\">
            <tr>
            <th>Year</th>
            <th>RSVP Deadline</th>
            <th>Party Date</th>
            <th>Party Location</th>
            </tr>";

            foreach($partyData as $row) {
                echo "<tr>";
                echo "<td>" . $row['year'] . "</td>";
                echo "<td>" . $row['rsvp_deadline'] . "</td>";
                echo "<td>" . $row['party_date'] . "</td>";
                echo "<td>" . $row['party_location'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
    }

    function updatePeople($conn) {
        // Get the people
        $peopleData = getPeople($conn);

        // If no matches were found: send a message that there are no matches
        if ( is_null($peopleData) ) {
            echo "null";
        }
        // Otherwise: compile the table of matches
        else {
            echo "<table class=\"dataTable\">
            <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Invited</th>
            <th>RSVP</th>
            <th>Attending</th>
            <th>In Secret Santa</th>
            <th>Role</th>
            <th>Ideas</th>
            </tr>";

            foreach($peopleData as $row) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                echo "<td>" . $row['invited'] . "</td>";
                echo "<td>" . $row['rsvp'] . "</td>";
                echo "<td>" . $row['attending'] . "</td>";
                echo "<td>" . $row['in_secret_santa'] . "</td>";
                echo "<td>" . $row['role'] . "</td>";
                echo "<td>" . $row['ideas'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
    }

    include '../database/database.php';
    $conn = getConnection();

    $func = $_GET['q'];
    if ( $func === "updateParty" ) {
        updateParty($conn);
    }
    else if ( $func === "updatePeople" ) {
        updatePeople($conn);
    }
?>