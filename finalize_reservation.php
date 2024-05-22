<?php
session_start();
include 'Database.php';
include 'Client.php';

// Retrieve payment details
$reservationId = $_POST['reservationId'];
$paymentType = $_POST['paymentType'];
$cardDetails = $_POST['cardDetails'];
$cardHolderName = $_POST['cardHolderName'];
$billingAddress = $_POST['billingAddress'];
$discountedPrice = $_POST['discountedPrice'];
$clientId = $_POST['clientId'];

// Insert payment details into the database
try {
    $db = Database::getInstance();
    
    // Start transaction
    $db->beginTransaction();

    $paymentQuery = "INSERT INTO dpProj_Payment (paymentType, cardDetails, cardHolderName, billingAddress, reservationId) VALUES (?, ?, ?, ?, ?)";
    $db->querySQL($paymentQuery, [$paymentType, $cardDetails, $cardHolderName, $billingAddress, $reservationId]);
    $paymentId = $db->getLastInsertId();

    // Insert invoice details
    $invoiceQuery = "INSERT INTO dpProj_Invoice (amount, date, paymentId) VALUES (?, ?, ?)";
    $invoiceDate = date('Y-m-d');
    $db->querySQL($invoiceQuery, [$discountedPrice, $invoiceDate, $paymentId]);

    // Commit transaction
    $db->commit();
    
    // Update royalty points and client status if user is logged in
    if (isset($_SESSION['userId'])) {
        $clientId = $_SESSION['userId'];
        $client = new Client();
        $user = $client->initWithClientId($clientId);
        $currentPoints = $client->getRoyaltyPoints();
        $newPoints = $currentPoints + 1;
        $clientStatus = $client->getClientStatus();

        if ($newPoints > 15) {
            $clientStatus = 'Golden';
        } elseif ($newPoints > 10) {
            $clientStatus = 'Silver';
        } elseif ($newPoints > 5) {
            $clientStatus = 'Bronze';
        }

        $client->setRoyaltyPoints($newPoints);
        $client->setClientStatus($clientStatus);
        $client->updateClientDB();

        // Send confirmation email
        $to = $user->getEmail();
        $subject = "Reservation Confirmation";
        $message = "Dear " . $user->getFirstName() . " " . $user->getLastName() . ",\n\nYour reservation has been confirmed. Your reservation ID is " . $reservationId . ".\n\nThank you for choosing our service.";
        $headers = "From: no-reply@example.com";

        mail($to, $subject, $message, $headers);
    }

    echo "Connected successfully to the database.";

} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollback();
    echo "Error: " . $e->getMessage();
}
?>
