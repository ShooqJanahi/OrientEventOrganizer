<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Login.php';
include 'Client.php';

$login = new Login(); // Initialize the login system

if (!$login->check_session()) {
    header('Location: LoginForm.php');
    exit();
}

// Get the user ID from the session
$userId = $_SESSION['userId'];

// Fetch client data using user ID
$client = new Client();
$clientData = $client->initWithUserId($userId); // Use initWithUserId to fetch client data by user ID
$clientId = $client->getClientId();

// If no client data is found, redirect to login
if (!$clientData || is_null($clientId)) {
    header('Location: LoginForm.php');
    exit();
}

// Fetch reservations for the client
$db = Database::getInstance();
$sql = "SELECT * FROM dbProj_Reservation WHERE clientId = ?";
$params = [$clientId];
$reservations = $db->multiFetch($sql, $params);

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
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation->reservationId); ?></td>
                        <td><?php echo htmlspecialchars($reservation->reservationDate); ?></td>
                        <td><?php echo htmlspecialchars($reservation->startDate); ?></td>
                        <td><?php echo htmlspecialchars($reservation->endDate); ?></td>
                        <td><?php echo htmlspecialchars($reservation->totalCost); ?></td>
                        <td><?php echo htmlspecialchars($reservation->discountRate); ?></td>
                        <td><?php echo htmlspecialchars($reservation->hallId); ?></td>
                        <td><?php echo htmlspecialchars($reservation->eventId); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include 'footer.html'; ?>
</body>
</html>

<?php
$db->close();
?>
