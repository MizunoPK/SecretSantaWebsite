<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christmas Party - Santa Info</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../common.css">
    <link rel="stylesheet" href="santa.css">
</head>
<body>
    <!-- Include other PHP scripts we use -->
    <?php 
        // Make sure they are allowed to view this page
        include "../session.php";
        accessCheck(0);

        // Get their info from the database
        include "../database/database.php";
        $conn = getConnection();
        $id = $_SESSION['id'];
        $personRow = getPerson($conn, $id);

        // get the party info
        $partyRow = getCurrentParty($conn);
    ?>
    

    <!-- Header -->
    <?php include '../common_header.php';?>

    <!-- Nav -->
    <?php include 'santa_nav.php';?>
    
    <main>
    <h3>Secret Santa Details</h3>
    <h4 id="helloPerson">Hello, <?php echo $personRow['first_name'] ?>!</h4>

    <div id="santaInfoRegion">
    <?php if ( $personRow['in_secret_santa'] === 0 ): ?>
    <!-- Case: User is not participating in secret santa -->
    <p id="notParticipating">You are currently not signed up to be participating in the Secret Santa. If you have changed your mind, you may resubmit the <a href="../rsvp/invite.php">RSVP</a> before <?php echo $partyRow['rsvp_deadline'] ?>. If it has passed that date, reach out to Cameron/Kai to see if we can get you in.</p>

    <?php else: ?>
        <!-- Case: User is participating in secret santa -->
        <?php 
            // Get the person's target
            // This will return null if the target has not been assigned
            $targetRow = getTarget($conn, $id, date("Y"));
        ?>

        <?php if ( is_null($targetRow) ): ?>
            <!-- Case: User is participating but the results are not out yet -->
            <p id="resultsNotOut">Secret Santa targets have not been generated yet! Targets will be generated on <?php echo $partyRow['rsvp_deadline'] ?>, at which point this page will update to show you who you will be the Santa for.</p>
        <?php else: ?>
            <!-- Case: User is participating and the results are out -->
            <div id="target">You Are the Secret Santa For: <strong><?php echo $targetRow['first_name'] . " " . $targetRow['last_name'] ?></strong></div>
            <div id="ideas">
                <?php 
                    if ( $targetRow['ideas'] === "" ) {
                        echo "<div id=\"noIdeas\">" . $targetRow['first_name'] . " didn't submit any gift topics...</div>";
                    }
                    else {
                        echo "<div id=\"ideasLabel\">Here are the gift topics " . $targetRow['first_name'] . " submitted:</div>";
                        echo "<div id=\"ideasText\">" . $targetRow['ideas'] . "</div>";
                    }
                    echo "<br><p>The price cap for this year's party is <strong>$" . $partyRow['price_cap'] . "</strong>. If you have any questions or concerns, reach out to Cameron or Kai.</p>";
                ?>
            </div>
        <?php endif ?>
    <?php endif ?>
    </div>

    </main>

    <!-- Footer -->
    <?php include '../common_footer.php';?>
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        
    </script>
</body>
</html>