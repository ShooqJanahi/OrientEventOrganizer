<?php
include 'Database.php'; // Include your Database class

// Function to fetch reservation details
function fetchReservationDetails($reservationId) {
    $db = Database::getInstance();
    $sql = "SELECT * FROM dbProj_Reservation WHERE reservationId = ?";
    return $db->singleFetch($sql, [$reservationId]);
}

// Function to update reservation
function updateReservation($reservationId, $startDate, $endDate, $totalCost) {
    $db = Database::getInstance();
    $sql = "CALL UpdateReservation(?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("issd", $reservationId, $startDate, $endDate, $totalCost);
        $stmt->execute();
        $stmt->bind_result($updatedTotalCost);
        $stmt->fetch();
        $stmt->close();
        return $updatedTotalCost;
    }
    return false;
}

// Function to cancel reservation
function cancelReservation($reservationId) {
    $db = Database::getInstance();
    $sql = "CALL CancelReservation(?)";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $reservationId);
        $stmt->execute();
        $stmt->bind_result($message);
        $stmt->fetch();
        $stmt->close();
        return $message;
    } else {
        return "Failed to prepare statement: " . $db->dblink->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Reservation</title>
</head>
<body>
    <h2>Manage Reservation</h2>
    <form action="" method="post">
        <label for="reservationId">Reservation ID:</label>
        <input type="number" id="reservationId" name="reservationId" required><br><br>
        <input type="submit" name="fetch_details" value="Fetch Reservation Details">
    </form>

    <?php
    if (isset($_POST['fetch_details'])) {
        $reservationId = $_POST['reservationId'];
        $reservation = fetchReservationDetails($reservationId);

        if ($reservation) {
            echo "<h3>Reservation Details</h3>";
            echo "Reservation ID: " . $reservation->reservationId . "<br>";
            echo "Start Date: " . $reservation->startDate . "<br>";
            echo "End Date: " . $reservation->endDate . "<br>";
            echo "Total Cost: " . $reservation->totalCost . "<br>";
            
            echo '<form action="" method="post">';
            echo '<input type="hidden" name="reservationId" value="' . $reservationId . '">';
            echo '<label for="startDate">Start Date:</label>';
            echo '<input type="date" id="startDate" name="startDate" value="' . $reservation->startDate . '" required><br><br>';
            echo '<label for="endDate">End Date:</label>';
            echo '<input type="date" id="endDate" name="endDate" value="' . $reservation->endDate . '" required><br><br>';
            echo '<label for="totalCost">Total Cost:</label>';
            echo '<input type="number" id="totalCost" name="totalCost" step="0.01" value="' . $reservation->totalCost . '" required><br><br>';
            echo '<input type="submit" name="update_reservation" value="Update Reservation">';
            echo '<input type="submit" name="cancel_reservation" value="Cancel Reservation">';
            echo '</form>';
        } else {
            echo "No reservation found with ID " . $reservationId;
        }
    }

    if (isset($_POST['update_reservation'])) {
        $reservationId = $_POST['reservationId'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $totalCost = $_POST['totalCost'];

        $updatedTotalCost = updateReservation($reservationId, $startDate, $endDate, $totalCost);
        if ($updatedTotalCost !== false) {
            echo "Reservation updated successfully. New total cost: " . $updatedTotalCost;
        } else {
            echo "Error updating reservation.";
        }
    }

    if (isset($_POST['cancel_reservation'])) {
        $reservationId = $_POST['reservationId'];

        $message = cancelReservation($reservationId);
        if ($message !== false) {
            echo $message;
        } else {
            echo "Error canceling reservation.";
        }
    }
    ?>
</body>
</html>
