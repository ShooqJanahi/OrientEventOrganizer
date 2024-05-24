<?php

session_start();

?>


<html>
    <head>
        <title></title>
               <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- mobile metas -->
<meta name="viewport" content="initial-scale=1, maximum-scale=1">
<!-- site metas -->
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="">
<!-- bootstrap css -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<!-- style css -->
<link rel="stylesheet" type="text/css" href="css/style.css">
<!-- Responsive-->
<link rel="stylesheet" href="css/responsive.css">
<!-- fevicon -->
<link rel="icon" href="images/fevicon.png" type="image/gif" />
<!-- font css -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
<!-- Scrollbar Custom CSS -->
<link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
<!-- Tweaks for older IEs-->
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">

    
        <style>
            /* Dropdown menu styles */
            .nav-item ul {
                display: none;
                position: absolute;
                background-color: #f9f9f9;
                min-width: 200px;
                z-index: 1;
            }

            .nav-item:hover > ul {
                display: block;
            }

            .nav-item ul li {
                display: block;
                position: relative;
            }

            .nav-item ul li ul {
                display: none;
                position: absolute;
                left: 100%;
                top: 0;
                background-color: #f9f9f9;
            }

            .nav-item ul li:hover > ul {
                display: block;
            }

            .nav-item ul li a {
                color: #333;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
            }

            .nav-item ul li a:hover {
                background-color: #f1f1f1;
            }
        </style>
    </head>
    <body>
        
        


<div class="header_section header_bg">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.html"><img src="images/logo.png"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="searchAndBooking.php">Booking</a>
                    </li>
                    <?php if (!empty($_SESSION['userId']) && $_SESSION['userType'] === 'Client'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_reservation.php">Manage Reservation</a>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['userId']) && $_SESSION['userType'] === 'Admin'): ?>
                        <li class="nav-item">
                                <a class="nav-link" href="shop.php">Management</a>
                                <ul>
                                    <li><a href="#">Manage Employee</a>
                                        <ul>
                                            <li><a href="add_employee.php">Add Employee</a></li>
                                            <li><a href="view_employees.php">View Employee</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Manage Halls</a>
                                        <ul>
                                            <li><a href="add_hall.php">Add Hall</a></li>
                                            <li><a href="view_halls.php">View Halls</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Manage Clients</a>
                                        <ul>
                                            <li><a href="add_client.php">Add Client</a></li>
                                            <li><a href="view_clients.php">View Clients</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Manage Catering Menu details</a>
                                        <ul>
                                            <li><a href="add_menu.php">Add Menu</a></li>
                                            <li><a href="view_menus.php">View Menu</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                    <?php endif; ?>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <div class="login_bt">
                        <ul>
                                <?php if (!empty($_SESSION['userId'])): ?>
                                    <li><span class="user_icon"><i class="fa fa-user" aria-hidden="true"></i></span> <?php echo $_SESSION['username']; ?></li>
                                    <li><a href="logout.php">Logout</a></li>
                                <?php else: ?>
                                    <li><a href="LoginForm.php"><span class="user_icon"><i class="fa fa-user" aria-hidden="true"></i></span>SignIn</a></li>
                                <?php endif; ?>
                                <li><a href="#"><i class="fa fa-search" aria-hidden="true"></i></a></li>
                            </ul>
                    </div>
                </form>
            </div>
        </nav>
    </div>
</div>
        
        
    </body>
</html>
