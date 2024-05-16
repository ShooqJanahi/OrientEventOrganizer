<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .form-section { margin-bottom: 20px; }
        label { margin-right: 10px; }
        input, select { margin-right: 20px; }
        .result { margin-top: 20px; border-top: 1px solid #ccc; padding-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .book-btn { padding: 5px 10px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <?php include 'header.html'; ?>

    <div class="coffee_section layout_padding">
        <div class="container">
            <h1>Search for Halls</h1>
            <form method="post" action="">
                <div class="form-section">
                    <label for="date">Select Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>">
                    <label for="duration">Duration:</label>
                    <select id="duration" name="duration">
                        <option value="">Select Duration</option>
                        <option value="1" <?php echo (isset($_POST['duration']) && $_POST['duration'] == '1') ? 'selected' : ''; ?>>1 Day</option>
                        <option value="7" <?php echo (isset($_POST['duration']) && $_POST['duration'] == '7') ? 'selected' : ''; ?>>1 Week</option>
                        <option value="15" <?php echo (isset($_POST['duration']) && $_POST['duration'] == '15') ? 'selected' : ''; ?>>15 Days</option>
                    </select>
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" value="<?php echo isset($_POST['time']) ? htmlspecialchars($_POST['time']) : ''; ?>">
                </div>
                <div class="form-section">
                    <label for="audience">Number of Audience:</label>
                    <input type="number" id="audience" name="audience" placeholder="e.g., 100" value="<?php echo isset($_POST['audience']) ? htmlspecialchars($_POST['audience']) : ''; ?>">
                    <label for="search">Search by Name/Description:</label>
                    <input type="text" id="search" name="search" placeholder="Enter hall name or description" value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                </div>
                <input type="submit" name="submitted" value="Search">
            </form>
            <div class="result">
                <?php
                if (isset($_POST['submitted'])) {
                    // Get input values from the form using POST
                    $date = isset($_POST['date']) ? $_POST['date'] : null;
                    $duration = isset($_POST['duration']) ? $_POST['duration'] : null;
                    $audience = isset($_POST['audience']) ? $_POST['audience'] : null;
                    $time = isset($_POST['time']) ? $_POST['time'] : null;
                    $search = isset($_POST['search']) ? $_POST['search'] : null;

                    // Include the Search class
                    require_once 'Search.php';

                    // Instantiate the Search class
                    $searchClass = new Search();

                    try {
                        // Perform the search
                        $halls = $searchClass->searchHalls($date, $duration, $audience, $time, $search);

                        // Display results
                        if (!empty($halls)) {
                            echo "<table><tr><th>Hall Name</th><th>Location</th><th>Capacity</th><th>Description</th><th>Available Time</th><th>Action</th></tr>";
                            foreach ($halls as $hall) {
                                echo "<tr><td>".$hall["hallName"]."</td><td>".$hall["location"]."</td><td>".$hall["capacity"]."</td><td>".$hall["description"]."</td><td>".$hall["timingSlotStart"]." - ".$hall["timingSlotEnd"]."</td><td><a class='book-btn' href='booking_form.php?hallId=".$hall["hallId"]."&date=".$date."&duration=".$duration."&audience=".$audience."&time=".$time."'>Book</a></td></tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "No results found.<br>";
                            // Get recommendations
                            $recommendations = $searchClass->recommendSlots($date, $duration, $audience, $search);
                            if (!empty($recommendations)) {
                                echo "However, here are some alternative available slots:<br>";
                                echo "<table><tr><th>Hall Name</th><th>Location</th><th>Capacity</th><th>Description</th><th>Available Time</th><th>Action</th></tr>";
                                foreach ($recommendations as $rec) {
                                    echo "<tr><td>".$rec["hallName"]."</td><td>".$rec["location"]."</td><td>".$rec["capacity"]."</td><td>".$rec["description"]."</td><td>".$rec["timingSlotStart"]." - ".$rec["timingSlotEnd"]."</td><td><a class='book-btn' href='booking_form.php?hallId=".$rec["hallId"]."&date=".$date."&duration=".$duration."&audience=".$audience."&time=".$time."'>Book</a></td></tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "No alternative slots available.";
                            }
                        }
                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <?php include 'footer.html'; ?>
</body>
</html>
