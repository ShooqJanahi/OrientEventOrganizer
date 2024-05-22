<?php
include 'debugging.php';
require_once 'Employee.php'; // Ensure this path is correct
require_once 'Users.php';    // Ensure this path is correct

if (isset($_POST['submitted'])) {
    // Create and register the user
    $user = new Users();
    $user->setFirstName($_POST['firstName']);
    $user->setLastName($_POST['lastName']);
    $user->setUsername($_POST['username']);
    $user->setUserType('Employee'); // Assuming all employees have the userType 'Employee'
    $user->setPassword($_POST['password']);
    $user->setEmail($_POST['email']);
    $user->setPhoneNumber($_POST['phoneNumber']);

    if ($user->registerUser()) {
        // User registered successfully, now register the employee
        $employee = new Employee();
        $employee->setUserId($user->getUserId());
        $employee->setDepartment($_POST['department']);
        $employee->setPosition($_POST['position']);
        $employee->setJoinDate($_POST['joinDate']);

        if ($employee->registerEmployee()) {
            echo 'Employee added successfully';
        } else {
            error_log("Error adding employee: " . $employee->getLastError());
            echo '<p class="error">Error adding employee. Please check the logs for details.</p>';
        }
    } else {
        error_log("Error adding user: " . $user->getLastError());
        echo '<p class="error">Error adding user. Please check the logs for details.</p>';
    }
}

include 'header.html';
?>

<h1>Add Employee</h1>
<div id="stylized" class="myform"> 
    <form action="add_employee.php" method="post">
        <fieldset>
            <label>First Name</label>
            <input type="text" name="firstName" size="20" value="" />
            <label>Last Name</label>
            <input type="text" name="lastName" size="20" value="" />
            <label>Username</label>
            <input type="text" name="username" size="20" value="" />
            <label>Password</label>
            <input type="password" name="password" size="20" value="" />
            <label>Email</label>
            <input type="text" name="email" size="20" value="" />
            <label>Phone Number</label>
            <input type="text" name="phoneNumber" size="20" value="" />
            <label>Department</label>
            <input type="text" name="department" size="20" value="" />
            <label>Position</label>
            <input type="text" name="position" size="20" value="" />
            <label>Join Date</label>
            <input type="date" name="joinDate" value="" />
            <div align="center">
                <input type="submit" value="Add Employee" />
            </div>  
            <input type="hidden" name="submitted" value="1" />
        </fieldset>
    </form>    
    <div class="spacer"></div>    
</div>    

<?php
include 'footer.html';
?>

