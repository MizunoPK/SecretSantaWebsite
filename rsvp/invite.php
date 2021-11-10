<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christmas Party</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../common.css">
    <link rel="stylesheet" href="invite.css">
</head>
<body>
    <!-- Include other PHP scripts we use -->
    <?php 
        include '../database/database.php';
        $conn = getConnection();
        $peopleData = getAll($conn, 'people');
    ?>
    

    <!-- Header -->
    <?php include '../common_header.php';?>

    
    <main>
        <!-- Invite -->
        <div id="inviteSection">
            <h3>You Have Been Invited to Our 2021 Christmas Party!</h3>
            <p>We are looking forward to once again being able to have an in-person party for the holidays this year, and hope you can attend!</p>
            <p>Here is the core information about the party:</p>
            <ul>
                <li><strong>When: </strong> $date$</li>
                <li><strong>Where: </strong> $location$</li>
                <li><strong>Events: </strong> We will be doing another Secret Santa this year. This site has been made to help automate the system such that everyone (including Cameron) can participate.</li>
            </ul>
            <p>Please RSVP below if you can join. Reach out to Cameron Hockenhull if you have any questions.</p>
            <br>
        </div>

        <!-- RSVP  -->
        <div id="rsvpSection">
            <h3>RSVP:</h3>
            <form action="process.php" method="post">

            <div id="nameSelectRegion">
                <label for="nameSelect" class="form-label">Name:</label>
                <select class="form-select" id="nameSelect" name="person_id" required>
                        <option value="" disabled selected hidden>-- Who Are You? --</option>
                        <?php if ($peopleData): ?>
                            <?php foreach($peopleData as $row): ?>
                                <?php if ($row['invited'] === '1'): ?>
                                    <option value="<?= $row['id'] ?>" data-ideas="<?= $row['ideas'] ?>">
                                        <?= $row['first_name'] ?> <?= $row['last_name'] ?>
                                    </option>
                                    <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                </select>
            </div>

            <div id="rsvpSelectRegion">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rsvp" id="rsvp-yes" value="yes" checked>
                    <label class="form-check-label" for="rsvp-yes">
                        Yes, I can attend.
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rsvp" id="rsvp-no" value="no">
                    <label class="form-check-label" for="rsvp-no">
                        No, I cannot attend.
                    </label>
                </div>
            </div>

            <div id="secretSantaRegion">
            <div class="form-check">
                    <input class="form-check-input" type="radio" name="santa" id="santa-yes" value="yes" checked>
                    <label class="form-check-label" for="santa-yes">
                        Yes, I want to participate in the Secret Santa.
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="santa" id="santa-no" value="no">
                    <label class="form-check-label" for="santa-no">
                        No, I do not want to participate in the Secret Santa.
                    </label>
                </div>
            </div>

            <div id="passwordSelectRegion">
                <label for="password" class="form-label">Set Password:</label>
                <div class="note">Feel free to keep the password simple. This will only be needed for viewing who you've been assigned for the Secret Santa.</div>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>

            <div id="oldTargetsSelectRegion">
                <label for="oldTargetSelect1" class="form-label">Please select the people you have been a Santa for before:</label>
                <select class="form-select" id="oldTargetSelect1" name="old_target_1">
                        <option value="" selected>-- Select someone --</option>
                        <?php if ($peopleData): ?>
                            <?php foreach($peopleData as $row): ?>
                                <?php if ($row['invited'] === '1'): ?>
                                    <option value="<?= $row['id'] ?>" data-ideas="<?= $row['ideas'] ?>">
                                        <?= $row['first_name'] ?> <?= $row['last_name'] ?>
                                    </option>
                                    <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                </select>
                <select class="form-select" id="oldTargetSelect2" name="old_target_2">
                        <option value="" selected>-- Select someone --</option>
                        <?php if ($peopleData): ?>
                            <?php foreach($peopleData as $row): ?>
                                <?php if ($row['invited'] === '1'): ?>
                                    <option value="<?= $row['id'] ?>" data-ideas="<?= $row['ideas'] ?>">
                                        <?= $row['first_name'] ?> <?= $row['last_name'] ?>
                                    </option>
                                    <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                </select>
            </div>

            <div id="ideasRegion">
                <label for="ideas" class="form-label">Gift Topics:</label>
                <div class="note">Please jot down a few topics that your Santa could draw ideas from for your gift, just in case your Santa doesn't know you very well. Keep it vague enough to leave room for creativity. Some example topics are be 'League of Legends', 'Magic the Gathering', or 'Classic Cars'.</div>
                <textarea class="form-control" id="ideas" name="ideas" rows="3" placeholder="Enter gift topics here..." maxlength="255"></textarea>
                <div class="note">Max Characters: 255</div>
            </div>

            <button class="btn btn-primary" type="submit">Submit</button>

            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php include '../common_footer.php';?>
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        $("#rsvp-yes").click(function() {
            $("#secretSantaRegion").css("display", "block");
            $("#ideasRegion").css("display", "block");
            $("#oldTargetsSelectRegion").css("display", "block");
            $("#passwordSelectRegion").css("display", "block");
        });
        $("#rsvp-no").click(function() {
            $("#secretSantaRegion").css("display", "none");
            $("#ideasRegion").css("display", "none");
            $("#oldTargetsSelectRegion").css("display", "none");
            $("#passwordSelectRegion").css("display", "none");
        });
        $("#santa-yes").click(function() {
            $("#ideasRegion").css("display", "block");
            $("#oldTargetsSelectRegion").css("display", "block");
            $("#passwordSelectRegion").css("display", "block");
        });
        $("#santa-no").click(function() {
            $("#ideasRegion").css("display", "none");
            $("#oldTargetsSelectRegion").css("display", "none");
            $("#passwordSelectRegion").css("display", "none");
        });
    </script>
</body>
</html>