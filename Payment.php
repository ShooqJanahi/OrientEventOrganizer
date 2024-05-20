<!-- Shooq -->
<?php
session_start();
include 'Database.php'; // Your database connection file

// Retrieve the reservation code and other details from the POST data
$reservation_code = isset($_POST['reservation_code']) ? $_POST['reservation_code'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$company = isset($_POST['company']) ? $_POST['company'] : '';
$total_cost = isset($_POST['total_cost']) ? $_POST['total_cost'] : '';

if (!$reservation_code || !$name || !$email || !$total_cost) {
    die("All fields are required.");
}

// Get the database instance
$db = Database::getInstance();
$pdo = $db->dblink;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentType = $_POST['paymentType'];
    $cardNumber = $_POST['cardNumber'];
    $expiryDate = $_POST['expiryDate'];
    $securityNumber = $_POST['securityNumber'];
    $cardholderName = $_POST['cardholderName'];
    $billingAddress = $_POST['billingAddress'];
    $amountPaid = $total_cost;
    $paymentDate = date('Y-m-d');

    // Insert payment details into the payment table
    $stmt = $pdo->prepare("INSERT INTO dbProj_Payment (reservationId, paymentType, cardDetails, cardHolderName, billingAddress) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('issss', $reservation_code, $paymentType, $cardNumber, $cardholderName, $billingAddress);

    if ($stmt->execute()) {
        // Redirect or display success message
        header("Location: summary.php?code=" . $reservation_code);
        exit();
    } else {
        die("Error saving payment details.");
    }
}

// Fetch reservation details
$sql = "SELECT * FROM dbProj_Reservation WHERE reservationId = ?";
$stmt = $pdo->prepare($sql);
$stmt->bind_param('i', $reservation_code);
$stmt->execute();
$reservation = $stmt->get_result()->fetch_assoc();

if (!$reservation) {
    die("Reservation not found.");
}

$totalCost = $reservation['totalCost'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Payment</h1>
        <form method="post">
            <div class="form-group">
                <label for="paymentType">Payment Type:</label>
                <input type="text" id="paymentType" name="paymentType" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="cardNumber">Card Number:</label>
                <input type="text" id="cardNumber" name="cardNumber" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="expiryDate">Expiry Date:</label>
                <input type="date" id="expiryDate" name="expiryDate" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="securityNumber">Security Number:</label>
                <input type="text" id="securityNumber" name="securityNumber" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="cardholderName">Cardholder Name:</label>
                <input type="text" id="cardholderName" name="cardholderName" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="billingAddress">Billing Address:</label>
                <textarea id="billingAddress" name="billingAddress" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="amountPaid">Amount Paid:</label>
                <input type="text" id="amountPaid" name="amountPaid" class="form-control" value="<?= $totalCost ?>" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Confirm Payment</button>
        </form>
    </div>
</body>
</html>
