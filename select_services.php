<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Catering or Other Services</title>
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
        .service-option, .menu-option {
            cursor: pointer;
            padding: 10px;
            border: 1px solid black;
            margin: 5px;
        }
        .service-option:hover, .menu-option:hover {
            background-color: lightgray;
        }
        .details {
            display: none;
            margin-top: 10px;
            text-align: left;
            padding: 10px;
            border: 1px solid black;
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            // Retrieve the necessary variables from the confirm page
            var audience = <?php echo isset($_POST['audience']) ? (int)$_POST['audience'] : 1; ?>;
            var duration = <?php echo isset($_POST['duration']) ? (int)$_POST['duration'] : 1; ?>;
            var startTime = '<?php echo isset($_POST['start_time']) ? $_POST['start_time'] : '12:00 PM'; ?>';
            var endTime = '<?php echo isset($_POST['end_time']) ? $_POST['end_time'] : '05:00 PM'; ?>';

            // Convert time to 24-hour format
            function convertTo24HourFormat(time) {
                var hours = parseInt(time.split(':')[0]);
                var minutes = parseInt(time.split(':')[1].split(' ')[0]);
                var ampm = time.split(' ')[1];
                if (ampm === 'PM' && hours < 12) hours += 12;
                if (ampm === 'AM' && hours === 12) hours = 0;
                return hours + (minutes / 60);
            }

            var startTime24 = convertTo24HourFormat(startTime);
            var endTime24 = convertTo24HourFormat(endTime);
            var hours = endTime24 - startTime24;

            // Calculate total price function
            function calculateTotalPrice() {
                var totalCatering = 0;
                var totalServices = 0;
                $(".menu-option input[type=checkbox]:checked").each(function(){
                    totalCatering += parseFloat($(this).data("price")) * audience;
                });
                $(".service-option input[type=checkbox]:checked").each(function(){
                    totalServices += parseFloat($(this).data("price")) * hours * duration;
                });
                var totalPrice = totalCatering + totalServices;
                $("#total-price").text("Total Price: " + totalPrice + " BD");
            }

            // Fetch and display menu details
            $(".menu-option input[type=checkbox]").change(function(){
                var selected = $(this).data("menu-id");
                var detailsDiv = $(this).closest('.menu-option').find('.details');
                if ($(this).is(':checked')) {
                    $.ajax({
                        url: "fetch_menu.php",
                        type: "POST",
                        data: {menuId: selected},
                        success: function(response){
                            detailsDiv.html(response);
                            detailsDiv.show();
                            calculateTotalPrice();
                        }
                    });
                } else {
                    detailsDiv.hide();
                    calculateTotalPrice();
                }
            });

            // Fetch and display service details
            $(".service-option input[type=checkbox]").change(function(){
                var selected = $(this).data("service-id");
                var detailsDiv = $(this).closest('.service-option').find('.details');
                if ($(this).is(':checked')) {
                    $.ajax({
                        url: "fetch_service.php",
                        type: "POST",
                        data: {serviceId: selected},
                        success: function(response){
                            detailsDiv.html(response);
                            detailsDiv.show();
                            calculateTotalPrice();
                        }
                    });
                } else {
                    detailsDiv.hide();
                    calculateTotalPrice();
                }
            });

            // Calculate total price on checkbox change
            $(".menu-option input[type=checkbox], .service-option input[type=checkbox]").change(function(){
                calculateTotalPrice();
            });
        });
    </script>
</head>
<body>
    <header>
        <?php include 'header.html'; ?>
    </header>

    <div class="container">
        <h1>Select Catering or Other Services</h1>
        <div>
            <h2>Catering</h2>
            <div class="menu-option">
                <label>
                    <input type="checkbox" data-menu-id="1" data-price="10">
                    <span>Breakfast - 10 BD per person</span>
                </label>
                <div class="details"></div>
            </div>
            <div class="menu-option">
                <label>
                    <input type="checkbox" data-menu-id="2" data-price="12">
                    <span>Lunch - 12 BD per person</span>
                </label>
                <div class="details"></div>
            </div>
            <div class="menu-option">
                <label>
                    <input type="checkbox" data-menu-id="3" data-price="5">
                    <span>Hot Beverages - 5 BD per person</span>
                </label>
                <div class="details"></div>
            </div>
            <div class="menu-option">
                <label>
                    <input type="checkbox" data-menu-id="4" data-price="3">
                    <span>Cold Beverages - 3 BD per person</span>
                </label>
                <div class="details"></div>
            </div>
        </div>
        <div>
            <h2>Service Packages</h2>
            <div class="service-option">
                <label>
                    <input type="checkbox" data-service-id="1" data-price="150">
                    <span>Decor and Theme Design - 150 BD per hour</span>
                </label>
                <div class="details"></div>
            </div>
            <div class="service-option">
                <label>
                    <input type="checkbox" data-service-id="2" data-price="100">
                    <span>Audiovisual Equipment Rental - 100 BD per hour</span>
                </label>
                <div class="details"></div>
            </div>
            <div class="service-option">
                <label>
                    <input type="checkbox" data-service-id="3" data-price="120">
                    <span>Event Photography and Videography - 120 BD per hour</span>
                </label>
                <div class="details"></div>
            </div>
        </div>
        <div id="total-price">
            <p>Total Price: 0 BD</p>
        </div>
        <div class="form-buttons">
            <form method="post" action="confirm_booking.php">
                <input type="hidden" name="hallId" value="<?php echo htmlspecialchars($hallId); ?>">
                <input type="hidden" name="hallName" value="<?php echo htmlspecialchars($hallName); ?>">
                <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
                <input type="hidden" name="duration" value="<?php echo htmlspecialchars($duration); ?>">
                <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
                <input type="hidden" name="audience" value="<?php echo htmlspecialchars($audience); ?>">
                <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
                <input type="hidden" name="hallImage" value="<?php echo htmlspecialchars($hallImage); ?>">
                <input type="hidden" name="rentalDetails" value="<?php echo htmlspecialchars($rentalDetails); ?>">
                <input type="submit" value="Proceed">
            </form>
            <input type="button" value="Cancel" onclick="window.location.href='main_page.php';">
        </div>
    </div>

    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</body>
</html>
