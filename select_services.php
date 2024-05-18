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
    // Calculate total price function
    function calculateTotalPrice() {
        var total = 0;
        $(".menu-option input[type=checkbox]:checked").each(function(){
            total += parseFloat($(this).data("price"));
        });
        $(".service-option input[type=checkbox]:checked").each(function(){
            total += parseFloat($(this).data("price"));
        });
        $("#total-price").text("Total Price: " + total + " BD");
    }

    // Fetch and display menu details
    $(".menu-option input[type=checkbox]").change(function(){
        var selected = $(this).data("menu-id");
        $.ajax({
            url: "fetch_menu.php",
            type: "POST",
            data: {menuId: selected},
            success: function(response){
                $("#menu-details").html(response);
                calculateTotalPrice();
            }
        });
    });

    // Fetch and display service details
    $(".service-option input[type=checkbox]").change(function(){
        var selected = $(this).data("service-id");
        $.ajax({
            url: "fetch_service.php",
            type: "POST",
            data: {serviceId: selected},
            success: function(response){
                $("#service-details").html(response);
                calculateTotalPrice();
            }
        });
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
                    <span>Breakfast - 10 BD</span>
                </label>
            </div>
            <div class="menu-option">
                <label>
                    <input type="checkbox" data-menu-id="2" data-price="12">
                    <span>Lunch - 12 BD</span>
                </label>
            </div>
            <div class="menu-option">
                <label>
                    <input type="checkbox" data-menu-id="3" data-price="5">
                    <span>Hot Beverages - 5 BD</span>
                </label>
            </div>
            <div class="menu-option">
                <label>
                    <input type="checkbox" data-menu-id="4" data-price="3">
                    <span>Cold Beverages - 3 BD</span>
                </label>
            </div>
            <div id="menu-details">
                <p>Menu details will be displayed here.</p>
            </div>
        </div>
        <div>
            <h2>Service Packages</h2>
            <div class="service-option">
                <label>
                    <input type="checkbox" data-service-id="1" data-price="150">
                    <span>Decor and Theme Design - 150 BD</span>
                </label>
            </div>
            <div class="service-option">
                <label>
                    <input type="checkbox" data-service-id="2" data-price="100">
                    <span>Audiovisual Equipment Rental - 100 BD</span>
                </label>
            </div>
            <div class="service-option">
                <label>
                    <input type="checkbox" data-service-id="3" data-price="120">
                    <span>Event Photography and Videography - 120 BD</span>
                </label>
            </div>
        </div>
        <div id="total-price">
            <p>Total Price: 0 BD</p>
        </div>
        <div class="form-buttons">
            <form method="post" action="confirm_booking.php">
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
