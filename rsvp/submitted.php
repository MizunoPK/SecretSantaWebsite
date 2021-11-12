<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christmas Party - RSVP Submitted</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../common.css">
    <link rel="stylesheet" href="submitted.css">
</head>
<body>

    <!-- Header -->
    <?php include '../common_header.php';?>

    <!-- Nav  -->
    <?php include 'rsvp_nav.php';?>
    
    <main>
    <h3 id="thanks">Thank you for RSVP'ing!</h3>

    <div id="moreInfo">
    <div id="attending">
    <?php if (isset($_GET['attending'])): ?>
        <?php if ($_GET['attending'] === '0'): ?>
            <p>We're sad you can't make it!</p>
        <?php endif ?>

        <?php if ($_GET['attending'] === '1'): ?>
            <p>We look forward to seeing you! Once again, here are the party details:</p>
            <div><strong>When: </strong> $date$</div>
            <div><strong>Where: </strong> $location$</div>
        <?php endif ?>
    <?php endif ?>
    </div>
    <p>You may update any info you submitted in this form by revisiting the <a href="invite.php">RSVP page</a> and resubmitting the form.</p> <p>You have until $date$ to update any information regarding the Secret Santa. On that date, we will be generating the Secret Santa targets. After that date, any changes in your information/status regarding the Secret Santa should be sent to Kai Mizuno.</p>
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