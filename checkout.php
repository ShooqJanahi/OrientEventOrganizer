<-<!-- Shooq -->
<?php
// checkout.php
session_start();

// Fetch posted data
$name = $_POST['name'];
$email = $_POST['email'];
$company = $_POST['company'];
$total_cost = $_POST['total_cost'];

// Payment processing would occur here
// Store reservation details in the database

// Placeholder for sending confirmation email
mail($email, "Reservation Confirmation", "Thank you for your reservation. Your total is: $total_cost");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Thank you for your Reservation</h1>
        <p>A confirmation email has been sent to <?= htmlspecialchars($email) ?>.</p>
        <p>Your total payment was: <?= htmlspecialchars($total_cost) ?></p>
    </div>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


