<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Booking</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            margin: 0; 
            min-height: 100vh; 
            background-color: white; 
            color: black; 
        }
        .container { 
            text-align: center; 
            max-width: 800px; 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
        }
        .hall-details { 
            margin-bottom: 20px; 
        }
        .hall-image { 
            max-width: 80%; 
            height: auto; 
            border-radius: 10px; 
        }
        .hall-description { 
            margin-bottom: 10px; 
        }
        .reservation-details { 
            margin-bottom: 20px; 
        }
        .form-buttons input { 
            padding: 10px 20px; 
            margin-right: 10px; 
            background-color: black; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
            text-transform: uppercase;
        }
        .form-buttons input:hover {
            background-color: red; 
            color: white; 
        }
        .form-buttons { 
            margin-top: 20px; 
        }
        .form-buttons form { 
            display: inline-block; 
        }
        header, footer { 
            width: 100%; 
            background-color: #f8f8f8; 
            padding: 10px 0; 
        }
        footer { 
            margin-top: auto; 
        }
        h1 {
            font-size: 2em;
            text-align: center;
            margin-bottom: 20px;
        }
        p, h2, h3 {
            color: black; /* Ensure all text remains black */
        }
    </style>
</head>
<body>
    <header>
        <?php include 'header.html'; ?>
    </header>

    <div class="container">
        <h1>Confirm Booking</h1>
        <?php
        // Include the Database class
        include 'Database.php';
        $db = Database::getInstance();

        // Get hallId, hallName, date, duration, audience, time, and end_date from POST data
        $hallId = isset($_POST['hallId']) ? $_POST['hallId'] : null;
        $hallName = isset($_POST['hallName']) ? $_POST['hallName'] : null;
        $date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $duration = isset($_POST['duration']) ? $_POST['duration'] : null;
        $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : null;
        $audience = isset($_POST['audience']) ? $_POST['audience'] : null;
        $time = isset($_POST['availableTime']) ? $_POST['availableTime'] : null;
        $hallImage = isset($_POST['hallImage']) ? $_POST['hallImage'] : null;
        $rentalDetails = isset($_POST['rentalDetails']) ? $_POST['rentalDetails'] : null;

        // Fetch hall details based on hallId
        $hallDescription = '';
        if ($hallId) {
            $sql = "SELECT description FROM dbProj_Hall WHERE hallId = $hallId";
            $hall = $db->singleFetch($sql);
            if ($hall) {
                $hallDescription = $hall->description;
            }
        }
        ?>

        <div class="hall-details">
            <h2><?php echo htmlspecialchars($hallName); ?></h2>
            <img class="hall-image" src="<?php echo htmlspecialchars($hallImage); ?>" alt="<?php echo htmlspecialchars($hallName); ?>">
            <p class="hall-description"><?php echo htmlspecialchars($hallDescription); ?></p>
        </div>

        <div class="reservation-details">
            <h3><b>Reservation Details</b></h3>
            <p>Start Date: <?php echo htmlspecialchars($date); ?></p>
            <p>End Date: <?php echo htmlspecialchars($endDate); ?></p>
            <p>Duration: <?php echo htmlspecialchars($duration); ?> days</p>
            <p>Number of Audience: <?php echo htmlspecialchars($audience); ?></p>
            <p>Time: <?php echo htmlspecialchars($time); ?></p>
            <p>Rental Details: <?php echo htmlspecialchars($rentalDetails); ?> BD</p>
        </div>

        <div class="form-buttons">
            <form method="post" action="update_page.php">
                <input type="hidden" name="hallId" value="<?php echo htmlspecialchars($hallId); ?>">
                <input type="hidden" name="hallName" value="<?php echo htmlspecialchars($hallName); ?>">
                <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($date); ?>">
                <input type="hidden" name="duration" value="<?php echo htmlspecialchars($duration); ?>">
                <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
                <input type="hidden" name="audience" value="<?php echo htmlspecialchars($audience); ?>">
                <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
                <input type="hidden" name="hallImage" value="<?php echo htmlspecialchars($hallImage); ?>">
                <input type="hidden" name="rentalDetails" value="<?php echo htmlspecialchars($rentalDetails); ?>">
                <input type="submit" value="Update Details">
            </form>
            <form method="post" action="select_services.php">
                <input type="hidden" name="hallId" value="<?php echo htmlspecialchars($hallId); ?>">
                <input type="hidden" name="hallName" value="<?php echo htmlspecialchars($hallName); ?>">
                <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($date); ?>">
                <input type="hidden" name="duration" value="<?php echo htmlspecialchars($duration); ?>">
                <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
                <input type="hidden" name="audience" value="<?php echo htmlspecialchars($audience); ?>">
                <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
                <input type="hidden" name="hallImage" value="<?php echo htmlspecialchars($hallImage); ?>">
                <input type="hidden" name="rentalDetails" value="<?php echo htmlspecialchars($rentalDetails); ?>">
                <input type="submit" value="Proceed">
            </form>
            <input type="button" value="Cancel" onclick="window.location.href='main_page.php';">
        </div>
    </div>

    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</body>
</html>
