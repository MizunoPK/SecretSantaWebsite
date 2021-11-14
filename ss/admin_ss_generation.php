<?php
// Function: checkGeneration
// Description: Checks whether or not we have already generated targets this year. Returns '1' back to admin.php if we have, and '0' if not
function alreadyGenerated($conn) {
    $party = getCurrentParty($conn);
    if ( is_null($party) ) {
        echo "null";
    }
    else {
        echo $party['targets_assigned'];
    }
}

// Function: performGeneration
// Description: Performs the secret santa generation of targets
function performGeneration($conn) {
    // Get a list of the people participating in the secret santa
    $peopleData = getSSParticipants($conn);

    // Get a list of ids
    $peopleIDs = array();
    foreach ($peopleData as $row) {
        $peopleIDs[] = $row['id'];
    }

    // Compile a dictionary, where each person id is a key and they point to a list of potential matches
    $santaDict = array();
    foreach ($peopleIDs as $santaID) {
        // Get a list of previous targets
        $previousTargets = getPreviousTargets($conn, $santaID);

        // Get a list of the people this person could be the santa for
        $potentialTargets = array_values(array_diff($peopleIDs, array($santaID)));
        if ( !is_null($previousTargets) ) {
            $potentialTargets = array_values(array_diff($potentialTargets, $previousTargets));
        }

        // Add to the dictionary
        $santaDict[$santaID] = $potentialTargets;
    }

    // Sort the dictionary by the length of each key's potential targets
    uasort($santaDict, "length_sort");

    // Track established pairings as we loop through each santa and determine a pair
    $pairs = array();
    foreach ($santaDict as $santaID => $potentialTargets) {
        // Get a list of the potential targets that have not been assigned yet
        $pickedTargets = array_values($pairs);
        $unpickedPotentialTargets = array_diff($potentialTargets, $pickedTargets);

        // If the santa has no potential new targets, then try to draw on their old targets
        if ( count($unpickedPotentialTargets) === 0 ) {
            $santaRow = getPerson($conn, $santaID);

            // Get old targets to potentially draw a match from
            $oldTargets = getPreviousTargets($conn, $santaID);
            if ( is_null($oldTargets) ) {
                echo "Error: Could not assign a target for " . $santaRow['first_name'] . " " . $santaRow['last_name'] . ". No available new targets.\n";
                continue;
            }
            
            // Pop the most recent target
            $mostRecentTarget = array_pop($oldTargets);

            // If the most recent target was their only previous target...
            if ( count($oldTargets) === 0 ) {
                // If this only potential target has already been taken... shit
                if ( in_array($mostRecentTarget, $pickedTargets) ) {
                    echo "Error: SHIT SHIT SHIT! Could not assign a target for " . $santaRow['first_name'] . " " . $santaRow['last_name'] . ". No available new targets, and this person has only one old target, which has already been assigned.\n";
                    continue;
                }

                // assign it but output a warning
                $pairs[$santaID] = $mostRecentTarget;
                echo "Warning: The only available target for " . $santaRow['first_name'] . " " . $santaRow['last_name'] . " was already assigned to them in the last party they participated in.\n";
                continue;
            }

            // Remove any already picked targets from the pool
            $potentialOldTargets = array_diff($oldTargets, $pickedTargets);

            // If we don't have any potential targets still... shit
            if ( count($potentialOldTargets) === 0 ) {
                // Double check if we can assign the most recent target again instead
                if ( !in_array($mostRecentTarget, $pickedTargets) ) {
                    // assign it but output a warning
                    $pairs[$santaID] = $mostRecentTarget;
                    echo "Warning: The only available target for " . $santaRow['first_name'] . " " . $santaRow['last_name'] . " was already assigned to them in the last party they participated in.\n";
                    continue;
                }

                // Otherwise... shit
                echo "Error: SHIT SHIT SHIT! Could not assign a target for " . $santaRow['first_name'] . " " . $santaRow['last_name'] . ". No available targets.\n";
                continue;
            }

            // Finally, pick a random person from the old targets
            $randomKey = array_rand($potentialOldTargets, 1);
            $target = $potentialOldTargets[$randomKey];
            $pairs[$santaID] = $target;
        }
        else {
            // Pick a random person from the potential targets and assign the pair
            $randomKey = array_rand($unpickedPotentialTargets, 1);
            $target = $unpickedPotentialTargets[$randomKey];
            $pairs[$santaID] = $target;
        }
    }
    
    // Populate the database with the new pairs
    $year = date("Y");
    foreach ($pairs as $santaID => $targetID) {
        insertPair($conn, $santaID, $targetID, $year);
    }

    // Flag that the pairs have been generated
    flagSSGenerated($conn, date("Y"), 1);

    // Output that we have finished
    echo "Generation Complete!";
}

// Helper function used to sort an array by list length when the values are lists
function length_sort($a,$b){
    $a_len = count($a);
    $b_len = count($b);
    if ($a_len==$b_len) return 0;
    return ($a_len<$b_len)?-1:1;
}

include '../database/database.php';
    $conn = getConnection();

    // Test the possible GETS
    if ( isset($_GET['q']) ) {
        $func = $_GET['q'];
        if ( $func === "check" ) {
            alreadyGenerated($conn);
        }
        else if ( $func === "generate" ) {
            performGeneration($conn);
        }
    }


?>