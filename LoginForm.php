<?php
include 'Login.php';
include 'Database.php';
// Check if the login form is submitted
if (isset($_POST['submitted'])) {
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
<h1>Log In</h1>
<div id="stylized" class="myform"> 
    <form action="LoginForm.php" method="post">
        <fieldset>
            <label><b>Username</b></label>
            <input type="text" name="Username" size="20" placeholder="Enter Username" value="<?php echo $_POST['Username']; ?>"><br>
            <label><b>Password</b></label>
            <input type="password" name="Password" size="20" placeholder="Enter Password" value="<?php echo $_POST['Password']; ?>">
            <p>Don't have an account? <a href="Register.php">Register here</a></p>
            <div align="center">
                <input type ="submit" value ="LogIn" />
            </div>  
            <input type="hidden" name="submitted" value="1" />
        </fieldset>
    </form>    
    <div class="spacer"></div>
</div>    
<!-- Login form end -->


