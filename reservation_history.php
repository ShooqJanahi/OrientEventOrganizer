<?php
session_start();
include 'Login.php';

$login = new Login(); // Initialize the login system

if (!$login->check_session()) {
    header('Location: LoginForm.php');
    exit();
}

// Get the user ID from the session
$userId = $_SESSION['userId'];

// Database connection
include 'database.php';

// Fetch client ID using user ID
$clientQuery = $conn->prepare("SELECT clientId FROM dbProj_Client WHERE userId = ?");
$clientQuery->bind_param("i", $userId);
$clientQuery->execute();
$clientResult = $clientQuery->get_result();
$client = $clientResult->fetch_assoc();
$clientId = $client['clientId'];

// Fetch reservations for the client
$reservationQuery = $conn->prepare("SELECT * FROM dbProj_Reservation WHERE clientId = ?");
$reservationQuery->bind_param("i", $clientId);
$reservationQuery->execute();
$reservationResult = $reservationQuery->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reservation History</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'header.html'; ?>
    <div class="container">
        <h1>Reservation History</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Reservation Date</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Cost</th>
                    <th>Discount Rate</th>
                    <th>Hall ID</th>
                    <th>Event ID</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reservation = $reservationResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $reservation['reservationId']; ?></td>
                        <td><?php echo $reservation['reservationDate']; ?></td>
                        <td><?php echo $reservation['startDate']; ?></td>
                        <td><?php echo $reservation['endDate']; ?></td>
                        <td><?php echo $reservation['totalCost']; ?></td>
                        <td><?php echo $reservation['discountRate']; ?></td>
                        <td><?php echo $reservation['hallId']; ?></td>
                        <td><?php echo $reservation['eventId']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include 'footer.html'; ?>
</body>
</html>

<?php
$conn->close();
?>
