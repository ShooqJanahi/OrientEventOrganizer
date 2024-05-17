<?php
include 'debugging.php';

if (isset($_POST['submitted'])) {
    // First, create the User
    $menu = new Users();
    $menu->setEmail($_POST['Email']);
    $menu->setUsername($_POST['Username']);
    $menu->setPassword($_POST['Password']);
    $menu->setFirstName($_POST['FName']);
    $menu->setLastName($_POST['LName']);
    $menu->setPhoneNumber($_POST['PhoneNumber']);
    $menu->setUserType('employee'); // Ensure userType is set appropriately

    if (!$menu->initWithUsername()) {
        if ($menu->registerUser()) {
            // Now, create the Employee using the newly created userId
            $employee = new Employee();
            $employee->setUserId($menu->getUserId());
            $employee->setDepartment($_POST['Department']);
            $employee->setPosition($_POST['Position']);
            $employee->setJoinDate($_POST['JoinDate']);

            if ($employee->registerEmployee()) {
                echo 'Registered Successfully';
            } else {
                echo '<p class="error"> Employee registration not successful </p>';
            }
        } else {
            echo '<p class="error"> User registration not successful </p>';
        }
    } else {
        echo '<p class="error"> Username already exists </p>';
    }
}

include 'header.html';
?>

<h1>Employee Registration</h1>
<div id="stylized" class="myform"> 
    <form action="register_employee.php" method="post">
        <fieldset>
            <label><b>Enter First Name</b></label>
            <input type="text" name="FName" size="20" value="" required />
            <label><b>Enter Last Name</b></label>
            <input type="text" name="LName" size="20" value="" required />
            <label><b>Enter Username</b></label>
            <input type="text" name="Username" size="20" value="" required />
            <label><b>Enter Email</b></label>
            <input type="email" name="Email" size="50" value="" required />
            <label><b>Enter Password</b></label>
            <input type="password" name="Password" size="10" value="" required />
            <label><b>Enter Phone Number</b></label>
            <input type="text" name="PhoneNumber" size="15" value="" required />
            <label><b>Enter Department</b></label>
            <input type="text" name="Department" size="50" value="" required />
            <label><b>Enter Position</b></label>
            <input type="text" name="Position" size="50" value="" required />
            <label><b>Enter Join Date</b></label>
            <input type="date" name="JoinDate" value="" required />
            <div align="center">
                <input type="submit" value="Register" />
            </div>  
            <input type="hidden" name="submitted" value="1" />
        </fieldset>
    </form>    
    <div class="spacer"></div>;    
</div>    
<?php
include 'footer.html';
?>
