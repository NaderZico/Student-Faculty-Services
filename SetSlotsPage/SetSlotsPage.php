<?php 
session_start();

include "../db_connection.php";

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'faculty') {
    header("Location: ../LoginPage/LoginPage.php");
    exit();
} 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Slots Form</title>
    <link rel="stylesheet" href="SetSlotsPage.css">
</head>

<body>

    <?php 
  include "../Header/FacultyHeader.php";
  include "../Chatbot/Chatbot.php"; 
  ?>

    <div class="section">
        <div class="form">
            <h2 class="heading">Set Slot</h2>
            <h4 class="heading">Select convenient timeslots for accepting appointments from students</h4>

            <form id="slotForm" method="POST" action="handle_slots.php" onsubmit="return confirmSlot();">
                <div class="field">
                    <label for="time" class="label">Select Time</label>
                    <select name="time" id="time" class="select-style" required>
                        <option value="">Select a Time</option>
                        <?php
                            // Generate time options for the day
                            $startTime = 9;
                            $endTime = 17;

                            for ($hour = $startTime; $hour <= $endTime; $hour++) {
                                for ($minute = 0; $minute < 60; $minute += 15) {
                                    $time = sprintf("%02d:%02d", $hour, $minute);
                                    $endTimeMinutes = $minute + 15;
                                    $endTimeHour = $hour;

                                    // Adjust the end time if it exceeds 60 minutes
                                    if ($endTimeMinutes >= 60) {
                                        $endTimeMinutes = 0;
                                        $endTimeHour += 1;
                                    }
                                    $endTimeFormatted = sprintf("%02d:%02d", $endTimeHour, $endTimeMinutes);

                                    // Display the time slot
                                    echo "<option value=\"$time\">$time to $endTimeFormatted</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="field">
                    <label for="date" class="label">Select Date</label>
                    <input type="date" id="date" name="date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                        max="<?php echo date('Y-m-d', strtotime('+2 week')); ?>" class="input-style" required>
                </div>
                <div class="message">
                    <?php
                        if (isset($_SESSION['error'])) {
                            echo "<span class='error-message'>" . $_SESSION['error'] . "</span>";
                             unset($_SESSION['error']);
                        }
                        if (isset($_SESSION['success'])) {
                            echo "<span class='success-message'>" . $_SESSION['success'] . "</span>";
                            unset($_SESSION['success']);
                        }
                    ?>
                </div>

                <div class="button">
                    <button type="submit" name="submit" class="btn-primary">Set Slot</button>
                </div>
            </form>
        </div>
    </div><br><br><br>

    
        <!-- Include the help modal HTML content -->
<button class="help-button" onclick="toggleHelp()">
    <img src="../Header/question mark.jpg" class="help-icon">
</button>

<!-- Add the help modal container with the modal content -->
<div class="modal-container" id="helpModalContainer">
    <div class="modal-content">
    <?php include "../LoginPage/help.html"; ?>
    <link rel="stylesheet" href="../LoginPage/help.css">
</div>
</div>
    <script>
        function confirmSlot() {
            var selectedTime = document.getElementById('time').value;
            var selectedDate= document.getElementById('date').value;

            return confirm('Are you sure you want to set this slot for ' + selectedTime +' on '+ selectedDate+ '?');
        }
    </script>
</body>

</html>
