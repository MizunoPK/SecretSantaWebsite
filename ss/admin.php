<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christmas Party - Admin</title>

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
                <div class="form-row">
                    <label for="party-year" class="input-label">Year:</label>
                    <input type="number" name="party-year" id="party-year" class="form-input" title="Enter a year." step=1 required>
                </div>
                <div class="form-row">
                    <label for="party-rsvp"  class="input-label">RSVP Deadline:</label>
                    <input type="text" name="party-rsvp" id="party-rsvp" class="form-input" title="Enter a RSVP deadline." required>
                </div>
                <div class="form-row">
                    <label for="party-date"  class="input-label">Party Date:</label>
                    <input type="text" name="party-date" id="party-date" class="form-input" title="Enter a date for the party." required>
                </div>
                <div class="form-row">
                    <label for="party-location"  class="input-label">Location:</label>
                    <input type="text" name="party-location" id="party-location" class="form-input" title="Enter a location for the party.">
                </div>

                <div id="party-new-sec" class="form-row">
                    <input type="checkbox" name="party-new" id="party-new" class="form-check-input" checked>
                    <label for="party-new" class="form-check-label">This is a New Party</label>
                </div>

                
                <!-- If it's a new party: give the option of resetting people's information -->
                <div id="party-reset-sec" class="form-row">
                    <input type="checkbox" name="party-reset" id="party-reset" class="form-check-input" checked>
                    <label for="party-reset" class="form-check-label">Reset Flags (invited, attending, etc.)</label>
                </div>

                <!-- If we're resetting everyone's information: have admin select who to invite -->
                <div id="party-invite-sec" class="form-row">
                    <label for="party-invites">Invitees:</label><br>
                    <span id="party-invite-list"></span>
                </div>

                <div class="submit-btns">
                    <button id="party-submit-button" class="btn btn-primary">Submit</button>
                    <button id="party-reset-button" class="btn btn-secondary btn-sm">Reset</button>
                </div>
            </form>
        </div>
    </div>
    <hr>

    <!-- People Section -->
    <div id="peopleSection" class="dataSection row">
        <div class="dataTableSide col-md-7">
            <p class="tableTitle">People</p>
            <span id="peopleTable"></span>
        </div>
        <div class="controlSide col-md-5">
            <!-- New/Update Person -->
            <form action="">
                <!-- Person Name -->
                <div class="form-row">
                    <label for="people-fname" class="input-label">Name:</label>
                    <div class="row">
                        <div class="col-sm-6"><input type="text" name="people-fname" id="people-fname" class="form-input" title="Enter a First Name." placeholder="First Name" required></div>
                        <div class="col-sm-6"><input type="text" name="people-lname" id="people-lname" class="form-input" title="Enter a Last Name." placeholder="Last Name" required></div>
                    </div>
                </div>

                <!-- Person Flags -->
                <div class="form-row row">
                    <div class="col-md-4 col-sm-6">
                        <input type="checkbox" name="people-admin" id="people-admin" class="form-check-input">
                        <label for="people-admin" class="form-check-label">Admin</label>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <input type="checkbox" name="people-invited" id="people-invited" class="form-check-input">
                        <label for="people-invited" class="form-check-label">Invited</label>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <input type="checkbox" name="people-rsvp" id="people-rsvp" class="form-check-input">
                        <label for="people-rsvp" class="form-check-label">RSVP'd</label>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <input type="checkbox" name="people-attending" id="people-attending" class="form-check-input">
                        <label for="people-attending" class="form-check-label">Attending</label>
                    </div>
                    <div class="col-md-8 col-sm-6">
                        <input type="checkbox" name="people-ss" id="people-ss" class="form-check-input">
                        <label for="people-ss" class="form-check-label">Secret Santa</label>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-row">
                    <label for="people-password"  class="input-label">Password:</label>
                    <input type="password" name="people-password" id="people-password" class="form-input" title="Enter a Password." required>
                    <a href="#" class="link-info" id="show-hide-link">Show Password</a>
                </div>

                <!-- Ideas -->
                <div class="form-row">
                    <label for="people-ideas"  class="input-label">Ideas:</label>
                    <textarea class="form-control" id="people-ideas" name="people-ideas" rows="2" placeholder="Enter gift topics here..." maxlength="255"></textarea>
                </div>

                <!-- Target -->
                <div class="form-row">
                    <label for="people-target"  class="input-label">Target:</label>
                    
                    <div class="row">
                        <div class="col-md-6"><select class="form-select" id="people-target-year" name="people-target-year">
                        </select></div>
                        <div class="col-md-6"><select class="form-select" id="people-target" name="people-target">
                        </select></div>
                    </div>
                </div>

                <!-- Hidden id -->
                <input type="hidden" id="people-id" name="people-id" val="0">

                <!-- Submit Buttons -->
                <div class="submit-btns">
                    <button id="people-submit-button" class="btn btn-primary">Submit</button>
                    <button id="people-reset-button" class="btn btn-secondary btn-sm">Reset</button>
                </div>
            </form>
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