<?php
include 'header.html';

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
        echo 'if';
    } else {
        // If there are no errors 
        // Create user object and save user details
        echo 'else';
        $user = new Users();
        $user->setUserName($_POST['username']);
        $user->setUserType("C");
        $user->setFirstName($_POST['fName']);
        $user->setLastName($_POST['lName']);
        $user->setEmail($_POST['email']);
        $user->setPhoneNumber($_POST['phone']);
        $user->setPassword($_POST['password']);
        echo 'hh';
        if ($user->initWithUsername()) {
            echo 'user';
            if ($user->registerUser()) {
                echo 'Registerd Successfully';
                //header('Location: index.php');
            } else {
                echo 'Not Successfull ';
            }
        } else {
            echo 'Username Already Exists';
        }
    }
}// End of If-Submit statment
?>

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

<?php
include 'footer.html';
?>



