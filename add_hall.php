<?php
include 'debugging.php';
include 'Upload.php'; // Include the Upload class

session_start(); // Ensure session is started

// Set a dummy session ID for testing purposes (remove this in production)
$_SESSION['uid'] = isset($_SESSION['uid']) ? $_SESSION['uid'] : 1;

if (isset($_POST['submitted'])) {
    $hall = new Hall();
    $hall->setHallName($_POST['hallName']);
    $hall->setLocation($_POST['location']);
    $hall->setDescription($_POST['description']);
    $hall->setRentalCharge($_POST['rentalCharge']);
    $hall->setCapacity($_POST['capacity']);

    // Handle file upload using the Upload class
    $upload = new Upload();
    $upload->setUploadDir('uploads/');

    // Ensure the upload directory exists and has the correct permissions
    if (!$upload->check_dir($upload->getUploadDir())) {
        if (!mkdir($upload->getUploadDir(), 0777, true)) {
            echo '<p class="error">Failed to create upload directory</p>';
            exit;
        } else {
            chmod($upload->getUploadDir(), 0777);
        }
    }

    $uploadErrors = $upload->upload('image');

    if (empty($uploadErrors)) {
        $hall->setImage($upload->getUploadDir() . $upload->getFilepath());

        if ($hall->registerHall()) {
            $hallId = $hall->getHallId(); // Retrieve the auto-incremented ID of the newly inserted hall

            $timingSlots = $_POST['timingSlots'];
            $allSuccess = true;

            foreach ($timingSlots as $slot) {
                $timingSlot = new HallsTimingSlots();
                $timingSlot->setTimingSlotStart($slot['start']);
                $timingSlot->setTimingSlotEnd($slot['end']);
                $timingSlot->setHallId($hallId);

                if (!$timingSlot->registerTimingSlot()) {
                    $allSuccess = false;
                    echo '<p class="error">Error adding timing slot: ' . $slot['start'] . ' - ' . $slot['end'] . '</p>';
                }
            }

            if ($allSuccess) {
                echo 'Hall and timing slots added successfully';
            } else {
                echo '<p class="error">Error adding some timing slots</p>';
            }
        } else {
            echo '<p class="error">Error adding hall</p>';
        }
    } else {
        foreach ($uploadErrors as $error) {
            echo '<p class="error">' . $error . '</p>';
        }
        echo '<p class="error">Error adding hall due to image upload issues</p>';
    }
}

include 'header.html';
?>

<h1>Add Hall</h1>
<div id="stylized" class="myform"> 
    <form action="add_hall.php" method="post" enctype="multipart/form-data">
        <fieldset>
            <label>Hall Name</label>
            <input type="text" name="hallName" size="20" value="" />
            <label>Image</label>
            <input type="file" name="image" size="20" />
            <label>Location</label>
            <input type="text" name="location" size="20" value="" />
            <label>Description</label>
            <textarea name="description" rows="4" cols="50"></textarea>
            <label>Rental Charge</label>
            <input type="text" name="rentalCharge" size="20" value="" />
            <label>Capacity</label>
            <input type="number" name="capacity" size="20" value="" />
            <label>Timing Slots</label>
            <div id="timingSlotsContainer">
                <div class="timingSlot">
                    <label>Start</label>
                    <input type="time" name="timingSlots[0][start]" size="20" value="" />
                    <label>End</label>
                    <input type="time" name="timingSlots[0][end]" size="20" value="" />
                </div>
            </div>
            <div align="center">
                <button type="button" onclick="addTimingSlot()">Add Another Timing Slot</button>
            </div>
            <div align="center">
                <input type="submit" value="Add Hall" />
            </div>  
            <input type="hidden" name="submitted" value="1" />
        </fieldset>
    </form>    
    <div class="spacer"></div>    
</div>    

<script>
let timingSlotIndex = 1;

function addTimingSlot() {
    var container = document.getElementById('timingSlotsContainer');
    var newSlot = document.createElement('div');
    newSlot.className = 'timingSlot';
    newSlot.innerHTML = `
        <label>Start</label>
        <input type="time" name="timingSlots[${timingSlotIndex}][start]" size="20" value="" />
        <label>End</label>
        <input type="time" name="timingSlots[${timingSlotIndex}][end]" size="20" value="" />
    `;
    container.appendChild(newSlot);
    timingSlotIndex++;
}
</script>

<?php
include 'footer.html';
?>
