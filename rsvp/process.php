<?php
include "../database/database.php";

function process_rsvp() {
    // Set up database
    $conn = getConnection();

    // Get the id & whether or not they are attending
    $person_id = (int) $_POST['person_id'];
    $attending = 0;
    $in_secret_santa = 0;
    $password = "";
    $ideas = "";
    $dietary_restrictions = "";

    // if they are attending, keep collecting info
    if ( $_POST['rsvp'] === "yes" ) {
        $attending = 1;

        // Get whether or not they are doing the secret santa
        // If they are going to be in the secret santa, keep collecting info
        if ( $_POST['santa'] === "yes" ) {
            $in_secret_santa = 1;

            // Get their password
            if ( isset($_POST['password']) ) {
                $password = $_POST['password'];
            }

            // Get their ideas
            if ( isset($_POST['ideas']) ) {
                $ideas = $_POST['ideas'];
            }

            // Get their dietary restrictions
            if ( isset($_POST['dietary_restrictions']) ) {
                $dietary_restrictions = $_POST['dietary_restrictions'];
            }

            // Update the pairings table with the pairings they entered
            if ( isset($_POST['old_target_1']) ) {
                $old_target_1 = (int) $_POST['old_target_1'];
                insertPair($conn, $person_id, $old_target_1, 2017);
            }
            if ( isset($_POST['old_target_2']) ) {
                $old_target_2 = (int) $_POST['old_target_2'];
                insertPair($conn, $person_id, $old_target_2, 2019);
            }
        }
    }

    // Update the database with the rsvp info
    rsvp($conn, $person_id, $attending, $in_secret_santa, $password, $ideas, $dietary_restrictions);

    // Send to the thanks screen
    header("Location: submitted.php?attending=" . $attending);
}

process_rsvp();
?>