<?php
include 'debugging.php';
require_once 'Client.php'; // Ensure this path is correct
require_once 'Users.php';  // Ensure this path is correct

if (isset($_POST['submitted'])) {
    // Create and register the user
    $user = new Users();
    $user->setFirstName($_POST['firstName']);
    $user->setLastName($_POST['lastName']);
    $user->setUsername($_POST['username']);
    $user->setUserType('Client'); // Assuming all clients have the userType 'Client'
    $user->setPassword($_POST['password']);
    $user->setEmail($_POST['email']);
    $user->setPhoneNumber($_POST['phoneNumber']);

    if ($user->registerUser()) {
        // User registered successfully, now register the client
        $client = new Client();
        $client->setUserId($user->getUserId());
        $client->setCompanyName($_POST['companyName']);
        $client->setRoyaltyPoints(0); // Default value for new clients

        if ($client->registerClient($user)) {
            echo 'Client added successfully';
        } else {
            echo '<p class="error">Error adding client</p>';
        }
    } else {
        echo '<p class="error">Error adding user</p>';
    }
}

include 'header.html';
?>

<h1>Add Client</h1>
<div id="stylized" class="myform"> 
    <form action="add_client.php" method="post">
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
            <label>Company Name</label>
            <input type="text" name="companyName" size="20" value="" />
            <div align="center">
                <input type="submit" value="Add Client" />
            </div>  
            <input type="hidden" name="submitted" value="1" />
        </fieldset>
    </form>    
    <div class="spacer"></div>    
</div>    

<?php
include 'footer.html';
?>
