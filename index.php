<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christmas Party</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="common.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- Include other PHP scripts we use -->
    <?php 
        include 'database/database.php';
        $conn = getConnection();
        $peopleData = getPeople($conn);
        $partyRow = getCurrentParty($conn);
    ?>
    

    <!-- Header -->
    <?php include 'common_header.php';?>

    
    <main>
        <?php if ( isset($_GET['err']) && $_GET['err'] === 'unauth') : ?>
            <div class="error viewErr">Error: You are not authorized to view that page.</div>
        <?php endif ?>
        <?php if ( isset($_GET['err']) && $_GET['err'] === 'notLoggedIn') : ?>
            <div class="error viewErr">Error: Please log in before viewing that page.</div>
        <?php endif ?>

        <img src="images/tree.png" alt="tree" id="tree">

        <div id="rsvpSection">
            <a href="rsvp/invite.php" id="rsvpLink">Click Here to go to RSVP Form</a>
        </div>

        <!-- Login  -->
        <div id="loginSection">
            <h3>Secret Santa Login:</h3>
            <div class="note">Login here to view who you will be the Secret Santa for. Details will be available starting <?php echo $partyRow['rsvp_deadline'] ?>. Use the password you set in the RSVP form.</div>
            <form action="check_login.php" method="POST">

            <div id="nameSelectRegion">
                <label for="nameSelect" class="form-label">Name:</label>
                <select class="form-select" id="nameSelect" name="person_id" required>
                        <option value="" disabled selected hidden>-- Who Are You? --</option>
                        <?php if ($peopleData): ?>
                            <?php foreach($peopleData as $row): ?>
                                <option value="<?= $row['id'] ?>" data-ideas="<?= $row['ideas'] ?>">
                                    <?= $row['first_name'] ?> <?= $row['last_name'] ?>
                                </option>
                            <?php endforeach ?>
                        <?php endif ?>
                </select>
            </div>

            <div id="passwordRegion">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                <?php if ( isset($_GET['err']) && $_GET['err'] === 'pass') : ?>
                    <div class="error">Error: Incorrect Password</div>
                <?php endif ?>
            </div>

            <button class="btn btn-primary" type="submit">Login</button>

            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'common_footer.php';?>
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        
    </script>
</body>
</html>