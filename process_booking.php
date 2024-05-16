<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
require_once 'Database.php';
require_once 'Search.php';

// Start session to get client ID
//session_start();

// Debugging: Check if session is working and client ID is set
/*if (!isset($_SESSION['client_id'])) {
    die('Client ID not set in session.');
}*/

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Get the client ID from the session
    $clientId = $_SESSION['client_id'];

    // Validate input
    if (empty($startDate) || empty($duration) || empty($endDate) || empty($audience) || empty($time) || empty($hallId) || empty($clientId)) {
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
            $sql = "INSERT INTO dbProj_Reservation (reservationDate, startDate, endDate, timingID, totalCost, discountRate, clientId, hallId, eventId)
                    VALUES (NOW(), ?, ?, ?, NULL, NULL, 1, ?, NULL)";
            $stmt = $db->dblink->prepare($sql);
            if (!$stmt) {
                die("Prepare failed: (" . $db->dblink->errno . ") " . $db->dblink->error);
            }
            if (!$stmt->bind_param('ssiii', $startDate, $endDate, $time, $clientId, $hallId)) {
                die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            if (!$stmt->execute()) {
                die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            $stmt->close();
            echo "Booking successful.";
        } else {
            echo "The hall is not available for the selected date and time.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
