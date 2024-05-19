<?php
include 'Database.php';
$db = Database::getInstance();

// Get hall details from the query string
$hallId = isset($_GET['hallId']) ? $_GET['hallId'] : '';
$hallName = isset($_GET['hallName']) ? $_GET['hallName'] : '';
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
$duration = isset($_GET['duration']) ? $_GET['duration'] : '';
$numberOfAudience = isset($_GET['numberOfAudience']) ? $_GET['numberOfAudience'] : '';
$time = isset($_GET['time']) ? $_GET['time'] : '';
$hallImage = isset($_GET['hallImage']) ? $_GET['hallImage'] : '';
$rentalDetails = isset($_GET['rentalDetails']) ? $_GET['rentalDetails'] : '';
$timingSlotStart = isset($_GET['timingSlotStart']) ? $_GET['timingSlotStart'] : '';
$timingSlotEnd = isset($_GET['timingSlotEnd']) ? $_GET['timingSlotEnd'] : '';

// Convert to 12-hour format function
function convertTo12HourFormat($time) {
    return date("g:i A", strtotime($time));
}

$time12Start = !empty($timingSlotStart) ? convertTo12HourFormat($timingSlotStart) : '';
$time12End = !empty($timingSlotEnd) ? convertTo12HourFormat($timingSlotEnd) : '';

// Fetch available time slots for the hall if no specific time is selected
$timeSlots = [];
if (empty($time)) {
    $timeSlotsSql = "SELECT timingSlotStart, timingSlotEnd FROM dpProj_HallsTimingSlots WHERE hallId = ?";
    $stmt = $db->prepare($timeSlotsSql);
    $stmt->bind_param("i", $hallId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $timeSlots[] = [
            'start' => convertTo12HourFormat($row['timingSlotStart']),
            'end' => convertTo12HourFormat($row['timingSlotEnd']),
        ];
    }
}

// Form submission handling
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hallId = $_POST['hallId'];
    $hallName = $_POST['hallName'];
    $startDate = $_POST['startDate'];
    $duration = $_POST['duration'];
    $numberOfAudience = $_POST['numberOfAudience'];
    $time = $_POST['time'];

    // Validate audience number
    if (empty($numberOfAudience) || $numberOfAudience <= 0) {
        $errors[] = "Please enter a valid number of audience.";
    }

    // Validate other fields
    if (empty($startDate)) {
        $errors[] = "Please select a start date.";
    }

    if (empty($duration)) {
        $errors[] = "Please select a duration.";
    }

   /* if (empty($time)) {
        $errors[] = "Please select a time slot.";
    }*/

    // Check audience against hall capacity
    $capacitySql = "SELECT capacity FROM dbProj_Hall WHERE hallId = ?";
    $stmt = $db->prepare($capacitySql);
    $stmt->bind_param("i", $hallId);
    $stmt->execute();
    $result = $stmt->get_result();
    $hall = $result->fetch_assoc();

    if ($numberOfAudience > $hall['capacity']) {
        $errors[] = "The number of audience exceeds the hall capacity.";
    }

    // Recheck hall availability
    if (empty($errors)) {
        $checkAvailabilitySql = "SELECT 1 FROM dbProj_Reservation 
                                 WHERE hallId = ? 
                                   AND startDate <= DATE_ADD(?, INTERVAL ? DAY) 
                                   AND endDate >= ?
                                   AND timingSlotStart <= ?
                                   AND timingSlotEnd >= ?";
        $stmt = $db->prepare($checkAvailabilitySql);
        $stmt->bind_param("isssss", $hallId, $startDate, $duration, $startDate, $timingSlotStart, $timingSlotEnd);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "The hall is not available for the selected date and time.";
        }
    }

    // If no errors, proceed to the confirmation page
    if (empty($errors)) {
        header("Location: Confirm_Booking.php?hallId=$hallId&hallName=$hallName&startDate=$startDate&duration=$duration&numberOfAudience=$numberOfAudience&time=$time&hallImage=$hallImage&rentalDetails=$rentalDetails&timingSlotStart=$timingSlotStart&timingSlotEnd=$timingSlotEnd");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Hall</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .form-section { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; }
        input, select { margin-bottom: 10px; width: 100%; padding: 8px; }
        .form-buttons { margin-top: 20px; }
        .form-buttons input {
            padding: 10px 20px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-buttons input:hover {
            background-color: red;
        }
        .error-message { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <?php include 'header.html'; ?>

    <div class="booking_section layout_padding">
        <div class="container">
            <h1>Book Hall: <?php echo htmlspecialchars($hallName); ?></h1>
            <form id="bookingForm" method="post" action="">
                <div class="form-section">
                    <?php if (!empty($errors)): ?>
                        <div class="error-message">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <label for="hallName">Hall Name:</label>
                    <input type="text" id="hallName" name="hallName" value="<?php echo htmlspecialchars($hallName); ?>" readonly>

                    <label for="time">Available Time:</label>
                    <?php if (!empty($time12Start) && !empty($time12End)): ?>
                        <input type="text" id="time" name="time" value="<?php echo htmlspecialchars($time12Start . ' - ' . $time12End); ?>" readonly>
                    <?php else: ?>
                        <select id="time" name="time">
                            <option value="">Select Time</option>
                            <?php foreach ($timeSlots as $slot): ?>
                                <option value="<?php echo htmlspecialchars($slot['start'] . ' - ' . $slot['end']); ?>">
                                    <?php echo htmlspecialchars($slot['start'] . ' - ' . $slot['end']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="startDate" value="<?php echo htmlspecialchars($startDate); ?>" min="<?php echo date('Y-m-d'); ?>">

                    <label for="duration">Duration:</label>
                    <select id="duration" name="duration">
                        <option value="">Select Duration</option>
                        <option value="1" <?php echo ($duration == '1') ? 'selected' : ''; ?>>1 Day</option>
                        <option value="7" <?php echo ($duration == '7') ? 'selected' : ''; ?>>1 Week</option>
                        <option value="15" <?php echo ($duration == '15') ? 'selected' : ''; ?>>15 Days</option>
                    </select>

                    <label for="numberOfAudience">Number of Audience:</label>
                    <input type="number" id="numberOfAudience" name="numberOfAudience" value="<?php echo htmlspecialchars($numberOfAudience); ?>" min="0">
                </div>
                <div class="form-buttons">
                    <input type="hidden" name="hallId" value="<?php echo htmlspecialchars($hallId); ?>">
                    <input type="hidden" name="hallImage" value="<?php echo htmlspecialchars($hallImage); ?>">
                    <input type="hidden" name="rentalDetails" value="<?php echo htmlspecialchars($rentalDetails); ?>">
                    <input type="hidden" name="timingSlotStart" value="<?php echo htmlspecialchars($timingSlotStart); ?>">
                    <input type="hidden" name="timingSlotEnd" value="<?php echo htmlspecialchars($timingSlotEnd); ?>">
                    <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
                    <input type="submit" value="Continue">
                    <input type="button" value="Cancel" onclick="window.location.href='searchAndBooking.php';">
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.html'; ?>
</body>
</html>
