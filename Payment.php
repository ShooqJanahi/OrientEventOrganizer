<?php
session_start();
include 'Database.php';
include 'Client.php';

// Retrieve reservation details
$hallId = $_POST['hallId'];
$hallName = $_POST['hallName'];
$startDate = $_POST['start_date'];
$duration = $_POST['duration'];
$endDate = $_POST['end_date'];
$audience = $_POST['audience'];
$time = $_POST['time'];
$hallImage = $_POST['hallImage'];
$rentalDetails = $_POST['rentalDetails'];
$totalPrice = $_POST['totalPrice'];
$discountedPrice = $_POST['discountedPrice'];
$clientId = isset($_POST['clientId']) ? $_POST['clientId'] : null;
$clientStatus = isset($_POST['clientStatus']) ? $_POST['clientStatus'] : null;
$companyName = $_POST['companyName'];

// Insert event and reservation details into the database
$db = Database::getInstance();

// Insert event
$eventType = '';
if (strpos($hallName, 'Seminar Hall') !== false) {
    $eventType = 'Seminar';
} elseif (strpos($hallName, 'Small Hall') !== false) {
    $eventType = 'Workshop';
} elseif (strpos($hallName, 'Lab') !== false) {
    $eventType = 'Training';
}
$eventQuery = "INSERT INTO dbProj_event (eventType, numberOfAudiance, numberOFDays) VALUES (?, ?, ?)";
$db->querySQL($eventQuery, [$eventType, $audience, $duration]);
$eventId = $db->getLastInsertId();
echo "Event ID: $eventId<br>"; // Debugging line

// Get timing ID based on hallId and time
$timingQuery = "SELECT timingID FROM dpProj_HallsTimingSlots WHERE hallId = ? AND timingSlotStart <= ? AND timingSlotEnd >= ?";
$timingResult = $db->singleFetch($timingQuery, [$hallId, $time, $time]);
if ($timingResult) {
    $timingID = $timingResult->timingID;
} else {
    die('Error: Invalid timing ID.');
}

// Calculate discount rate
$client = new Client();
$client->initWithClientId($clientId);
$royaltyPoints = $client->getRoyaltyPoints();
$discountRate = 0;
if ($royaltyPoints > 15) {
    $discountRate = 0.20;
} elseif ($royaltyPoints > 10) {
    $discountRate = 0.10;
} elseif ($royaltyPoints > 5) {
    $discountRate = 0.05;
}

// Insert reservation
$reservationDate = date('Y-m-d');
$reservationQuery = "INSERT INTO dbProj_Reservation (reservationDate, startDate, endDate, timingID, totalCost, discountRate, clientId, hallId, eventId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$db->querySQL($reservationQuery, [$reservationDate, $startDate, $endDate, $timingID, $totalPrice, $discountRate, $clientId, $hallId, $eventId]);
$reservationId = $db->getLastInsertId();
if ($reservationId) {
    echo "Reservation ID: $reservationId<br>"; // Debugging line
} else {
    die('Error: Reservation not created.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            min-height: 100vh;
            background-color: white;
            color: black;
        }
        .container {
            text-align: center;
            max-width: 800px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-buttons input {
            padding: 10px 20px;
            margin-right: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
        }
        .form-buttons input:hover {
            background-color: red;
            color: white;
        }
        .form-buttons {
            margin-top: 20px;
        }
        .form-buttons form {
            display: inline-block;
        }
        header, footer {
            width: 100%;
            background-color: #f8f8f8;
            padding: 10px 0;
        }
        footer {
            margin-top: auto;
        }
        h1 {
            font-size: 2em;
            text-align: center;
            margin-bottom: 20px;
        }
        p, h2, h3 {
            color: black; /* Ensure all text remains black */
        }
    </style>
</head>
<body>
    <header>
        <?php include 'header.html'; ?>
    </header>

    <div class="container">
        <h1>Payment</h1>
        <form action="finalize_reservation.php" method="post">
            <label for="paymentType">Payment Type:</label>
            <input type="text" id="paymentType" name="paymentType" required><br><br>
            <label for="cardDetails">Card Details:</label>
            <input type="text" id="cardDetails" name="cardDetails" required><br><br>
            <label for="cardHolderName">Card Holder Name:</label>
            <input type="text" id="cardHolderName" name="cardHolderName" required><br><br>
            <label for="billingAddress">Billing Address:</label>
            <input type="text" id="billingAddress" name="billingAddress" required><br><br>

            <!-- Pass reservation details -->
            <input type="hidden" name="reservationId" value="<?php echo htmlspecialchars($reservationId); ?>">
            <input type="hidden" name="discountedPrice" value="<?php echo htmlspecialchars($discountedPrice); ?>">
            
            <input type="submit" value="Confirm Payment">
        </form>
    </div>

    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</body>
</html>
