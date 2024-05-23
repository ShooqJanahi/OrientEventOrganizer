<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'Database.php';

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
$companyName = isset($_POST['companyName']) ? $_POST['companyName'] : '';

$email = isset($_POST['email']) ? $_POST['email'] : '';

$clientId = '';
$clientStatus = '';
$discountedPrice = $totalPrice;

$loggedIn = isset($_SESSION['userId']);
if ($loggedIn) {
    $clientId = $_POST['clientId'];
    $clientStatus = $_POST['clientStatus'];
}

$selectedMenus = isset($_POST['selectedMenus']) ? implode(',', $_POST['selectedMenus']) : '';
$selectedServices = isset($_POST['selectedServices']) ? implode(',', $_POST['selectedServices']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
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
        <h1>Checkout</h1>
        <h2>Reservation Summary</h2>
        <p>Hall Name: <?php echo htmlspecialchars($hallName); ?></p>
        <p>Start Date: <?php echo htmlspecialchars($startDate); ?></p>
        <p>End Date: <?php echo htmlspecialchars($endDate); ?></p>
        <p>Duration: <?php echo htmlspecialchars($duration); ?> days</p>
        <p>Number of Audience: <?php echo htmlspecialchars($audience); ?></p>
        <p>Time: <?php echo htmlspecialchars($time); ?></p>
        <p>Rental Details: <?php echo htmlspecialchars($rentalDetails); ?> BD</p>
        <p>Total Price: <?php echo htmlspecialchars($totalPrice); ?> BD</p>
        <p>Company Name: <?php echo htmlspecialchars($companyName); ?></p>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>

        <div class="form-buttons">
            <form action="payment.php" method="post">
                <!-- Pass reservation details -->
                <input type="hidden" name="hallId" value="<?php echo htmlspecialchars($hallId); ?>">
                <input type="hidden" name="hallName" value="<?php echo htmlspecialchars($hallName); ?>">
                <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
                <input type="hidden" name="duration" value="<?php echo htmlspecialchars($duration); ?>">
                <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
                <input type="hidden" name="audience" value="<?php echo htmlspecialchars($audience); ?>">
                <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
                <input type="hidden" name="hallImage" value="<?php echo htmlspecialchars($hallImage); ?>">
                <input type="hidden" name="rentalDetails" value="<?php echo htmlspecialchars($rentalDetails); ?>">
                <input type="hidden" name="totalPrice" value="<?php echo htmlspecialchars($totalPrice); ?>">
                <input type="hidden" name="clientId" value="<?php echo htmlspecialchars($clientId); ?>">
                <input type="hidden" name="clientStatus" value="<?php echo htmlspecialchars($clientStatus); ?>">
                <input type="hidden" name="companyName" value="<?php echo htmlspecialchars($companyName); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="selectedMenus" value="<?php echo htmlspecialchars($selectedMenus); ?>">
                <input type="hidden" name="selectedServices" value="<?php echo htmlspecialchars($selectedServices); ?>">

                <input type="submit" name="proceed_to_payment" value="Proceed to Payment">
            </form>
            <input type="button" value="Cancel" onclick="window.location.href='index.php';">
        </div>
    </div>

    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</body>
</html>
