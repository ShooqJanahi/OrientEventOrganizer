<!-- Shooq -->

<?php
// confirm_reservation.php
session_start();

// Assume logged-in user's data if they exist
$logged_in_user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

// Example function to apply discounts based on client status
function calculate_discount($status, $total_cost) {
    switch ($status) {
        case "Gold":
            return $total_cost * 0.80;
        case "Silver":
            return $total_cost * 0.90;
        case "Bronze":
            return $total_cost * 0.95;
        default:
            return $total_cost;
    }
}

// Placeholder for reservation cost calculation
$total_cost = 1000; // Replace with actual cost calculation logic
$client_status = isset($logged_in_user['status']) ? $logged_in_user['status'] : 'Bronze';
$discounted_cost = calculate_discount($client_status, $total_cost);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Reservation</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Enter Personal/Business Details</h1>
        <form action="payment.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required value="<?= $logged_in_user ? htmlspecialchars($logged_in_user['name']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required value="<?= $logged_in_user ? htmlspecialchars($logged_in_user['email']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="company">Company Name:</label>
                <input type="text" id="company" name="company" class="form-control" value="<?= $logged_in_user ? htmlspecialchars($logged_in_user['company']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="total_cost">Total Cost (Discount Applied):</label>
                <input type="text" id="total_cost" name="total_cost" class="form-control" value="<?= $discounted_cost ?>" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
        </form>
    </div>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
