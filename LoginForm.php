<?php
// Check if the login form is submitted

if (isset($_POST['submitted'])) {
    include 'Login.php';
    $lgnObj = new Login(); // Create a new instance of the Login class
    // Get username and password from the form
    $username = trim($_POST['Username']);
    $password = trim($_POST['Password']);
    
    // Attempt to login
    if (!empty($username) && !empty($password)) {
        if ($lgnObj->login($username, $password)) {
            header('location: index.php');
        } else {
            echo 'Wrong Login Values';
        }
    } else {
        echo 'Username and Password are required';
    }
}
?>

<!-- Login form start -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            margin-bottom: 0;
            display: inline-block;
            width: 120px; /* Adjust the width as needed */
        }
        .container {
            margin-top: 50px;
        }
        .card {
            width: 60%;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title text-center">Login</h1>
                <form action="LoginForm.php" method="post">
                    <div class="form-group">
                        <label for="username"><b>Username</b></label>
                        <input type="text" class="form-control" id="Username" name="Username" placeholder="Enter Username">
                    </div>
                    <div class="form-group">
                        <label for="password"><b>Password</b></label>
                        <input type="password" class="form-control" id="Password" name="Password" placeholder="Enter Password">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Login</button>
                        <p class="mt-3">Don't have an account? <a href="Register.php">Register here</a>.</p>
                    </div>
                    <input type="hidden" name="submitted" value="1" />
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


<!-- Login form end -->


