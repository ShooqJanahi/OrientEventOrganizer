<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'Database.php';

// Function to calculate discount based on client status
function calculateDiscount($status, $totalCost) {
    $discountRate = 0;
    if ($status == 'Golden') {
        $discountRate = 0.20;
    } elseif ($status == 'Silver') {
        $discountRate = 0.10;
    } elseif ($status == 'Bronze') {
        $discountRate = 0.05;
    }
    return $totalCost * (1 - $discountRate);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proceed_to_payment'])) {
    $db = Database::getInstance()->getConnection();

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
    $clientStatus = isset($_POST['clientStatus']) ? $_POST['clientStatus'] : '';
    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : '';
    $discountedPrice = isset($_POST['discountedPrice']) ? $_POST['discountedPrice'] : $totalPrice;

    // Check if clientId exists in dbProj_Client
    $clientExistsQuery = "SELECT COUNT(*) FROM dbProj_Client WHERE clientId = ?";
    $stmt = $db->prepare($clientExistsQuery);
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $stmt->bind_result($clientExists);
    $stmt->fetch();
    $stmt->close();

    if ($clientExists == 0) {
        die("Client ID does not exist in dbProj_Client.");
    }

    // Insert event details into the database
    $stmt = $db->prepare("INSERT INTO dbProj_event (eventType, numberOfAudiance, numberOFDays) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $db->error);
    }

    $eventType = "Workshop"; // Default value
    $numberOfAudiance = $audience;
    $numberOfDays = $duration;

    if (!$stmt->bind_param("sii", $eventType, $numberOfAudiance, $numberOfDays)) {
        die("Bind failed: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $eventId = $stmt->insert_id; // Get the inserted event ID
    $stmt->close();

    // Insert reservation details into the database
    $stmt = $db->prepare("INSERT INTO dbProj_Reservation (reservationDate, startDate, endDate, timingID, totalCost, discountRate, clientId, hallId, eventId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $db->error);
    }

    $reservationDate = date('Y-m-d');
    $timingID = 1; // Example value, update according to your logic
    $discountRate = $discountedPrice < $totalPrice ? ($totalPrice - $discountedPrice) / $totalPrice : 0;

    if (!$stmt->bind_param("sssiddiii", $reservationDate, $startDate, $endDate, $timingID, $totalPrice, $discountRate, $clientId, $hallId, $eventId)) {
        die("Bind failed: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $reservationId = $stmt->insert_id; // Get the inserted reservation ID
    $stmt->close();

    // Insert catering details into the database if menus or services were selected
    if (!empty($_POST['selectedMenus']) || !empty($_POST['selectedServices'])) {
        $stmt = $db->prepare("INSERT INTO dbProj_Catering (reservationId, menuId, packageId) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $db->error);
        }

        foreach ($_POST['selectedMenus'] as $menuId) {
            $packageId = null; // No service package in this case

            if (!$stmt->bind_param("iii", $reservationId, $menuId, $packageId)) {
                die("Bind failed: " . $stmt->error);
            }

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }
        }

        foreach ($_POST['selectedServices'] as $packageId) {
            $menuId = null; // No menu in this case

            if (!$stmt->bind_param("iii", $reservationId, $menuId, $packageId)) {
                die("Bind failed: " . $stmt->error);
            }

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }
        }

        $stmt->close();
    }

    // Pass reservation data to payment page
    $_SESSION['reservationId'] = $reservationId;
    $_SESSION['totalPrice'] = $totalPrice;
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['reservationSummary'] = "Hall Name: $hallName\nStart Date: $startDate\nEnd Date: $endDate\nDuration: $duration days\nNumber of Audience: $audience\nTime: $time\nRental Details: $rentalDetails BD\nTotal Price: $totalPrice BD\nDiscounted Price: $discountedPrice BD\nCompany Name: $companyName";

    header('Location: payment.php');
    exit();
}

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

$loggedIn = isset($_SESSION['userId']);
$clientStatus = '';
if ($loggedIn) {
    $clientStatus = $_POST['clientStatus'];
    $clientId = $_POST['clientId'];
    $discountedPrice = calculateDiscount($clientStatus, $totalPrice);
} else {
    $clientId = '';
    $discountedPrice = $totalPrice;
}

// Retrieve selected menus and services
$selectedMenus = isset($_POST['selectedMenus']) ? $_POST['selectedMenus'] : [];
$menuPrices = isset($_POST['menuPrices']) ? $_POST['menuPrices'] : [];
$menuNames = isset($_POST['menuNames']) ? $_POST['menuNames'] : [];
$menuLists = isset($_POST['menuLists']) ? $_POST['menuLists'] : [];

$selectedServices = isset($_POST['selectedServices']) ? $_POST['selectedServices'] : [];
$servicePrices = isset($_POST['servicePrices']) ? $_POST['servicePrices'] : [];
$serviceNames = isset($_POST['serviceNames']) ? $_POST['serviceNames'] : [];
$serviceLists = isset($_POST['serviceLists']) ? $_POST['serviceLists'] : [];
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
        <?php if ($loggedIn): ?>
            <p>Discounted Price: <?php echo htmlspecialchars($discountedPrice); ?> BD</p>
        <?php endif; ?>
        <p>Company Name: <?php echo htmlspecialchars($companyName); ?></p>

        <h3>Selected Menus</h3>
        <?php foreach ($menuNames as $index => $menuName): ?>
            <p><?php echo htmlspecialchars($menuName); ?> - <?php echo htmlspecialchars($menuPrices[$index]); ?> BD</p>
            <p><?php echo htmlspecialchars($menuLists[$index]); ?></p>
        <?php endforeach; ?>

        <h3>Selected Services</h3>
        <?php foreach ($serviceNames as $index => $serviceName): ?>
            <p><?php echo htmlspecialchars($serviceName); ?> - <?php echo htmlspecialchars($servicePrices[$index]); ?> BD</p>
            <p><?php echo htmlspecialchars($serviceLists[$index]); ?></p>
        <?php endforeach; ?>

        <div class="form-buttons">
            <form action="checkout.php" method="post">
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
                <input type="hidden" name="discountedPrice" value="<?php echo htmlspecialchars($discountedPrice); ?>">
                <input type="hidden" name="clientId" value="<?php echo htmlspecialchars($clientId); ?>">
                <input type="hidden" name="clientStatus" value="<?php echo htmlspecialchars($clientStatus); ?>">
                <input type="hidden" name="companyName" value="<?php echo htmlspecialchars($companyName); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

                <!-- Pass selected menus and services -->
                <?php foreach ($selectedMenus as $index => $menuId): ?>
                    <input type="hidden" name="selectedMenus[]" value="<?php echo htmlspecialchars($menuId); ?>">
                    <input type="hidden" name="menuPrices[]" value="<?php echo htmlspecialchars($menuPrices[$index]); ?>">
                    <input type="hidden" name="menuNames[]" value="<?php echo htmlspecialchars($menuNames[$index]); ?>">
                    <input type="hidden" name="menuLists[]" value="<?php echo htmlspecialchars($menuLists[$index]); ?>">
                <?php endforeach; ?>
                <?php foreach ($selectedServices as $index => $serviceId): ?>
                    <input type="hidden" name="selectedServices[]" value="<?php echo htmlspecialchars($serviceId); ?>">
                    <input type="hidden" name="servicePrices[]" value="<?php echo htmlspecialchars($servicePrices[$index]); ?>">
                    <input type="hidden" name="serviceNames[]" value="<?php echo htmlspecialchars($serviceNames[$index]); ?>">
                    <input type="hidden" name="serviceLists[]" value="<?php echo htmlspecialchars($serviceLists[$index]); ?>">
                <?php endforeach; ?>

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
