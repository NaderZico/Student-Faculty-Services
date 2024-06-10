<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    header("Location: ../LoginPage/LoginPage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <link rel="stylesheet" href="BookAppointmentPage.css">
</head>

<body>
    <?php
    include "../Header/StudentHeader.php";
    include "../Chatbot/Chatbot.php";
    ?>

    <div class="section">
        <div class="form">
            <h2 class="appointment-header">Book An Appointment</h2>
            <h4 class="appointment-description">Choose a 15-minute timeslot to visit your preferred faculty</h4>
            <form id="appointment-form" method="POST" action="handle_appointments.php">
                <div class="field field1">
                    <label for="faculty-search">Search Faculty</label>
                    <input type="text" name="faculty-search" id="faculty-search" class="input-field faculty-search" placeholder="Search by name..." required oninput="searchFaculty()">
                    <div id="select-slot-container">
                        <select name="faculty-list" id="faculty-list" class="input-field faculty-list" required>
                            <option class="faculty-id" id="faculty-id" value="">Select Faculty</option>
                        </select>
                    </div>
                </div>
                <div class="field field2">
                    <label for="select-slot">Select Slot</label>
                    <select name="select-slot" id="select-slot" class="input-field select-slot" required>
                        <option class="slot-id" id="slot-id" value="">Select a Slot</option>
                    </select>
                </div>
                <input type="hidden" name="faculty_id" id="faculty_id" value="">
                <input type="hidden" name="slot_id" id="slot_id" value="">

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
                    <button type="submit" id="book-appointment-button" onclick="bookAppointment()">Book Appointment</button>
                </div>
            </form>
        </div>
    </div>
</body>


    <!-- Include the help modal HTML content -->
    <button class="help-button" onclick="toggleHelp()">
        <img src="../images/icons/question mark.jpg" class="help-icon">
    </button>

    <!-- Add the help modal container with the modal content -->
    <div class="modal-container" id="helpModalContainer">
        <div class="modal-content">
            <?php include "../HelpModal/help.html"; ?>
        </div>
    </div>
    <script>
        function searchFaculty() {
            var query = document.getElementById('faculty-search').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'handle_appointments.php?query=' + query, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var selectElement = document.getElementById('faculty-list');
                    selectElement.innerHTML = xhr.responseText;

                    // Check if there is any input in the search field
                    var inputText = query.trim();
                    if (inputText !== '') {
                        // Show the select bar if there is input
                        document.getElementById('select-slot-container').style.display = 'block';
                    } else {
                        // Hide the select bar if there is no input
                        document.getElementById('select-slot-container').style.display = 'none';
                    }

                    // Update slots whenever the faculty selection changes
                    selectElement.addEventListener('change', function() {
                        var selectedFacultyId = selectElement.value;
                        if (selectedFacultyId) {
                            selectSlots(selectedFacultyId);

                            // Update the search bar with the selected faculty name
                            var selectedFacultyName = selectElement.options[selectElement.selectedIndex].text;
                            document.getElementById('faculty-search').value = selectedFacultyName;
                        }
                    });

                    // Retrieve the selected faculty ID
                    var selectedFacultyId = selectElement.value;

                    // Update slots immediately after fetching the faculty list
                    if (selectedFacultyId) {
                        selectSlots(selectedFacultyId);
                    }
                }
            };
            xhr.send();
        }

        function selectSlots(facultyId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'handle_appointments.php?faculty_id=' + facultyId, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var slotElement = document.getElementById('select-slot');
                    slotElement.innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function bookAppointment() {
            var selectedFacultyId = document.getElementById('faculty-list').value;
            var selectedSlotId = document.getElementById('select-slot').value;

            // Check if both faculty and slot are selected
            if (selectedFacultyId && selectedSlotId) {
                var selectedFacultyName = document.getElementById('faculty-list').options[document.getElementById('faculty-list').selectedIndex].text;
                var selectedSlotDateTime = document.getElementById('select-slot').options[document.getElementById('select-slot').selectedIndex].text;

                // Display confirmation message with faculty name and time in bold using CSS
                var confirmation = confirm("Are you sure you want to book an appointment with \" " + selectedFacultyName + " \" on \" " + selectedSlotDateTime + " \"?");
                if (confirmation) {
                    document.getElementById('faculty_id').value = selectedFacultyId;
                    document.getElementById('slot_id').value = selectedSlotId;
                    document.getElementById("appointment-form").submit();
                }
            } else {
                alert("Please select both faculty and slot before booking the appointment.");
            }
        }
    </script>

</body>

</html>