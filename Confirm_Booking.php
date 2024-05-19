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
            border-radius: 15px;  /* Make the corners round */
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

        // Get booking details from POST or GET data
        $hallId = isset($_POST['hallId']) ? $_POST['hallId'] : (isset($_GET['hallId']) ? $_GET['hallId'] : null);
        $hallName = isset($_POST['hallName']) ? $_POST['hallName'] : (isset($_GET['hallName']) ? $_GET['hallName'] : null);
        $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : (isset($_GET['startDate']) ? $_GET['startDate'] : null);
        $duration = isset($_POST['duration']) ? $_POST['duration'] : (isset($_GET['duration']) ? $_GET['duration'] : null);
        $numberOfAudience = isset($_POST['numberOfAudience']) ? $_POST['numberOfAudience'] : (isset($_GET['numberOfAudience']) ? $_GET['numberOfAudience'] : null);
        $time = isset($_POST['time']) ? $_POST['time'] : (isset($_GET['time']) ? $_GET['time'] : null);
        $hallImage = isset($_POST['hallImage']) ? $_POST['hallImage'] : (isset($_GET['hallImage']) ? $_GET['hallImage'] : null);
        $rentalDetails = isset($_POST['rentalDetails']) ? $_POST['rentalDetails'] : (isset($_GET['rentalDetails']) ? $_GET['rentalDetails'] : null);

        // Calculate end date based on start date and duration
        if ($startDate && $duration) {
            $startDateObj = new DateTime($startDate);
            $startDateObj->add(new DateInterval('P' . $duration . 'D'));
            $endDate = $startDateObj->format('Y-m-d');
        }

        // Fetch hall details based on hallId
        $hallDescription = '';
        $rentalCharge = 0;
        if ($hallId) {
            $sql = "SELECT description, rentalCharge FROM dbProj_Hall WHERE hallId = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $hallId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($hall = $result->fetch_assoc()) {
                $hallDescription = $hall['description'];
                $rentalCharge = $hall['rentalCharge'];
            }
        }

        // Fetch timing details based on selected time
        $timingSql = "SELECT timingSlotStart, timingSlotEnd FROM dpProj_HallsTimingSlots WHERE hallId = ? AND timingSlotStart <= ? AND timingSlotEnd >= ?";
        $stmt = $db->prepare($timingSql);
        $stmt->bind_param("iss", $hallId, $time, $time);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($timing = $result->fetch_assoc()) {
            $timingSlotStart = new DateTime($timing['timingSlotStart']);
            $timingSlotEnd = new DateTime($timing['timingSlotEnd']);
            $interval = $timingSlotStart->diff($timingSlotEnd);
            $hours = $interval->h + ($interval->days * 24);
            $rentalDetails = $hours * $duration * $rentalCharge;
        }

        // Ensure all details are available for display
        $hallName = htmlspecialchars($hallName);
        $startDate = htmlspecialchars($startDate);
        $endDate = htmlspecialchars($endDate);
        $duration = htmlspecialchars($duration);
        $numberOfAudience = htmlspecialchars($numberOfAudience);
        $time = htmlspecialchars($time);
        $hallImage = htmlspecialchars($hallImage);
        $rentalDetails = htmlspecialchars($rentalDetails);
        ?>

        <div class="hall-details">
            <h2><?php echo $hallName; ?></h2>
            <img class="hall-image" src="<?php echo $hallImage; ?>" alt="<?php echo $hallName; ?>">
            <p class="hall-description"><?php echo $hallDescription; ?></p>
        </div>

        <div class="reservation-details">
            <h3><b>Reservation Details</b></h3>
            <p>Start Date: <?php echo $startDate; ?></p>
            <p>End Date: <?php echo $endDate; ?></p>
            <p>Duration: <?php echo $duration; ?> days</p>
            <p>Number of Audience: <?php echo $numberOfAudience; ?></p>
            <p>Time: <?php echo $timingSlotStart->format('h:i A') . ' - ' . $timingSlotEnd->format('h:i A'); ?></p>
            <p>Rental Details: <?php echo $rentalDetails; ?> BD</p>
        </div>

        <div class="form-buttons">
            <form method="post" action="update_page.php">
                <input type="hidden" name="hallId" value="<?php echo $hallId; ?>">
                <input type="hidden" name="hallName" value="<?php echo $hallName; ?>">
                <input type="hidden" name="start_date" value="<?php echo $startDate; ?>">
                <input type="hidden" name="duration" value="<?php echo $duration; ?>">
                <input type="hidden" name="end_date" value="<?php echo $endDate; ?>">
                <input type="hidden" name="audience" value="<?php echo $numberOfAudience; ?>">
                <input type="hidden" name="time" value="<?php echo $time; ?>">
                <input type="hidden" name="hallImage" value="<?php echo $hallImage; ?>">
                <input type="hidden" name="rentalDetails" value="<?php echo $rentalDetails; ?>">
                <input type="submit" value="Update Details">
            </form>
            <form method="post" action="select_services.php">
                <input type="hidden" name="hallId" value="<?php echo $hallId; ?>">
                <input type="hidden" name="hallName" value="<?php echo $hallName; ?>">
                <input type="hidden" name="start_date" value="<?php echo $startDate; ?>">
                <input type="hidden" name="duration" value="<?php echo $duration; ?>">
                <input type="hidden" name="end_date" value="<?php echo $endDate; ?>">
                <input type="hidden" name="audience" value="<?php echo $numberOfAudience; ?>">
                <input type="hidden" name="time" value="<?php echo $time; ?>">
                <input type="hidden" name="hallImage" value="<?php echo $hallImage; ?>">
                <input type="hidden" name="rentalDetails" value="<?php echo $rentalDetails; ?>">
                <input type="submit" value="Proceed">
            </form>
            <input type="button" value="Cancel" onclick="window.location.href='searchAndBooking.php';">
        </div>
    </div>

    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</body>
</html>
