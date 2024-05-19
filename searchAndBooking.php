<?php
// Include the Database class
include 'Database.php';
$db = Database::getInstance();

// Get search criteria from query string
$numberOfAudience = isset($_GET['numberOfAudience']) ? $_GET['numberOfAudience'] : '';
$selectDate = isset($_GET['selectDate']) ? $_GET['selectDate'] : '';
$duration = isset($_GET['duration']) ? $_GET['duration'] : '';
$time = isset($_GET['time']) ? $_GET['time'] : '';
$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

// Convert 12-hour format time to 24-hour format
function convertTo24HourFormat($time) {
    return date("H:i:s", strtotime($time));
}

// Convert the input time to 24-hour format for comparison
$time24 = !empty($time) ? convertTo24HourFormat($time) : '';

$sql = "SELECT h.hallId, h.hallName, h.location, h.capacity, h.description, h.image, h.rentalCharge";

if (!empty($time24)) {
    $sql .= ", t.timingSlotStart, t.timingSlotEnd";
}

$sql .= " FROM dbProj_Hall h";

if (!empty($time24)) {
    $sql .= " JOIN dpProj_HallsTimingSlots t ON h.hallId = t.hallId";
}

$sql .= " WHERE 1=1";

if (!empty($numberOfAudience)) {
    $sql .= " AND h.capacity >= $numberOfAudience";
}
if (!empty($searchTerm)) {
    $sql .= " AND MATCH(h.hallName, h.description) AGAINST ('$searchTerm' IN NATURAL LANGUAGE MODE)";
}

$alternativeTimeSlots = [];
if (!empty($selectDate) && !empty($duration)) {
    $sql .= " AND NOT EXISTS (
              SELECT 1
              FROM dbProj_Reservation r
              WHERE r.hallId = h.hallId
                AND r.startDate <= DATE_ADD('$selectDate', INTERVAL $duration DAY)
                AND r.endDate >= '$selectDate'
          )";

    if (!empty($time24)) {
        $sql .= " AND '$time24' BETWEEN t.timingSlotStart AND t.timingSlotEnd";

        // Check if the chosen time is available
        $availabilityCheckSql = "SELECT 1 FROM dbProj_Reservation r
                                 WHERE r.hallId = h.hallId
                                   AND r.startDate <= DATE_ADD('$selectDate', INTERVAL $duration DAY)
                                   AND r.endDate >= '$selectDate'
                                   AND ('$time24' BETWEEN t.timingSlotStart AND t.timingSlotEnd)";
        $result = $db->multiFetch($availabilityCheckSql);
        if (empty($result)) {
            // Fetch alternative time slots if the chosen time is not available
            $alternativeTimeSlotsSql = "SELECT t.timingSlotStart, t.timingSlotEnd FROM dpProj_HallsTimingSlots t
                                        WHERE t.hallId = h.hallId
                                          AND NOT EXISTS (
                                              SELECT 1 FROM dbProj_Reservation r
                                              WHERE r.hallId = t.hallId
                                                AND r.startDate <= DATE_ADD('$selectDate', INTERVAL $duration DAY)
                                                AND r.endDate >= '$selectDate'
                                          )";
            $alternativeTimeSlots = $db->multiFetch($alternativeTimeSlotsSql);
        }
    }
}

$halls = $db->multiFetch($sql);

// Convert time to 12-hour format
function convertTo12HourFormat($time) {
    return date("g:i A", strtotime($time));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search for Halls</title>
    <style>
        body { font-family: Arial, sans-serif; color: black; }
        .form-section { margin-bottom: 20px; }
        label { margin-right: 10px; color: black; }
        input, select { margin-right: 20px; color: black; }
        .form-buttons { margin-top: 20px; }
        .form-buttons input {
            padding: 5px 10px; /* Reduced padding for smaller height */
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
            text-transform: uppercase;
            cursor: pointer;
        }
        .form-buttons input:hover {
            background-color: red;
            color: white;
        }
        .error-message { color: red; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; color: black; }
        th { background-color: #f8f8f8; text-align: center; }
        td img { max-width: 100px; height: auto; display: block; margin: 0 auto; }
        .select-button {
            display: inline-block;
            padding: 5px 10px; /* Reduced padding for smaller height */
            font-size: 14px;
            color: white;
            background-color: black;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
        }
        .select-button:hover {
            background-color: red;
            color: white;
        }
        .select-button:disabled {
            background-color: grey;
            cursor: not-allowed;
        }
        .hidden-column {
            display: none;
        }
        th.action-column, td.action-column { /* Ensure the Action column is wide enough */
            width: 150px; /* Adjust as necessary */
        }
    </style>
    <script>
        function validateSearchForm() {
            var numberOfAudience = document.getElementById('numberOfAudience').value;
            var selectDate = document.getElementById('selectDate').value;
            var time = document.getElementById('time').value;
            var duration = document.getElementById('duration').value;
            var errorMessage = document.getElementById('errorMessage');
            var durationErrorMessage = document.getElementById('durationErrorMessage');

            // Check if start date is entered without duration
            if (selectDate && !duration) {
                durationErrorMessage.textContent = 'Please select a duration when a start date is entered.';
                document.getElementById('duration').focus();
                return false;
            }

            errorMessage.textContent = '';
            durationErrorMessage.textContent = '';
            return true;
        }

        function checkRequiredFields() {
            var numberOfAudience = document.getElementById('numberOfAudience').value;
            var selectDate = document.getElementById('selectDate').value;
            var time = document.getElementById('time').value;
            var duration = document.getElementById('duration').value;
            var searchTerm = document.getElementById('searchTerm').value;
            var bookButtons = document.getElementsByClassName('select-button');

            var allFieldsFilled = numberOfAudience || selectDate || time || duration || searchTerm;

            for (var i = 0; i < bookButtons.length; i++) {
                bookButtons[i].disabled = !allFieldsFilled;
            }
        }

        window.onload = function() {
            checkRequiredFields(); // Initial check

            document.getElementById('numberOfAudience').addEventListener('input', checkRequiredFields);
            document.getElementById('selectDate').addEventListener('input', checkRequiredFields);
            document.getElementById('time').addEventListener('input', checkRequiredFields);
            document.getElementById('duration').addEventListener('input', checkRequiredFields);
            document.getElementById('searchTerm').addEventListener('input', checkRequiredFields);
        }
    </script>
</head>
<body>
    <?php include 'header.html'; ?>

    <div class="search_section layout_padding">
        <div class="container">
            <h1>Search for Halls</h1>
            <form id="searchForm" method="get" action="searchAndBooking.php" onsubmit="return validateSearchForm();">
                <div class="form-section">
                    <label for="numberOfAudience">Number of Audience:</label>
                    <input type="number" id="numberOfAudience" name="numberOfAudience" value="<?php echo htmlspecialchars($numberOfAudience); ?>" min="0">
                    <label for="selectDate">Select Date:</label>
                    <input type="date" id="selectDate" name="selectDate" value="<?php echo htmlspecialchars($selectDate); ?>" min="<?php echo date('Y-m-d'); ?>">
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($time); ?>">
                    <select id="duration" name="duration">
                        <option value="">Select Duration</option>
                        <option value="1" <?php echo ($duration == '1') ? 'selected' : ''; ?>>1 Day</option>
                        <option value="7" <?php echo ($duration == '7') ? 'selected' : ''; ?>>1 Week</option>
                        <option value="15" <?php echo ($duration == '15') ? 'selected' : ''; ?>>15 Days</option>
                    </select>
                    <div id="durationErrorMessage" class="error-message"></div>
                </div>
                <div class="form-section">
                    <label for="searchTerm">Search by Name/Description:</label>
                    <input type="text" id="searchTerm" name="searchTerm" value="<?php echo htmlspecialchars($searchTerm); ?>">
                </div>
                <div id="errorMessage" class="error-message"></div>
                <div class="form-buttons">
                    <input type="submit" value="Search">
                </div>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Hall Name</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Description</th>
                        <th>Picture</th>
                        <?php if (!empty($time24)) : ?>
                            <th>Available Time</th>
                        <?php endif; ?>
                        <th id="rental-details-header" class="<?php echo empty($duration) ? 'hidden-column' : ''; ?>">Rental Details</th>
                        <th class="action-column">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($halls): ?>
                        <?php foreach ($halls as $hall): ?>
                            <?php
                            $timingSlotStart = new DateTime($hall->timingSlotStart);
                            $timingSlotEnd = new DateTime($hall->timingSlotEnd);
                            $interval = $timingSlotStart->diff($timingSlotEnd);
                            $hours = $interval->h + ($interval->days * 24);
                            $rentalDetails = $hours * $hall->rentalCharge;
                            $totalRentalDetails = $rentalDetails * $duration;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($hall->hallName); ?></td>
                                <td><?php echo htmlspecialchars($hall->location); ?></td>
                                <td><?php echo htmlspecialchars($hall->capacity); ?></td>
                                <td><?php echo htmlspecialchars($hall->description); ?></td>
                                <td><img src="<?php echo htmlspecialchars($hall->image); ?>" alt="<?php echo htmlspecialchars($hall->hallName); ?>"></td>
                                <?php if (!empty($time24)) : ?>
                                    <td><?php echo convertTo12HourFormat($hall->timingSlotStart); ?> - <?php echo convertTo12HourFormat($hall->timingSlotEnd); ?></td>
                                <?php endif; ?>
                                <td class="rental-details-cell <?php echo empty($duration) ? 'hidden-column' : ''; ?>"><?php echo htmlspecialchars($totalRentalDetails); ?></td>
                                <td class="action-column">
                                   <form method="get" action="booking_form.php">
    <input type="hidden" name="hallId" value="<?php echo htmlspecialchars($hall->hallId); ?>">
    <input type="hidden" name="hallName" value="<?php echo htmlspecialchars($hall->hallName); ?>">
    <input type="hidden" name="startDate" value="<?php echo htmlspecialchars($selectDate); ?>">
    <input type="hidden" name="duration" value="<?php echo htmlspecialchars($duration); ?>">
    <input type="hidden" name="numberOfAudience" value="<?php echo htmlspecialchars($numberOfAudience); ?>">
    <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
    <input type="hidden" name="hallImage" value="<?php echo htmlspecialchars($hall->image); ?>">
    <input type="hidden" name="rentalDetails" value="<?php echo htmlspecialchars($totalRentalDetails); ?>">
    <input type="hidden" name="timingSlotStart" value="<?php echo htmlspecialchars($hall->timingSlotStart); ?>">
    <input type="hidden" name="timingSlotEnd" value="<?php echo htmlspecialchars($hall->timingSlotEnd); ?>">
    <input type="submit" class="select-button" value="Book an Event">
</form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No halls available for the selected criteria.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <?php if (!empty($alternativeTimeSlots)): ?>
                <h2>Recommended Alternative Time Slots:</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Hall Name</th>
                            <th>Alternative Time Slot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alternativeTimeSlots as $slot): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($hall->hallName); ?></td>
                                <td><?php echo convertTo12HourFormat($slot['timingSlotStart']) . ' - ' . convertTo12HourFormat($slot['timingSlotEnd']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        </div>
    </div>

    <?php include 'footer.html'; ?>
</body>
</html>
