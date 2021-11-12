<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christmas Party</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../common.css">
    <link rel="stylesheet" href="admin.css">
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
        $partyData = getParties($conn);
        $peopleData = getPeople($conn);

        // get the party info
        $partyRow = getCurrentParty($conn);
    ?>
    

    <!-- Header -->
    <?php include '../common_header.php';?>

    <!-- Nav -->
    <?php include 'santa_nav.php';?>
    
    <main >
    <!-- Generate Secret Santa Big Red Button -->
    <div id="generateSection">
        <!-- <button id="generateButton">Generate Secret Santa Targets</button> -->
        <button type="button" id="generateButton" class="btn btn-danger" onclick="generateSecretSantaTargets()">Generate Secret Santa Targets</button>
    </div>
    <hr>

    <!-- Party Section -->
    <div id="partySection" class="dataSection row">
        <div class="dataTableSide col-md-7">
        <p class="tableTitle">Parties</p>
            <span id="partyTable">
            
            </span>
        </div>
        <div class="controlSide col-md-5">
            <!-- New/Update party -->
            <form action="">
                <div class="party-form-row">
                    <label for="party-year" class="input-label">Year:</label>
                    <input type="number" name="party-year" id="party-year" class="form-input" title="Enter a year." step=1 required>
                </div>
                <div class="party-form-row">
                    <label for="party-rsvp"  class="input-label">RSVP Deadline:</label>
                    <input type="text" name="party-rsvp" id="party-rsvp" class="form-input" title="Enter a RSVP deadline." required>
                </div>
                <div class="party-form-row">
                    <label for="party-date"  class="input-label">Party Date:</label>
                    <input type="text" name="party-date" id="party-date" class="form-input" title="Enter a date for the party." required>
                </div>
                <div class="party-form-row">
                    <label for="party-location"  class="input-label">Location:</label>
                    <input type="text" name="party-location" id="party-location" class="form-input" title="Enter a location for the party.">
                </div>

                <div id="party-new-sec" class="party-form-row">
                    <input type="checkbox" name="party-new" id="party-new" class="form-check-input" checked>
                    <label for="party-new" class="form-check-label">This is a New Party</label>
                </div>

                
                <!-- If it's a new party: give the option of resetting people's information -->
                <div id="party-reset-sec" class="party-form-row">
                    <input type="checkbox" name="party-reset" id="party-reset" class="form-check-input" checked>
                    <label for="party-reset" class="form-check-label">Reset Flags (invited, attending, etc.)</label>
                </div>

                <!-- If we're resetting everyone's information: have admin select who to invite -->
                <div id="party-invite-sec" class="party-form-row">
                    <label for="party-invites">Invitees:</label><br>
                    <span id="party-invite-list"></span>
                </div>

                <div id="party-submit-btns">
                <button id="party-submit-button" class="btn btn-primary">Submit</button>
                <button id="party-reset-button" class="btn btn-secondary btn-sm">Reset</button>
                </div>
            </form>
        </div>
    </div>
    <hr>

    <!-- People Section -->
    <div id="peopleSection" class="dataSection">
        <div class="dataTableSide">
            <p class="tableTitle">People</p>
            <span id="peopleTable"></span>
        </div>
        <div class="controlSide">
            <!-- New/Update Person -->
            <!-- Show/Edit Target -->
        </div>
    </div>
    <hr>

    <!-- Pairings Section -->
    <div id="pairSection" class="dataSection">
        <div class="dataTableSide">

        </div>
        <div class="controlSide">
            <!-- Select Year -->
            <!-- New/Update Pair -->
        </div>
    </div>

    </main>

    <!-- Footer -->
    <?php include '../common_footer.php';?>
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="admin.js"></script>
</body>
</html>