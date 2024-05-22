<?php
session_start();
include 'Database.php';

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    header('Location: LoginForm.php');
    exit();
}

$invoiceId = $_GET['invoiceId'];
$db = Database::getInstance();

$sql = "SELECT * FROM dpProj_Inovice WHERE invoiceId = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $invoiceId);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_object();

$sql_payment = "SELECT * FROM dpProj_Payment WHERE paymentId = ?";
$stmt_payment = $db->prepare($sql_payment);
$stmt_payment->bind_param('i', $invoice->paymentId);
$stmt_payment->execute();
$result_payment = $stmt_payment->get_result();
$payment = $result_payment->fetch_object();

$sql_reservation = "SELECT * FROM dbProj_Reservation WHERE reservationId = ?";
$stmt_reservation = $db->prepare($sql_reservation);
$stmt_reservation->bind_param('i', $payment->reservationId);
$stmt_reservation->execute();
$result_reservation = $stmt_reservation->get_result();
$reservation = $result_reservation->fetch_object();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        /* Your styles here */
    </style>
</head>
<body>
    <header>
        <?php include 'header.html'; ?>
    </header>
    <div class="container">
        <h1>Invoice</h1>
        <p>Thank you for your reservation. Here are your details:</p>
        <p>Invoice ID: <?php echo htmlspecialchars($invoice->invoiceId); ?></p>
        <p>Amount: <?php echo htmlspecialchars($invoice->amount); ?> BD</p>
        <p>Date: <?php echo htmlspecialchars($invoice->date); ?></p>
        <p>Reservation ID: <?php echo htmlspecialchars($reservation->reservationId); ?></p>
        <p>Payment Type: <?php echo htmlspecialchars($payment->paymentType); ?></p>
        <p>Card Holder Name: <?php echo htmlspecialchars($payment->cardHolderName); ?></p>
        <p>Billing Address: <?php echo htmlspecialchars($payment->billingAddress); ?></p>
    </div>
    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</body>
</html>
