<?php 
    // Function: getPartyTable
    // Description: Corresponds to when admin.php needs to update its display of the Party table
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
            <th>Targets Generated</th>
            </tr>";

            foreach($partyData as $row) {
                echo "<tr>";
                echo "<td>" . $row['year'] . "</td>";
                echo "<td>" . $row['rsvp_deadline'] . "</td>";
                echo "<td>" . $row['party_date'] . "</td>";
                echo "<td>" . $row['party_location'] . "</td>";
                echo "<td>" . $row['targets_assigned'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
    }

    // Function: getPeopleTable
    // Description: Corresponds to when admin.php needs to update its display of the People table
    function getPeopleTable($conn) {
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
            <th>First Name</th>
            <th>Last Name</th>
            <th>Invited</th>
            <th>RSVP</th>
            <th>Attending</th>
            <th>In Secret Santa</th>
            <th>Role</th>
            <th>Ideas</th>
            </tr>";

            foreach($peopleData as $row) {
                echo "<tr data-id=\"" . $row['id'] . "\" data-pass=\"" . $row['password'] . "\">";
                echo "<td>" . $row['first_name'] . "</td>";
                echo "<td>" . $row['last_name'] . "</td>";
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

    // Function: getInvitees
    // Description: Corresponds to when admin.php needs to update its display of the potential invitees
    function getInvitees($conn) {
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

    // Function: partySubmit
    // Description: Processes the POST from when admin.php sends either a new party for the database or updates to an established one
    function partySubmit($conn) {
        $year = $_POST['year'];
        $rsvp = $_POST['rsvp'];
        $date = $_POST['date'];
        $location = $_POST['location'];
        $targetsAssigned = (int)filter_var($_POST['targetsAssigned'], FILTER_VALIDATE_BOOLEAN);

        // Determine whether this is a CREATE or UPDATE
        if ( $_POST['new'] === "true" ) {
            // Perform the insert
            insertParty($conn, $year, $rsvp, $date, $location, $targetsAssigned);

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
            updateParty($conn, $year, $rsvp, $date, $location, $targetsAssigned);
        }
    }

    // Function: getPartyDropdown
    // Description: Corresponds to when admin.php wants to update the contents of party dropdown menus
    function getPartyDropdown($conn) {
        // Get the parties
        $partyData = getParties($conn);

        // If no matches were found: send a message that there are no matches
        if ( is_null($partyData) ) {
            echo "null";
        }
        // Otherwise: compile the dropdown
        else {
            echo "<option value=\"\" selected>-- Select a Party --</option>";
            foreach($partyData as $row) {
                echo "<option value=\"" . $row['year'] . "\">" . $row['year'] . "</option>";
            }
        }
    }

    // Function: getTargetDropdown
    // Description: Corresponds to when admin.php wants to update the contents of target dropdown menus
    function getTargetDropdown($conn) {
        echo "<option value=\"\" selected>-- Select a Target --</option>";

        if ( !isset($_GET['year']) || $_GET['year'] === "" ) {
            return;
        }

        // Get the targets
        $targetData = getPeople($conn);

        if ( !is_null($targetData) ) {
            foreach($targetData as $row) {
                echo "<option value=\"" . $row['id'] . "\">" . $row['first_name'] . " " . $row['last_name'] . "</option>";
            }
        }
    }

    // Function: getTargetFromYear
    // Description: Corresponds to when admin.php wants to know someone's target for a given year
    function getTargetFromYear($conn) {
        $id = $_GET['id'];
        $year = $_GET['year'];

        // Get the targets
        $targetData = getTarget($conn, $id, $year);

        if ( !is_null($targetData) ) {
            echo $targetData['id'];
        }
        else {
            return "null";
        }
    }

    // Function: getPairTable
    // Description: Gets the table for a given year's pairs
    function getPairTable($conn) {
        $year = $_GET['year'];
        $pairData = getPairs($conn, $year);

        // If no pairs were found: send null
        if ( is_null($pairData) ) {
            echo "null";
        }
        // Otherwise: compile the table of matches
        else {
            echo "<table class=\"dataTable\">
            <tr>
            <th>Santa</th>
            <th>Target</th>
            </tr>";

            foreach($pairData as $row) {
                $santaRow = getPerson($conn, $row['santa']);
                $targetRow = getPerson($conn, $row['target']);

                echo "<tr>";
                echo "<td>" . $santaRow['first_name'] . " " . $santaRow['last_name'] . "</td>";
                echo "<td>" . $targetRow['first_name'] . " " . $targetRow['last_name'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
    }

    // Function: peopleSubmit
    // Description: Processes the POST from when admin.php sends either a new person for the database or updates to an established one
    function peopleSubmit($conn) {
        // Get the POST'ed data
        $id = $_POST['id'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $admin = (int)filter_var($_POST['admin'], FILTER_VALIDATE_BOOLEAN);
        $invited = (int)filter_var($_POST['invited'], FILTER_VALIDATE_BOOLEAN);
        $rsvp = (int)filter_var($_POST['rsvp'], FILTER_VALIDATE_BOOLEAN);
        $attending = (int)filter_var($_POST['attending'], FILTER_VALIDATE_BOOLEAN);
        $ss = (int)filter_var($_POST['ss'], FILTER_VALIDATE_BOOLEAN);
        $password = $_POST['password'];
        $ideas = $_POST['ideas'];
        $targetYear = $_POST['targetYear'];
        $target = $_POST['target'];

        // If it's a new person: Insert into the table
        if ( $id === "" ) {
            $id = insertPerson($conn, $fname, $lname, $admin, $invited, $rsvp, $attending, $ss, $password, $ideas);
        }
        // If it's an update to a person: update the table
        else {
            updatePerson($conn, $id, $fname, $lname, $admin, $invited, $rsvp, $attending, $ss, $password, $ideas);
        }

        // If a target was given: add the pairing to the pairs table
        if ($id !== "") {
            if ( $target !== "") {
                insertPair($conn, $id, $target, $targetYear);
            }
            // If no target was given but a party was selected: delete any corresponding existing pairs for this person
            if ( $targetYear !== "" && $target === "" ) {
                deletePair($conn, $id, $targetYear);
            }
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
            getPeopleTable($conn);
        }
        else if ( $func === "updateInvitees" ) {
            getInvitees($conn);
        }
        else if ( $func === "updatePartyDropdown" ) {
            getPartyDropdown($conn);
        }
        else if ( $func === "updateTargetDropdown" ) {
            getTargetDropdown($conn);
        }
        else if ( $func === "getTarget" ) {
            getTargetFromYear($conn);
        }
        else if ( $func === "updatePairs" ) {
            getPairTable($conn);
        }
        else if ( $func === "deleteYearPairs" ) {
            deletePairsFromYear($conn, $_GET['year']);
        }
    }

    // Test the possible POSTS
    if ( isset($_POST['q']) ) {
        $func = $_POST['q'];
        if ( $func === "partySubmit" ) {
            partySubmit($conn);
        }
        if ( $func === "peopleSubmit" ) {
            peopleSubmit($conn);
        }
    }
?>