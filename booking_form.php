<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Hall</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .form-section { margin-bottom: 20px; }
        label { margin-right: 10px; }
        input, select { margin-right: 20px; }
        .form-buttons { margin-top: 20px; }
        .form-buttons input { padding: 10px 20px; margin-right: 10px; }
    </style>
</head>
<body>
    <?php include 'header.html'; ?>

    <div class="booking_section layout_padding">
        <div class="container">
            <h1>Book a Hall</h1>
            <form id="bookingForm">
                <div class="form-section">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>" required>
                    <label for="duration">Duration:</label>
                    <select id="duration" name="duration" required>
                        <option value="">Select Duration</option>
                        <option value="1" <?php echo (isset($_GET['duration']) && $_GET['duration'] == '1') ? 'selected' : ''; ?>>1 Day</option>
                        <option value="7" <?php echo (isset($_GET['duration']) && $_GET['duration'] == '7') ? 'selected' : ''; ?>>1 Week</option>
                        <option value="15" <?php echo (isset($_GET['duration']) && $_GET['duration'] == '15') ? 'selected' : ''; ?>>15 Days</option>
                    </select>
                    <label for="end_date">End Date:</label>
                    <input type="text" id="end_date" name="end_date" readonly>
                </div>
                <div class="form-section">
                    <label for="audience">Number of Audience:</label>
                    <input type="number" id="audience" name="audience" value="<?php echo isset($_GET['audience']) ? htmlspecialchars($_GET['audience']) : ''; ?>" required>
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" value="<?php echo isset($_GET['time']) ? htmlspecialchars($_GET['time']) : ''; ?>" required>
                </div>
                <div class="form-section">
                    <label for="hall">Hall:</label>
                    <input type="text" id="hall" name="hall" value="<?php echo isset($_GET['hallId']) ? htmlspecialchars($_GET['hallId']) : ''; ?>" readonly>
                </div>
                <div class="form-buttons">
                    <input type="button" id="proceedButton" value="Proceed">
                    <input type="button" value="Cancel" onclick="window.history.back();">
                </div>
            </form>
            <div id="result"></div>
        </div>
    </div>

    <script>
        function calculateEndDate() {
            const startDate = document.getElementById('start_date').value;
            const duration = document.getElementById('duration').value;
            if (startDate && duration) {
                const endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + parseInt(duration));
                document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
            }
        }

        document.getElementById('duration').addEventListener('change', calculateEndDate);
        document.getElementById('start_date').addEventListener('change', calculateEndDate);

        document.getElementById('proceedButton').addEventListener('click', function() {
            const form = document.getElementById('bookingForm');
            const formData = new FormData(form);

            fetch('process_booking.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('result').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
        });
    </script>

    <?php include 'footer.html'; ?>
</body>
</html>
