<?php 
    function getPartyTable($conn) {
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

    function updateInvitees($conn) {
        // Get the people
        $peopleData = getPeople($conn);

        // If no matches were found: send a message that there are no matches
        if ( is_null($peopleData) ) {
            echo "null";
        }
        // Otherwise: compile the table of matches
        else {
            foreach($peopleData as $row) {
                echo "<input class=\"form-check-input invitee\" type=\"checkbox\" checked data-id=\"" . $row['id'] . "\">";
                echo "<label class=\"form-check-label inviteeLabel\">" . $row['first_name'] . " " . $row['last_name'] . "</label><br>";
            }
        }
    }

    function partySubmit($conn) {
        $year = $_POST['year'];
        $rsvp = $_POST['rsvp'];
        $date = $_POST['date'];
        $location = $_POST['location'];

        // Determine whether this is a CREATE or UPDATE
        if ( $_POST['new'] === "true" ) {
            // Perform the insert
            insertParty($conn, $year, $rsvp, $date, $location);

            // Check if we want to reset values
            if ( $_POST['reset'] === "true" ) {
                resetFlags($conn);
            }

            // Invite the people listed
            $invitees = json_decode($_POST['invitees']);
            foreach ($invitees as $id) {
                invitePerson($conn, $id);
            }
        }
        else {
            // Perform the update
            updateParty($conn, $year, $rsvp, $date, $location);
        }
    }

    include '../database/database.php';
    $conn = getConnection();

    // Test the possible GETS
    if ( isset($_GET['q']) ) {
        $func = $_GET['q'];
        if ( $func === "updateParty" ) {
            getPartyTable($conn);
        }
        else if ( $func === "updatePeople" ) {
            updatePeople($conn);
        }
        else if ( $func === "updateInvitees" ) {
            updateInvitees($conn);
        }
    }

    // Test the possible POSTS
    if ( isset($_POST['q']) ) {
        $func = $_POST['q'];
        if ( $func === "partySubmit" ) {
            partySubmit($conn);
        }
    }
?>