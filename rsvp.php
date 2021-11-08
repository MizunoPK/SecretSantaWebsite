<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christmas Party</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="common.css">
    <link rel="stylesheet" href="rsvp.css">
</head>
<body>
    <!-- Include other PHP scripts we use -->
    <?php 
        include 'database/database.php';
        $conn = getConnection();
        $peopleData = getAll($conn, 'people');
    ?>
    

    <!-- Header -->
    <?php include 'common_header.php';?>

    <!-- RSVP  -->
    <main>
        <h3>RSVP:</h3>
        <form action="process_rsvp.php" method="post">

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

        <button class="btn btn-primary" type="submit">Submit</button>

        </form>
    </main>

    <!-- Footer -->
    <?php include 'common_footer.php';?>
    

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>

    </script>
</body>
</html>