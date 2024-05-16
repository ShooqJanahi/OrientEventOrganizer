<?php

include 'header_admin.html';

$page_title = 'Delete Employee';

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
    $employee->deleteEmployee();
    echo "<h2> Employee deleted </h2>";
} else {
    echo '<h1>Delete Employee</h1>';
    echo '<div id="stylized" class="myform"> 
         <form action="delete_employee.php" method="post">
        <br />
        <h3>Are you sure you want to delete ' . $employee->getUsername() . '?</h3>
        <br />
        <input type="submit" class ="DB4Button" name="submit" value="Delete" />
        <input type="hidden" name="submitted" value="TRUE">
        <input type="hidden" name="id" value="' . $id . '"/>
        </form>
        <div class="spacer"></div>
        </div>';
}

include 'footer.html';
?>
