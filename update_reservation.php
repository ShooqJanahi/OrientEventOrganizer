<?php
include 'Database.php'; // Include your Database class

// Function to fetch reservation details along with additional information
function fetchReservationDetails($reservationId) {
    $db = Database::getInstance();
    $sql = "SELECT r.*, e.eventType, e.numberOfAudiance, e.numberOfDays, 
                   h.hallName, h.hallId, t.timingSlotStart, t.timingSlotEnd
            FROM dbProj_Reservation r
            LEFT JOIN dbProj_event e ON r.eventId = e.eventId
            LEFT JOIN dbProj_Hall h ON r.hallId = h.hallId
            LEFT JOIN dpProj_HallsTimingSlots t ON r.timingID = t.timingID
            WHERE r.reservationId = ?";
    return $db->singleFetch($sql, [$reservationId]);
}

// Function to fetch all menus
function fetchAllMenus() {
    $db = Database::getInstance();
    $sql = "SELECT * FROM dbProj_Menu";
    return $db->multiFetch($sql);
}

// Function to fetch all service packages
function fetchAllServices() {
    $db = Database::getInstance();
    $sql = "SELECT * FROM dpProj_servicePackage";
    return $db->multiFetch($sql);
}

// Function to fetch selected catering details
function fetchCateringDetails($reservationId) {
    $db = Database::getInstance();
    $sql = "SELECT menuId, packageId FROM dbProj_Catering WHERE reservationId = ?";
    return $db->multiFetch($sql, [$reservationId]);
}

// Function to fetch all timing slots for a hall
function fetchTimingSlots($hallId) {
    $db = Database::getInstance();
    $sql = "SELECT * FROM dpProj_HallsTimingSlots WHERE hallId = ?";
    return $db->multiFetch($sql, [$hallId]);
}

// Function to update reservation
function updateReservation($reservationId, $startDate, $endDate, $eventId, $timingID, $selectedMenus, $selectedServices) {
    $db = Database::getInstance();
    $db->querySQL("DELETE FROM dbProj_Catering WHERE reservationId = ?", [$reservationId]); // Clear existing catering details

    // Insert new catering details
    foreach ($selectedMenus as $menuId) {
        $db->querySQL("INSERT INTO dbProj_Catering (reservationId, menuId) VALUES (?, ?)", [$reservationId, $menuId]);
    }
    foreach ($selectedServices as $packageId) {
        $db->querySQL("INSERT INTO dbProj_Catering (reservationId, packageId) VALUES (?, ?)", [$reservationId, $packageId]);
    }

    $sql = "CALL UpdateReservation(?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("issii", $reservationId, $startDate, $endDate, $eventId, $timingID);
        $stmt->execute();
        $stmt->bind_result($updatedTotalCost);
        $stmt->fetch();
        $stmt->close();
        return $updatedTotalCost;
    }
    return false;
}

// Function to check availability
function checkAvailability($startDate, $endDate, $timingID, $hallId) {
    $db = Database::getInstance();
    $sql = "SELECT * FROM dbProj_Reservation WHERE hallId = ? AND timingID = ? AND 
            ((startDate <= ? AND endDate >= ?) OR (startDate <= ? AND endDate >= ?))";
    $result = $db->multiFetch($sql, [$hallId, $timingID, $startDate, $startDate, $endDate, $endDate]);
    return count($result) === 0;
}

// Calculate the end date based on the start date and duration
function calculateEndDate($startDate, $duration) {
    switch ($duration) {
        case '1':
            return $startDate;
        case '7':
            return date('Y-m-d', strtotime($startDate . ' + 6 days'));
        case '15':
            return date('Y-m-d', strtotime($startDate . ' + 14 days'));
    }
    return $startDate;
}

if (isset($_POST['reservationId'])) {
    $reservationId = $_POST['reservationId'];
    $reservation = fetchReservationDetails($reservationId);
    $allMenus = fetchAllMenus();
    $allServices = fetchAllServices();
    $selectedCatering = fetchCateringDetails($reservationId);
    $selectedMenus = array_column($selectedCatering, 'menuId');
    $selectedServices = array_column($selectedCatering, 'packageId');
    $timingSlots = fetchTimingSlots($reservation->hallId);
}

if (isset($_POST['update_reservation'])) {
    $reservationId = $_POST['reservationId'];
    $startDate = $_POST['startDate'];
    $duration = $_POST['duration'];
    $endDate = calculateEndDate($startDate, $duration);
    $eventId = $_POST['eventId'];
    $timingID = $_POST['timingID'];
    $hallId = $_POST['hallId']; // Assuming you pass the hall ID as a hidden field
    $selectedMenus = isset($_POST['menuId']) ? $_POST['menuId'] : [];
    $selectedServices = isset($_POST['packageId']) ? $_POST['packageId'] : [];

    // Check availability
    if (checkAvailability($startDate, $endDate, $timingID, $hallId)) {
        $updatedTotalCost = updateReservation($reservationId, $startDate, $endDate, $eventId, $timingID, $selectedMenus, $selectedServices);
        if ($updatedTotalCost !== false) {
            echo "Reservation updated successfully. New total cost: " . $updatedTotalCost;
        } else {
            echo "Error updating reservation.";
        }
    } else {
        echo "The selected hall is not available for the chosen dates and time slots.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Reservation</title>
</head>
<body>
    <h2>Update Reservation</h2>

    <?php if (isset($reservation)) { ?>
        <form action="" method="post">
            <input type="hidden" name="reservationId" value="<?php echo $reservationId; ?>">
            <input type="hidden" name="hallId" value="<?php echo $reservation->hallId; ?>"> <!-- Pass the hall ID -->
            
            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" name="startDate" value="<?php echo $reservation->startDate; ?>" required><br><br>
            
            <label for="duration">Duration:</label>
            <input type="radio" id="duration1" name="duration" value="1" <?php echo ($reservation->numberOfDays == 1) ? 'checked' : ''; ?>>
            <label for="duration1">1 Day</label><br>
            <input type="radio" id="duration7" name="duration" value="7" <?php echo ($reservation->numberOfDays == 7) ? 'checked' : ''; ?>>
            <label for="duration7">1 Week</label><br>
            <input type="radio" id="duration15" name="duration" value="15" <?php echo ($reservation->numberOfDays == 15) ? 'checked' : ''; ?>>
            <label for="duration15">15 Days</label><br><br>
            
            <label for="numberOfAudiance">Number of Audience:</label>
            <input type="number" id="numberOfAudiance" name="numberOfAudiance" value="<?php echo $reservation->numberOfAudiance; ?>" required><br><br>
            
            <h3>Select Timing Slots</h3>
            <?php foreach ($timingSlots as $slot) { ?>
                <input type="checkbox" id="slot_<?php echo $slot->timingID; ?>" name="timingID[]" value="<?php echo $slot->timingID; ?>"
                    <?php echo ($reservation->timingID == $slot->timingID) ? 'checked' : ''; ?>>
                <label for="slot_<?php echo $slot->timingID; ?>"><?php echo $slot->timingSlotStart . " - " . $slot->timingSlotEnd; ?></label><br>
            <?php } ?>
            
            <h3>Select Menus</h3>
            <?php foreach ($allMenus as $menu) { ?>
                <input type="checkbox" id="menu_<?php echo $menu->menuId; ?>" name="menuId[]" value="<?php echo $menu->menuId; ?>"
                    <?php echo in_array($menu->menuId, $selectedMenus) ? 'checked' : ''; ?>>
                <label for="menu_<?php echo $menu->menuId; ?>"><?php echo $menu->menuName . " - $" . $menu->price; ?></label><br>
            <?php } ?>
            
            <h3>Select Services</h3>
            <?php foreach ($allServices as $service) { ?>
                <input type="checkbox" id="service_<?php echo $service->packageId; ?>" name="packageId[]" value="<?php echo $service->packageId; ?>"
                    <?php echo in_array($service->packageId, $selectedServices) ? 'checked' : ''; ?>>
                <label for="service_<?php echo $service->packageId; ?>"><?php echo $service->packageName . " - $" . $service->price; ?></label><br>
            <?php } ?>
            
            <input type="submit" name="update_reservation" value="Update Reservation">
        </form>
    <?php } ?>
</body>
</html>
