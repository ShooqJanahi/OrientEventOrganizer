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

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }
    .myform {
        width: 50%;
        margin: 50px auto;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-size: 18px;
    }
    .myform fieldset {
        border: none;
    }
    .myform label {
        display: block;
        margin-bottom: 10px;
        font-size: 20px;
    }
    .myform input[type="text"],
    .myform input[type="password"] {
        width: calc(100% - 22px);
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 18px;
    }
    .myform input[type="submit"] {
        background-color: #ff6666; /* Light red color */
        color: white;
        padding: 15px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 20px;
        transition: background-color 0.3s;
    }
    .myform input[type="submit"]:hover {
        background-color: #ff4d4d; /* Slightly darker red on hover */
    }
    .error {
        color: red;
        font-size: 18px;
    }
</style>

<h1 style="text-align: center;">Add New Client</h1>
<div id="stylized" class="myform">
    <form action="add_client.php" method="post">
        <fieldset>
            <label>First Name</label>
            <input type="text" name="firstName" size="20" value="" placeholder="Enter first name" />
            <label>Last Name</label>
            <input type="text" name="lastName" size="20" value="" placeholder="Enter last name" />
            <label>Username</label>
            <input type="text" name="username" size="20" value="" placeholder="Enter username" />
            <label>Password</label>
            <input type="password" name="password" size="20" value="" placeholder="Enter password" />
            <label>Email</label>
            <input type="text" name="email" size="20" value="" placeholder="Enter email" />
            <label>Phone Number</label>
            <input type="text" name="phoneNumber" size="20" value="" placeholder="Enter phone number" />
            <label>Company Name</label>
            <input type="text" name="companyName" size="20" value="" placeholder="Enter company name" />
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
