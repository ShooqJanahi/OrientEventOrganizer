<?php
session_start();
$error = '';
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
            exit();
        } else {
            $error = 'Wrong Login Values';
        }
    } else {
        $error = 'Username and Password are required';
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
    <link href="css/style.css" rel="stylesheet"> <!-- Include your custom styles -->
    <style>
        .custom-login-form .form-group {
            margin-bottom: 1rem;
        }
        .custom-login-form .form-group label {
            margin-bottom: 0;
            display: inline-block;
            width: 120px; /* Adjust the width as needed */
        }
        .custom-login-form .container {
            margin-top: 150px; /* Adjust the margin-top to increase space */
        }
        .custom-login-form .card {
            width: 60%;
            padding: 20px;
        }
        /* Ensure links are blue */
        .custom-login-form a {
            color: blue;
        }
        .custom-login-form a:hover {
            color: darkblue;
        }
    </style>
</head>
<body>
    <?php include 'header.html'; ?>
    <div class="container custom-login-form d-flex justify-content-center">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title text-center">Login</h1>
                <form action="LoginForm.php" method="post">
                    <div class="form-group">
                        <label for="username"><b>Username</b></label>
                        <input type="text" class="form-control" id="Username" name="Username" placeholder="Enter Username" value="<?php echo $_POST['Username']; ?>" onblur="validate(this);" >
                        <span id="UsernameErr" style="color: red;"></span>
                    </div>
                    <div class="form-group">
                        <label for="password"><b>Password</b></label>
                        <input type="password" class="form-control" id="Password" name="Password" placeholder="Enter Password" value="<?php echo $_POST['Password']; ?>" onblur="validate(this);" >
                        <span id="PasswordErr" style="color: red;"></span>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Login</button>
                        <p class="mt-3">Don't have an account? <a href="Register.php">Register here</a>.</p>
                    </div>
                    <div class="error" style="color: red; text-align: center;"><?php echo $error; ?></div>
                    <input type="hidden" name="submitted" value="1" />
                </form>
            </div>
        </div>
    </div>
    <script>
    function validate(obj) {
        var errField = obj.id + 'Err';
        var value = obj.value.trim();

        if (value === '') {
            document.getElementById(errField).innerHTML = obj.id + ' field may not be blank';
        } else {
            document.getElementById(errField).innerHTML = '';
        }
    }
</script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<!-- Login form end -->

<?php 
include 'footer.html';
?>