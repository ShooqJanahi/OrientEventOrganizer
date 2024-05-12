<?php
// Initialize variables for error messages
$errors = [];
if (isset($_POST['submitted'])) {
    // Data validation
    if (empty($_POST['username']))
        $errors[] = 'You must enter a Username';
    if (empty($_POST['fName']))
        $errors[] = 'You must enter a First Name';
    if (empty($_POST['lName']))
        $errors[] = 'You must enter a Last Name';
    if (empty($_POST['password']))
        $errors[] = 'You must enter a Password';
    if (empty($_POST['email']))
        $errors[] = 'You must enter an Email';
    if (empty($_POST['phone']))
        $errors[] = 'You must enter a Phone number';

    // Check if there are errors
    if (!empty($errors)) {
        echo '<div >There were errors:<br>';
        foreach ($errors as $msg)
            echo "$msg<br> ";
        echo '</div>';

    } else {
        // If there are no errors 
        // Create user object and save user details
        $user = new Users();
        $user->setUserName($_POST['username']);
        $user->setUserType("C");
        $user->setFirstName($_POST['fName']);
        $user->setLastName($_POST['lName']);
        $user->setEmail($_POST['email']);
        $user->setPhoneNumber($_POST['phone']);
        $user->setPassword($_POST['password']);

        if ($user->initWithUsername()) {

            if ($user->registerUser()){
                echo 'Registerd Successfully';
                header('Location: index.php');
            }
            else {
                echo '<p class="error"> Not Successfull </p>';
            }
        } else {
            echo '<p class="error"> Username Already Exists </p>';
        }
    }
}// End of If-Submit statment
        ?>


        <!DOCTYPE html>
        <html>
            <head>
                <!-- basic -->
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <!-- mobile metas -->
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="viewport" content="initial-scale=1, maximum-scale=1">
                <!-- site metas -->
                <title>Blog</title>
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
            </head>
            <body>
                <div class="header_section header_bg">
                    <div class="container">
                        <nav class="navbar navbar-expand-lg navbar-light bg-light">
                            <a class="navbar-brand"href="index.html"><img src="images/logo.png"></a>
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
                                        <a class="nav-link" href="coffees.php">Coffees</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="shop.php">Shop</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="blog.php">Blog</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="contact.php">Contact</a>
                                    </li>
                                </ul>
                                <form class="form-inline my-2 my-lg-0">
                                    <div class="login_bt">
                                        <ul>
                                            <li><a href="#"><span class="user_icon"><i class="fa fa-user" aria-hidden="true"></i></span>Login</a></li>
                                            <li><a href="#"><i class="fa fa-search" aria-hidden="true"></i></a></li>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </nav>
                    </div>
                </div>
                <!-- header section end -->
                <!-- Registration form start -->
                <h1>User Registration</h1>
                <div id="stylized" class="myform"> 
                    <form action="Register.php" method="post">
                        <fieldset>
                            <label><b>Username</b></label>
                            <input type="text" name="username" size="20" placeholder="Enter Username" value="<?php echo $_POST['username']; ?>"><br>
                            <label><b>First Name</b></label>
                            <input type="text" name="fName" size="20" placeholder="Enter First Name" value="<?php echo $_POST['fName']; ?>"><br>
                            <label><b>Last Name</b></label>
                            <input type="text" name="lName" size="20" placeholder="Enter Last Name" value="<?php echo $_POST['lName']; ?>"><br>
                            <label><b>Phone Number</b></label>
                            <input type="text" name="phone" size="20" placeholder="Enter Phone Number" value="<?php echo $_POST['phone']; ?>"><br>
                            <label><b>Email</b></label>
                            <input type="email" name="email" size="50" placeholder="Enter Email" value="<?php echo $_POST['email']; ?>"><br>
                            <label><b>Password</b></label>
                            <input type="password" name="password" size="10" placeholder="Enter Password" value="<?php echo $_POST['password']; ?>">
                    <div align="center">
                        <input type ="submit" value ="Register" />
                    </div>  
                    <input type="hidden" name="submitted" value="1" />
                </fieldset>
            </form>    
            <div class="spacer"></div>   
        </div>    
        <!-- Registration form end -->
        <!-- footer section start -->
        <div class="footer_section layout_padding margin_top90">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="footer_social_icon">
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                        <div class="location_text">
                            <ul>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-phone" aria-hidden="true"></i><span class="padding_left_10">+01 1234567890</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-envelope" aria-hidden="true"></i><span class="padding_left_10">demo@gmail.com</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <textarea class="update_mail" placeholder="Your Email" rows="5" id="comment" name="Your Email"></textarea>
                            <div class="subscribe_bt"><a href="#"><i class="fa fa-arrow-right" aria-hidden="true"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer section end -->
    </body>
</html>



