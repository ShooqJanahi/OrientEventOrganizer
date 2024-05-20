<?php
// Include database class
include('Database.php');

// Start the session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $company = htmlspecialchars($_POST['company']);
    $address = htmlspecialchars($_POST['address']);
    $hallId = htmlspecialchars($_POST['hallId']);
    $hallName = htmlspecialchars($_POST['hallName']);
    $startDate = htmlspecialchars($_POST['start_date']);
    $duration = htmlspecialchars($_POST['duration']);
    $endDate = htmlspecialchars($_POST['end_date']);
    $audience = htmlspecialchars($_POST['audience']);
    $time = htmlspecialchars($_POST['time']);
    $hallImage = htmlspecialchars($_POST['hallImage']);
    $rentalDetails = htmlspecialchars($_POST['rentalDetails']);
    $totalPrice = htmlspecialchars($_POST['totalPrice']);

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        echo "All required fields must be filled out.";
        exit;
    }

    // Generate a unique reservation code
    $reservation_code = uniqid('res_');

    // Get the database instance
    $db = Database::getInstance();

    // Check if the user already exists and get the userId
    $userCheckSql = "SELECT userId FROM dbProj_User WHERE email = ?";
    $user = $db->singleFetch($userCheckSql, [$email]);

    if (!$user) {
        // Insert new user into dbProj_User
        $userInsertSql = "INSERT INTO dbProj_User (firstName, lastName, username, userType, password, email, phoneNumber)
                          VALUES (?, ?, ?, 'client', '', ?, ?)";
        $db->querySQL($userInsertSql, [$name, '', $name, $email, $phone]);
        $userId = mysqli_insert_id($db->dblink);

        // Insert new client into dbProj_Client
        $clientInsertSql = "INSERT INTO dbProj_Client (userId, companyName, clientStatus)
                            VALUES (?, ?, 'Bronze Client')";
        $db->querySQL($clientInsertSql, [$userId, $company]);
        $clientId = mysqli_insert_id($db->dblink);
    } else {
        // Get clientId for existing user
        $clientId = $db->singleFetch("SELECT clientId FROM dbProj_Client WHERE userId = ?", [$user->userId])->clientId;
    }

    // Prepare and execute the SQL statement to insert reservation
    $sql = "INSERT INTO dbProj_Reservation (reservationDate, startDate, endDate, timingID, totalCost, clientId, hallId, eventId)
            VALUES (CURDATE(), ?, ?, ?, ?, ?, ?, NULL)";
    $params = [$startDate, $endDate, $time, $totalPrice, $clientId, $hallId];

    if ($db->querySQL($sql, $params)) {
        $reservationId = mysqli_insert_id($db->dblink);

        // Output reservation details
        echo "Reservation confirmed successfully. Your reservation code is: " . $reservation_code;

        // Send confirmation email (optional)
        // mail($email, "Reservation Confirmation", "Your reservation is confirmed. Your reservation code is: " . $reservation_code);
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($db->dblink);
    }
}
?>
