<?php
require 'Database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

$searchQuery = "";
$sql = "SELECT h.*, GROUP_CONCAT(t.timingSlotStart, ' to ', t.timingSlotEnd SEPARATOR '|') as timingSlots
        FROM dbProj_Hall h
        LEFT JOIN dpProj_HallsTimingSlots t ON h.hallId = t.hallId";
$params = [];

if (isset($_POST['search'])) {
    $searchQuery = $db->mkSafe($_POST['search']);
    $sql .= " WHERE MATCH(hallName, description) AGAINST (?)";
    $params = [$searchQuery];
}

$sql .= " GROUP BY h.hallId";
$halls = $db->multiFetch($sql, $params);

include 'header.html';
?>
 <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .header, .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        .container {
            width: 90%;
            margin: 20px auto;
        }
        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-bar input {
            padding: 10px;
            width: 60%;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-bar button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .hall {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .hall img {
            max-width: 200px; /* Smaller image size */
            height: auto;
            border-radius: 5px;
            display: block;
            margin: 0 auto; /* Center the image */
        }
        .hall-details {
            margin-top: 10px;
            text-align: center; /* Center align text */
        }
        .book-button {
            display: block;
            margin: 20px 0;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>


<form method="post" action="view_search.php" class="search-bar">
    <input type="text" name="search" placeholder="Search by hall name or description" value="<?php echo htmlspecialchars($searchQuery); ?>">
    <button type="submit">Search</button>
</form>

<?php
if (!empty($halls)) {
    foreach ($halls as $hall) {
        echo "<div class='hall'>";
        echo "<h2>" . htmlspecialchars($hall->hallName) . "</h2>";
        echo "<img src='" . htmlspecialchars($hall->image) . "' alt='" . htmlspecialchars($hall->hallName) . "'>";
        echo "<div class='hall-details'>";
        echo "<p><strong>Location:</strong> " . htmlspecialchars($hall->location) . "</p>";
        echo "<p><strong>Description:</strong> " . htmlspecialchars($hall->description) . "</p>";
        echo "<p><strong>Rental Charge:</strong> $" . htmlspecialchars($hall->rentalCharge) . "</p>";
        echo "<p><strong>Capacity:</strong> " . htmlspecialchars($hall->capacity) . "</p>";

        // Display hall timing slots
        if ($hall->timingSlots) {
            echo "<h3>Timing Slots:</h3>";
            echo "<ul>";
            $timingSlots = explode('|', $hall->timingSlots);
            foreach ($timingSlots as $slot) {
                echo "<li>" . htmlspecialchars($slot) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No timing slots available.</p>";
        }

        echo "</div></div>";
    }
} else {
    echo "<p>No halls found.</p>";
}
?>

<a href="searchAndBooking.php" class="book-button">Book an Event</a>

<?php include 'footer.html'; ?>
