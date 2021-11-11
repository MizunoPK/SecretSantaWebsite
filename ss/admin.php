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
    
    <main>
    <!-- Generate Secret Santa Big Red Button -->
    <div id="generateSection">
        <!-- <button id="generateButton">Generate Secret Santa Targets</button> -->
        <button type="button" id="generateButton" class="btn btn-danger" onclick="generateSecretSantaTargets()">Generate Secret Santa Targets</button>
    </div>
    <hr>

    <!-- Party Section -->
    <div id="partySection" class="dataSection">
        <div class="dataTableSide">
        <p class="tableTitle">Parties</p>
            <span id="partyTable">
            
            </span>
        </div>
        <div class="controlSide">
            <!-- New/Update party -->
            <form action="">
                <div id="party-year-sec">
                    <label for="party-year">Year</label>
                    <input type="number" name="party-year" id="party-year" title="Enter a year." step=1 required>
                </div>
                <div id="party-rsvp-sec">
                    <label for="party-rsvp">RSVP Deadline</label>
                    <input type="text" name="party-rsvp" id="party-rsvp" title="Enter a RSVP deadline." required>
                </div>
                <div id="party-date-sec">
                    <label for="party-date">Party Date</label>
                    <input type="text" name="party-date" id="party-date" title="Enter a date for the party." required>
                </div>
                <div id="party-location-sec">
                    <label for="party-location">Location</label>
                    <input type="text" name="party-location" id="party-location" title="Enter a location for the party.">
                </div>

                <div id="party-new-sec">
                    <label for="party-new">This is a New Party</label>
                    <input type="checkbox" name="party-new" id="party-new" checked>
                </div>

                
                <!-- If it's a new party: give the option of reseting people's information -->
                <div id="party-reset-sec">
                    <label for="party-reset">Reset Flags (invited, RSVP'd, attending, etc.)</label>
                    <input type="checkbox" name="party-reset" id="party-reset" checked>
                </div>

                <!-- If we're resetting everyone's information: have admin select who to invite -->
                <div id="party-invite-sec">
                    <label for="party-invites">Invitees:</label>
                    <span id="party-invite-list"></span>
                </div>

                <button id="party-submit-button">Submit</button>
                <button id="party-reset-button">Reset</button>
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
    <script>
        updateParty();
        updatePeople();

        // function: generateSecretSantaTargets
        // Description: Attempts to make the pairings for the current year's secret santa targets
        function generateSecretSantaTargets() {
            
        }

        // Function: updateParty
        // Description: Updates the party table to reflect the current state of the database
        function updateParty() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if ( this.response === "null" ) {
                    var emptyTableMsg = "<p class=\"emptyTable\">Database is empty.</p>"
                    document.getElementById("partyTable").innerHTML = emptyTableMsg;
                }
                else {
                    // update the table's html
                    document.getElementById("partyTable").innerHTML = this.responseText;

                    // set up the table being clickable
                    // setupSelectProduct();
                }
            }
            };
            xmlhttp.open("GET","admin_controller.php?q=updateParty",true);
            xmlhttp.send();
        }

        // Function: updatePeople
        // Description: Updates the people table to reflect the current state of the database
        function updatePeople() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if ( this.response === "null" ) {
                    var emptyTableMsg = "<p class=\"emptyTable\">Database is empty.</p>"
                    document.getElementById("peopleTable").innerHTML = emptyTableMsg;
                }
                else {
                    // update the table's html
                    document.getElementById("peopleTable").innerHTML = this.responseText;

                    // set up the table being clickable
                    // setupSelectProduct();
                }
            }
            };
            xmlhttp.open("GET","admin_controller.php?q=updatePeople",true);
            xmlhttp.send();
        }

        function updateInvitees() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if ( this.response === "null" ) {
                    var emptyTableMsg = "<p class=\"emptyTable\">Database is empty.</p>"
                    document.getElementById("party-invite-list").innerHTML = emptyTableMsg;
                }
                else {
                    // update the html
                    document.getElementById("party-invite-list").innerHTML = this.responseText;
                }
            }
            };
            xmlhttp.open("GET","admin_controller.php?q=updateInvitees",true);
            xmlhttp.send();
        }
        
    </script>
</body>
</html>