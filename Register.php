<script>
    //fucntion to validate the entered data in fields
    function validate(obj) {
        var errField = obj.id+'Err';
        var valid = false;
        
        var value = obj.value.trim();
        
        if(value == '') {
            document.getElementById(errField).innerHTML = obj.id+' field may not be blank';
        } else {
            if (obj.id === 'email') {
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(value)) {
                document.getElementById(errField).innerHTML = 'Invalid email format';
            } else {
                document.getElementById(errField).innerHTML = '';
                valid = true;
            }                   
        } else {
            document.getElementById(errField).innerHTML = '';
            valid = true;
        }
        
    }
    
    return valid;
    }
</script>


<?php
include 'header.html';
include 'Users.php';

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
    <form name="cForm" id="cForm" action="Register.php" method="post">
        <fieldset>
            <label><b>Username</b></label>
            <input type="text" id="username" name="username" size="20" placeholder="Enter Username" autofocus onblur="validate(this);" value="<?php echo $_POST['username']; ?>">
            <span id="usernameErr" style="color: red;"></span><br>
            <label><b>First Name</b></label>
            <input type="text" id="fName" name="fName" size="20" placeholder="Enter First Name" autofocus onblur="validate(this);" value="<?php echo $_POST['fName']; ?>">
            <span id="fNameErr" style="color: red;"></span><br>
            <label><b>Last Name</b></label>
            <input type="text" id="lName" name="lName" size="20" placeholder="Enter Last Name" autofocus onblur="validate(this);" value="<?php echo $_POST['lName']; ?>">
            <span id="lNameErr" style="color: red;"></span><br>
            <label><b>Phone Number</b></label>
            <input type="text" id="phone" name="phone" size="20" placeholder="Enter Phone Number" autofocus onblur="validate(this);" value="<?php echo $_POST['phone']; ?>">
            <span id="phoneErr" style="color: red;"></span><br>
            <label><b>Email</b></label>
            <input type="email" id="email" name="email" size="50" placeholder="Enter Email" autofocus onblur="validate(this);" value="<?php echo $_POST['email']; ?>">
            <span id="emailErr" style="color: red;"></span><br>
            <label><b>Password</b></label>
            <input type="password" id="password" name="password" size="10" placeholder="Enter Password" autofocus onblur="validate(this);" value="<?php echo $_POST['password']; ?>">
            <span id="passwordErr" style="color: red;"></span><br>
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



