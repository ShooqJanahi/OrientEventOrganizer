<?php
include 'Database.php'; // Include your Database class

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to cancel reservation
function cancelReservation($reservationId) {
    $db = Database::getInstance();
    $sql = "CALL CancelReservation(?, @message)";
    $stmt = $db->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $reservationId);
        if (!$stmt->execute()) {
            return "Execution failed: " . $stmt->error;
        }
        $stmt->close();

        // Fetch the output message
        $result = $db->querySQL("SELECT @message AS message");
        if ($result) {
            $row = $result->get_result()->fetch_assoc();
            return $row['message'];
        } else {
            return "Failed to fetch message: " . $db->dblink->error;
        }
    } else {
        return "Failed to prepare statement: " . $db->dblink->error;
    }
}

if (isset($_POST['confirm_cancel'])) {
    $reservationId = $_POST['reservationId'];
    $message = cancelReservation($reservationId);
    if ($message) {
        echo $message;
    } else {
        echo "Error canceling reservation.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cancel Reservation</title>
</head>
<body>
    <h2>Cancel Reservation</h2>

    <?php if (isset($_POST['reservationId'])) { ?>
        <form action="" method="post">
            <input type="hidden" name="reservationId" value="<?php echo $_POST['reservationId']; ?>">
            <p>Are you sure you want to cancel the reservation?</p>
            <input type="submit" name="confirm_cancel" value="Yes, Cancel Reservation">
            <a href="manage_reservation.php">No, Go Back</a>
        </form>
    <?php } ?>
</body>
</html>
