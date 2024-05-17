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

$sql = "SELECT h.hallId, h.hallName, h.location, h.capacity, h.description, h.image, h.rentalCharge, t.timingSlotStart, t.timingSlotEnd
        FROM dbProj_Hall h
        JOIN dpProj_HallsTimingSlots t ON h.hallId = t.hallId
        WHERE 1=1";

if (!empty($numberOfAudience)) {
    $sql .= " AND h.capacity >= $numberOfAudience";
}
if (!empty($searchTerm)) {
    $sql .= " AND (h.hallName LIKE '%$searchTerm%' OR h.description LIKE '%$searchTerm%')";
}
if (!empty($selectDate) && !empty($duration)) {
    $sql .= " AND NOT EXISTS (
              SELECT 1
              FROM dbProj_Reservation r
              WHERE r.hallId = h.hallId
                AND r.timingID = t.timingID
                AND r.startDate <= DATE_ADD('$selectDate', INTERVAL $duration DAY)
                AND r.endDate >= '$selectDate'
          )";
}
if (!empty($time24)) {
    $sql .= " AND '$time24' BETWEEN t.timingSlotStart AND t.timingSlotEnd";
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
            padding: 10px 20px;
            margin-right: 10px;
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
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; color: black; }
        th { background-color: #f8f8f8; text-align: center; }
        td img { max-width: 100px; height: auto; display: block; margin: 0 auto; }
        .select-button {
            display: inline-block;
            padding: 8px 16px;
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
        .hidden-column {
            display: none;
        }
    </style>
    <script>
        function toggleRentalDetails() {
            var rentalDetailsCells = document.getElementsByClassName('rental-details-cell');
            var rentalDetailsHeader = document.getElementById('rental-details-header');

            rentalDetailsHeader.classList.remove('hidden-column');
            for (var i = 0; i < rentalDetailsCells.length; i++) {
                rentalDetailsCells[i].classList.remove('hidden-column');
            }
        }

        window.onload = function() {
            var searchForm = document.getElementById('searchForm');
            searchForm.addEventListener('submit', function() {
                toggleRentalDetails();
            });
        }
    </script>
</head>
<body>
    <?php include 'header.html'; ?>

    <div class="search_section layout_padding">
        <div class="container">
            <h1>Search for Halls</h1>
            <form id="searchForm" method="get" action="searchAndBooking.php">
                <div class="form-section">
                    <label for="numberOfAudience">Number of Audience:</label>
                    <input type="number" id="numberOfAudience" name="numberOfAudience" value="<?php echo htmlspecialchars($numberOfAudience); ?>">
                    <label for="selectDate">Select Date:</label>
                    <input type="date" id="selectDate" name="selectDate" value="<?php echo htmlspecialchars($selectDate); ?>">
                    <select id="duration" name="duration">
                        <option value="">Select Duration</option>
                        <option value="1" <?php echo ($duration == '1') ? 'selected' : ''; ?>>1 Day</option>
                        <option value="7" <?php echo ($duration == '7') ? 'selected' : ''; ?>>1 Week</option>
                        <option value="15" <?php echo ($duration == '15') ? 'selected' : ''; ?>>15 Days</option>
                    </select>
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($time); ?>">
                </div>
                <div class="form-section">
                    <label for="searchTerm">Search by Name/Description:</label>
                    <input type="text" id="searchTerm" name="searchTerm" value="<?php echo htmlspecialchars($searchTerm); ?>">
                </div>
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
                        <th>Available Time</th>
                        <th id="rental-details-header" class="<?php echo empty($duration) ? 'hidden-column' : ''; ?>">Rental Details</th>
                        <th>Action</th>
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
                                <td><?php echo convertTo12HourFormat($hall->timingSlotStart); ?> - <?php echo convertTo12HourFormat($hall->timingSlotEnd); ?></td>
                                <td class="rental-details-cell <?php echo empty($duration) ? 'hidden-column' : ''; ?>"><?php echo htmlspecialchars($totalRentalDetails); ?></td>
                                <td><a class="select-button" href="booking_form.php?hallId=<?php echo $hall->hallId; ?>&date=<?php echo $selectDate; ?>&duration=<?php echo $duration; ?>&audience=<?php echo $numberOfAudience; ?>&time=<?php echo convertTo12HourFormat($hall->timingSlotStart) . ' - ' . convertTo12HourFormat($hall->timingSlotEnd); ?>&rentalDetails=<?php echo $totalRentalDetails; ?>&availableTime=<?php echo convertTo12HourFormat($hall->timingSlotStart) . ' - ' . convertTo12HourFormat($hall->timingSlotEnd); ?>">Select</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No halls available for the selected criteria.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include 'footer.html'; ?>
</body>
</html>
