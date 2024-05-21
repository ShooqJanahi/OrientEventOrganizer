<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Reservation</title>
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
        <h1>Confirm Reservation</h1>
        <form action="finalize_reservation.php" method="post">
            <input type="hidden" name="hallId" value="<?php echo htmlspecialchars($_POST['hallId']); ?>">
            <input type="hidden" name="hallName" value="<?php echo htmlspecialchars($_POST['hallName']); ?>">
            <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($_POST['start_date']); ?>">
            <input type="hidden" name="duration" value="<?php echo htmlspecialchars($_POST['duration']); ?>">
            <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($_POST['end_date']); ?>">
            <input type="hidden" name="audience" value="<?php echo htmlspecialchars($_POST['audience']); ?>">
            <input type="hidden" name="time" value="<?php echo htmlspecialchars($_POST['time']); ?>">
            <input type="hidden" name="hallImage" value="<?php echo htmlspecialchars($_POST['hallImage']); ?>">
            <input type="hidden" name="rentalDetails" value="<?php echo htmlspecialchars($_POST['rentalDetails']); ?>">
            <input type="hidden" name="totalPrice" value="<?php echo htmlspecialchars($_POST['totalPrice']); ?>">

            <h2>Reservation Details</h2>
            <p>Hall Name: <?php echo htmlspecialchars($_POST['hallName']); ?></p>
            <p>Start Date: <?php echo htmlspecialchars($_POST['start_date']); ?></p>
            <p>End Date: <?php echo htmlspecialchars($_POST['end_date']); ?></p>
            <p>Duration: <?php echo htmlspecialchars($_POST['duration']); ?> days</p>
            <p>Number of Audience: <?php echo htmlspecialchars($_POST['audience']); ?></p>
            <p>Time: <?php echo htmlspecialchars($_POST['time']); ?></p>
            <p>Rental Details: <?php echo htmlspecialchars($_POST['rentalDetails']); ?> BD</p>
            <p>Total Price: <?php echo htmlspecialchars($_POST['totalPrice']); ?> BD</p>

            <?php if (isset($_POST['selectedMenus'])): ?>
                <h3>Selected Menus:</h3>
                <ul>
                    <?php foreach ($_POST['selectedMenus'] as $index => $menuId): ?>
                        <li>Menu ID: <?php echo htmlspecialchars($menuId); ?> (Price: <?php echo htmlspecialchars($_POST['menuPrices'][$index]); ?> BD)</li>
                        <input type="hidden" name="selectedMenus[]" value="<?php echo htmlspecialchars($menuId); ?>">
                        <input type="hidden" name="menuPrices[]" value="<?php echo htmlspecialchars($_POST['menuPrices'][$index]); ?>">
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (isset($_POST['selectedServices'])): ?>
                <h3>Selected Services:</h3>
                <ul>
                    <?php foreach ($_POST['selectedServices'] as $index => $serviceId): ?>
                        <li>Service ID: <?php echo htmlspecialchars($serviceId); ?> (Price: <?php echo htmlspecialchars($_POST['servicePrices'][$index]); ?> BD)</li>
                        <input type="hidden" name="selectedServices[]" value="<?php echo htmlspecialchars($serviceId); ?>">
                        <input type="hidden" name="servicePrices[]" value="<?php echo htmlspecialchars($_POST['servicePrices'][$index]); ?>">
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <h2>Enter Personal/Business Details to Confirm Reservation</h2>
            <?php if (!isset($_SESSION['loggedInUser'])): ?>
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" required><br><br>

                <label for="company">Company Name (if applicable):</label>
                <input type="text" id="company" name="company"><br><br>

                <label for="address">Billing Address:</label>
                <textarea id="address" name="address" required></textarea><br><br>
            <?php endif; ?>

            <div class="form-buttons">
                <input type="submit" value="Confirm Reservation">
                <input type="button" value="Cancel" onclick="window.location.href='main_page.php';">
            </div>
        </form>
    </div>

    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</body>
</html>
