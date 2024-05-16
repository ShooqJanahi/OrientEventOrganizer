<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Hall</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .form-section { margin-bottom: 20px; }
        label { margin-right: 10px; }
        input, select { margin-right: 20px; }
        .form-buttons { margin-top: 20px; }
        .form-buttons input { padding: 10px 20px; margin-right: 10px; }
    </style>
</head>
<body>
    <?php include 'header.html'; ?>

    <div class="booking_section layout_padding">
        <div class="container">
            <h1>Book a Hall</h1>
            <form id="bookingForm">
                <div class="form-section">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>" required>
                    <label for="duration">Duration:</label>
                    <select id="duration" name="duration" required>
                        <option value="">Select Duration</option>
                        <option value="1" <?php echo (isset($_GET['duration']) && $_GET['duration'] == '1') ? 'selected' : ''; ?>>1 Day</option>
                        <option value="7" <?php echo (isset($_GET['duration']) && $_GET['duration'] == '7') ? 'selected' : ''; ?>>1 Week</option>
                        <option value="15" <?php echo (isset($_GET['duration']) && $_GET['duration'] == '15') ? 'selected' : ''; ?>>15 Days</option>
                    </select>
                    <label for="end_date">End Date:</label>
                    <input type="text" id="end_date" name="end_date" readonly>
                </div>
                <div class="form-section">
                    <label for="audience">Number of Audience:</label>
                    <input type="number" id="audience" name="audience" value="<?php echo isset($_GET['audience']) ? htmlspecialchars($_GET['audience']) : ''; ?>" required>
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" value="<?php echo isset($_GET['time']) ? htmlspecialchars($_GET['time']) : ''; ?>" required>
                </div>
                <div class="form-section">
                    <label for="hall">Hall:</label>
                    <input type="text" id="hall" name="hall" value="<?php echo isset($_GET['hallId']) ? htmlspecialchars($_GET['hallId']) : ''; ?>" readonly>
                </div>
                <div class="form-buttons">
                    <input type="button" id="proceedButton" value="Proceed">
                    <input type="button" value="Cancel" onclick="window.history.back();">
                </div>
            </form>
            <div id="result"></div>
        </div>
    </div>

    <script>
        function calculateEndDate() {
            const startDate = document.getElementById('start_date').value;
            const duration = document.getElementById('duration').value;
            if (startDate && duration) {
                fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'calculate_end_date',
                        start_date: startDate,
                        duration: duration
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('end_date').value = data.end_date;
                    } else {
                        console.error('Error calculating end date');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        document.getElementById('duration').addEventListener('change', calculateEndDate);
        document.getElementById('start_date').addEventListener('change', calculateEndDate);

        document.getElementById('proceedButton').addEventListener('click', function() {
            const form = document.getElementById('bookingForm');
            const formData = new FormData(form);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('result').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
        });
    </script>

    <?php include 'footer.html'; ?>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if it's an AJAX request for end date calculation
    if (isset($_POST['action']) && $_POST['action'] == 'calculate_end_date') {
        $startDate = $_POST['start_date'];
        $duration = $_POST['duration'];

        if ($startDate && $duration) {
            $endDate = new DateTime($startDate);
            $endDate->modify("+$duration days");
            echo json_encode(['success' => true, 'end_date' => $endDate->format('Y-m-d')]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    // Booking form submission handling
    if (isset($_POST['proceed'])) {
        // Enable error reporting for debugging
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // Include necessary files
        require_once 'Database.php';
        require_once 'Search.php';

        // Get the input values
        $startDate = $_POST['start_date'];
        $duration = $_POST['duration'];
        $endDate = $_POST['end_date'];
        $audience = $_POST['audience'];
        $time = $_POST['time'];
        $hallId = $_POST['hall'];

        // Debugging: Print form data
        echo '<pre>';
        print_r($_POST);
        echo '</pre>';

        // Hardcode the client ID for testing
        $clientId = 1;

        // Validate input
        if (empty($startDate) || empty($duration) || empty($endDate) || empty($audience) || empty($time) || empty($hallId)) {
            die("All fields are required.");
        }

        // Create an instance of the Search class
        $searchClass = new Search();

        try {
            // Check availability
            $availability = $searchClass->searchHalls($startDate, $duration, $audience, $time, null);
            if (empty($availability)) {
                // Hall is available, proceed with booking
                $db = Database::getInstance();
                $dblink = $db->dblink;

                // Start transaction
                $dblink->begin_transaction();

                // Insert new event
                $eventSql = "INSERT INTO dbProj_event (eventType, numberOfAudiance, numberOFDays) VALUES ('Custom Event', ?, ?)";
                $eventStmt = $dblink->prepare($eventSql);
                if (!$eventStmt) {
                    throw new Exception("Event prepare failed: (" . $dblink->errno . ") " . $dblink->error);
                }
                if (!$eventStmt->bind_param('ii', $audience, $duration)) {
                    throw new Exception("Event binding parameters failed: (" . $eventStmt->errno . ") " . $eventStmt->error);
                }
                if (!$eventStmt->execute()) {
                    throw new Exception("Event execute failed: (" . $eventStmt->errno . ") " . $eventStmt->error);
                }
                $eventId = $eventStmt->insert_id;
                $eventStmt->close();

                // Insert new reservation
                $reservationSql = "INSERT INTO dbProj_Reservation (reservationDate, startDate, endDate, timingID, totalCost, discountRate, clientId, hallId, eventId)
                                   VALUES (NOW(), ?, ?, ?, NULL, NULL, ?, ?, ?)";
                $reservationStmt = $dblink->prepare($reservationSql);
                if (!$reservationStmt) {
                    throw new Exception("Reservation prepare failed: (" . $dblink->errno . ") " . $dblink->error);
                }
                if (!$reservationStmt->bind_param('sssiii', $startDate, $endDate, $time, $clientId, $hallId, $eventId)) {
                    throw new Exception("Reservation binding parameters failed: (" . $reservationStmt->errno . ") " . $reservationStmt->error);
                }
                if (!$reservationStmt->execute()) {
                    throw new Exception("Reservation execute failed: (" . $reservationStmt->errno . ") " . $reservationStmt->error);
                }
                $reservationStmt->close();

                // Commit transaction
                $dblink->commit();

                echo "Booking successful.";
            } else {
                echo "The hall is not available for the selected date and time.";
            }
        } catch (Exception $e) {
            // Rollback transaction in case of error
            $dblink->rollback();
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
