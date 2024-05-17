<?php
include 'debugging.php';
$page_title = 'Delete Menu';
include 'header.html';

$id = 0;

// Retrieve the parameter from the link or form submission
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    echo '<p class="error">Error has occurred</p>';
    include 'footer.html';
    exit();
}

echo '<h1>Delete Menu</h1>';

// Create a Menu object and initialize it with the menu ID
$menu = new Menu();
$menu->initWithmenuId($id);

if (isset($_POST['submitted'])) {
    // Test the value of the radio button
    if (isset($_POST['sure']) && ($_POST['sure'] == 'Yes')) {
        // Delete the record
        if ($menu->deleteMenu()) {
            echo '<p>Menu ' . $menu->getMenuName() . ' was deleted</p>';
        } else {
            echo '<p class="error">Error deleting the menu</p>';
        }
    } else {
        // Deletion not confirmed
        echo '<p>Menu ' . $menu->getMenuName() . ' deletion not confirmed</p>';
    }
} else {
    // Show the form
    echo '<div id="stylized" class="myform"> 
        <form action="delete_menu.php" method="post">
        <br />
        <h3>Name: ' . $menu->getMenuName() . '</h3></br>
        <label>Delete this menu?</label> <br/><br/>
        <input type="radio" name="sure" value="Yes" /><label>Yes</label>
        <input type="radio" name="sure" value="No" checked="checked" /><label>No</label>
        <input type="submit" class="DB4Button" name="submit" value="Delete" />
        
        <input type="hidden" name="submitted" value="TRUE">
        <input type="hidden" name="id" value="' . $id . '"/>
        </form>
        <div class="spacer"></div>
        </div>';   
}

include 'footer.html';
?>
