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

// If start date, duration, and number of audience are not provided, set to empty
$startDate = !empty($startDate) ? $startDate : '';
$duration = !empty($duration) ? $duration : '';
$numberOfAudience = !empty($numberOfAudience) ? $numberOfAudience : '';

function convertTo12HourFormat($time) {
    return date("g:i A", strtotime($time));
}

$time12 = !empty($time) ? convertTo12HourFormat($time) : '';

// Fetch available time slots for the hall
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
    </style>
</head>
<body>
    <?php include 'header.html'; ?>

    <div class="booking_section layout_padding">
        <div class="container">
            <h1>Book Hall: <?php echo htmlspecialchars($hallName); ?></h1>
            <form id="bookingForm" method="post" action="Confirm_Booking.php">
                <div class="form-section">
                    <label for="hallName">Hall Name:</label>
                    <input type="text" id="hallName" name="hallName" value="<?php echo htmlspecialchars($hallName); ?>" readonly>

                    <label for="time">Available Time:</label>
                    <?php if (!empty($time12)): ?>
                        <input type="text" id="time" name="time" value="<?php echo htmlspecialchars($time12); ?>" readonly>
                    <?php else: ?>
                        <select id="time" name="time" required>
                            <option value="">Select Time</option>
                            <?php foreach ($timeSlots as $slot): ?>
                                <option value="<?php echo htmlspecialchars($slot['start'] . ' - ' . $slot['end']); ?>">
                                    <?php echo htmlspecialchars($slot['start'] . ' - ' . $slot['end']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="startDate" value="<?php echo htmlspecialchars($startDate); ?>" min="<?php echo date('Y-m-d'); ?>" required>

                    <label for="duration">Duration:</label>
                    <select id="duration" name="duration" required>
                        <option value="">Select Duration</option>
                        <option value="1" <?php echo ($duration == '1') ? 'selected' : ''; ?>>1 Day</option>
                        <option value="7" <?php echo ($duration == '7') ? 'selected' : ''; ?>>1 Week</option>
                        <option value="15" <?php echo ($duration == '15') ? 'selected' : ''; ?>>15 Days</option>
                    </select>

                    <label for="numberOfAudience">Number of Audience:</label>
                    <input type="number" id="numberOfAudience" name="numberOfAudience" value="<?php echo htmlspecialchars($numberOfAudience); ?>" min="0" required>
                </div>
                <div class="form-buttons">
                    <input type="hidden" name="hallId" value="<?php echo htmlspecialchars($hallId); ?>">
                    <input type="hidden" name="hallImage" value="<?php echo htmlspecialchars($hallImage); ?>">
                    <input type="hidden" name="rentalDetails" value="<?php echo htmlspecialchars($rentalDetails); ?>">
                        <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
    <input type="hidden" name="timingSlotStart" value="<?php echo htmlspecialchars($hall->timingSlotStart); ?>">
    <input type="hidden" name="timingSlotEnd" value="<?php echo htmlspecialchars($hall->timingSlotEnd); ?>">
                    <input type="submit" value="Continue">
                    <input type="button" value="Cancel" onclick="window.location.href='searchAndBooking.php';">
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.html'; ?>
</body>
</html>
