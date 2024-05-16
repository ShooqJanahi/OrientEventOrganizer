<?php
include 'header_admin.html';

$page_title = 'Edit Employee';

// Ensure 'id' is present in GET or POST
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    echo '<p class="error">No Employee ID Parameter</p>';
    include 'footer.html';
    exit();
}

$employee = new Employee();
$employee->initWithEmployeeId($id);

if (isset($_POST['submitted'])) {
    $oldUsername = $employee->getUsername();

    $employee->setUsername($_POST['FName']);
    $employee->setEmail($_POST['Email']);
    $employee->setPassword($_POST['Password']);
    $employee->setDepartment($_POST['Department']);
    $employee->setPosition($_POST['Position']);
    $employee->setJoinDate($_POST['JoinDate']);

    if (!$employee->initWithUsername() && $employee->getUsername() != $oldUsername) {
        echo "<h2> Thank you </h2><p>" . $employee->getUsername() . " already exists</p>";
        $employee->setUsername($oldUsername);
    } else {
        $errors = $employee->isValid();

        if (empty($errors)) {
            $employee->updateDB();
            echo "<h2> Thank you </h2><p>" . $employee->getUsername() . " is updated</p>";
        } else {
            echo '<div style="width:50%; background:#FFFFFF; margin:0 auto;">
                <p class="error"> The following errors occurred: <br/>';
            foreach ($errors as $err) {
                echo "$err <br/>";
            }
            echo '</p></div>';
        }
    }
}

echo '<h1>Edit Employee</h1>';
echo '<div id="stylized" class="myform"> 
         <form action="edit_employee.php" method="post">
        <br />
        <h3>Edit Employee: ' . $employee->getUsername() . '</h3>
        <br />
           <label>First Name</label>    <input type="text" name="FName" value="' . $employee->getUsername() . '" />
           <label>Email Address</label> <input type="text" name="Email" value="' . $employee->getEmail() . '"/>
           <label>Password</label>      <input type="password" name="Password" value="' . $employee->getPassword() . '"/>
           <label>Department</label>    <input type="text" name="Department" value="' . $employee->getDepartment() . '"/>
           <label>Position</label>      <input type="text" name="Position" value="' . $employee->getPosition() . '"/>
           <label>Join Date</label>     <input type="date" name="JoinDate" value="' . $employee->getJoinDate() . '"/>
           <input type="submit" class ="DB4Button" name="submit" value="Update" />
        
         <input type ="hidden" name="submitted" value="TRUE">
         <input type ="hidden" name="id" value="' . $id . '"/>
         </form>
        <div class="spacer"></div>
        </div>';

include 'footer.html';
?>
