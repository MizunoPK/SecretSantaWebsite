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
    <!-- Party Section -->
    <div id="partySection" class="dataSection">
        <div class="dataTableSide">
        <p class="tableTitle">Parties</p>
            <?php if ($partyData && $partyData->num_rows > 0): ?>
                <table class="dataTable">
                    <tr>
                        <th>Year</th>
                        <th>RSVP Deadline</th>
                        <th>Party Date</th>
                        <th>Party Location</th>
                    </tr>
                    <?php foreach($partyData as $row): ?>
                        <tr>
                            <td><?= $row['year']; ?></td>
                            <td><?= $row['rsvp_deadline']; ?></td>
                            <td><?= $row['party_date']; ?></td>
                            <td><?= $row['party_location']; ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            <?php else: ?>
                <p class="emptyTable">Database is empty.</p>
            <?php endif ?>
        </div>
        <div class="controlSide">
            asdabsj
        </div>
    </div>
    <hr>

    <!-- People Section -->
    <div id="peopleSection" class="dataSection">
        <div class="dataTableSide">
            <p class="tableTitle">People</p>
            <?php if ($peopleData && $peopleData->num_rows > 0): ?>
                <table class="dataTable">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Invited</th>
                        <th>RSVP</th>
                        <th>Attending</th>
                        <th>In Secret Santa</th>
                        <th>Role</th>
                        <th>Ideas</th>
                    </tr>
                    <?php foreach($peopleData as $row): ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                            <td><?= $row['invited']; ?></td>
                            <td><?= $row['rsvp']; ?></td>
                            <td><?= $row['attending']; ?></td>
                            <td><?= $row['in_secret_santa']; ?></td>
                            <td><?= $row['role']; ?></td>
                            <td><?= $row['ideas']; ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            <?php else: ?>
                <p class="emptyTable">Database is empty.</p>
            <?php endif ?>
        </div>
        <div class="controlSide">
            asdasds
        </div>
    </div>
    <hr>

    <!-- Pairings Section -->
    <div id="pairSection" class="dataSection">
        <div class="dataTableSide">

        </div>
        <div class="controlSide">
            
        </div>
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