<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'Database.php';

// Function to send confirmation email
function sendConfirmationEmail($email, $reservationId, $reservationSummary) {
    $subject = "Reservation Confirmation";
    $message = "Thank you for your reservation. Your reservation ID is: $reservationId\n\n$reservationSummary";
    $headers = "From: no-reply@orienteventorganizer.com";

    mail($email, $subject, $message, $headers);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $db = Database::getInstance()->getConnection();

    $reservationId = $_SESSION['reservationId'];
    $paymentType = $_POST['paymentType'];
    $cardDetails = $_POST['cardDetails'];
    $cardHolderName = $_POST['cardHolderName'];
    $billingAddress = $_POST['billingAddress'];

    // Insert payment details into the database
    $stmt = $db->prepare("INSERT INTO dpProj_Payment (paymentType, cardDetails, cardHolderName, billingAddress, reservationId) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $db->error);
    }

    if (!$stmt->bind_param("ssssi", $paymentType, $cardDetails, $cardHolderName, $billingAddress, $reservationId)) {
        die("Bind failed: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $paymentId = $stmt->insert_id;
    $stmt->close();

    // Create invoice
    $stmt = $db->prepare("INSERT INTO dpProj_Inovice (amount, date, paymentId) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $db->error);
    }

    $amount = $_SESSION['totalPrice'];
    $date = date('Y-m-d');

    if (!$stmt->bind_param("dsi", $amount, $date, $paymentId)) {
        die("Bind failed: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();

    // Send confirmation email
    $email = $_SESSION['email'];
    $reservationSummary = $_SESSION['reservationSummary'];
    sendConfirmationEmail($email, $reservationId, $reservationSummary);

    header('Location: summary.php?reservationId=' . $reservationId);
    exit();
}

// Retrieve reservation details from the session
$reservationId = $_SESSION['reservationId'] ?? '';
$totalPrice = $_SESSION['totalPrice'] ?? '';
$email = $_SESSION['email'] ?? '';
$reservationSummary = $_SESSION['reservationSummary'] ?? '';

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
        <h2>Reservation Summary</h2>
        <p><?php echo nl2br(htmlspecialchars($reservationSummary)); ?></p>
        <p>Total Price: <?php echo htmlspecialchars($totalPrice); ?> BD</p>

        <form action="payment.php" method="post">
            <label for="paymentType">Payment Type:</label>
            <input type="text" name="paymentType" required><br>

            <label for="cardDetails">Card Details:</label>
            <input type="text" name="cardDetails" required><br>

            <label for="cardHolderName">Card Holder Name:</label>
            <input type="text" name="cardHolderName" required><br>

            <label for="billingAddress">Billing Address:</label>
            <input type="text" name="billingAddress" required><br>

            <div class="form-buttons">
                <input type="submit" name="confirm_payment" value="Confirm Payment">
                <input type="button" value="Cancel" onclick="window.location.href='index.php';">
            </div>
        </form>
    </div>

    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</body>
</html>
