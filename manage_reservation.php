<?php
include 'Database.php'; // Include your Database class

// Function to fetch reservation details
function fetchReservationDetails($reservationId) {
    $db = Database::getInstance();
    $sql = "SELECT * FROM dbProj_Reservation WHERE reservationId = ?";
    return $db->singleFetch($sql, [$reservationId]);
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
        <input type="submit" name="fetch_details" value="Get Details">
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
            
            echo '<form action="update_reservation.php" method="post" style="display:inline-block">';
            echo '<input type="hidden" name="reservationId" value="' . $reservationId . '">';
            echo '<input type="submit" value="Update Reservation">';
            echo '</form>';
            
            echo '<form action="cancel_reservation.php" method="post" style="display:inline-block">';
            echo '<input type="hidden" name="reservationId" value="' . $reservationId . '">';
            echo '<input type="submit" value="Cancel Reservation">';
            echo '</form>';
        } else {
            echo "No reservation found with ID " . $reservationId;
        }
    }
    ?>
</body>
</html>
