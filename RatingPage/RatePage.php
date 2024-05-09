<?php
session_start();

include "../db_connection.php";

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    header("Location: ../LoginPage/LoginPage.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Select appointments for the student where status is 'approved'
$sql = "SELECT a.appointment_id, CONCAT(ac.first_name, ' ', ac.last_name) AS faculty_name, sl.date, sl.time
        FROM appointment a
        JOIN account ac ON a.faculty_id = ac.account_id
        JOIN slot sl ON a.slot_id = sl.slot_id
        WHERE a.student_id = $student_id 
        AND a.status = 'approved' 
        AND a.rating_status = 'pending' 
        AND ac.account_type = 'faculty'";

// Execute the query and store the result
$result = $db->query($sql);

// Close the connection
$db->close();

// Set the submitted session variable
$_SESSION['submitted'] = isset($_SESSION['message']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Appointment</title>
    <link rel="stylesheet" href="RatePage.css">
</head>
<body>

<?php 
include "../Header/StudentHeader.php";
include "../Chatbot/Chatbot.php"; 
?>

<div class="main-content">
    <h2>Rate Appointments</h2>
    <?php 
    if (isset($_SESSION['message'])) {
        if ($_SESSION['submitted']) {
            echo '<p style="color: blue;">' . $_SESSION['message'] . '</p>';
        } else {
            echo '<p style="color: red;">' . $_SESSION['message'] . '</p>';
        }
        unset($_SESSION['message']); // Clear the message after displaying
    }
    ?>
    <div class="table-container">
        <table>
            <tr>
                <th>Appointment ID</th>
                <th>Faculty Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["appointment_id"] . "</td>";
                    echo "<td>" . $row["faculty_name"] . "</td>";

                    $date_formatted = date("d-m-Y", strtotime($row["date"]));
                    echo "<td>" . $date_formatted . "</td>";
                    echo "<td>" . $row["time"] . "</td>";
                    echo "<td>";
                    echo "<button class='rating-button' onclick='showRatingForm(" . $row["appointment_id"] . ")'>Rate</button>";
                    echo "</td>";
                    
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No appointments found to rate</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

    

<!-- Rating Form -->
<div id="ratingForm" class="modal-rate" style="display: none;">
    <div class="modal-content-rate">
        <span class="close" onclick="closeRatingForm()">&times;</span>
        <h2>Appointment Rating</h2>
        <p>Please rate the following aspects of the appointment:</p>
        <form class="rating-form" method="post" action="submit_rating.php">
            <input type="hidden" name="appointment_id" id="ratingAppointmentId" value="">
            
            <label>1. How would you rate a faculty member's ability to convey information clearly and concisely during the appointment?</label>
            <br>
            <label><input type="radio" name="concise" value="1" required> 1 (unsatisfied)</label>
            <label><input type="radio" name="concise" value="2"> 2</label>
            <label><input type="radio" name="concise" value="3"> 3</label>
            <label><input type="radio" name="concise" value="4"> 4</label>
            <label><input type="radio" name="concise" value="5"> 5 (satisfied)</label>
            <br><br>
            <label>2. To what extent did the faculty member actively involve you in the discussion and maintain your interest during the appointment?</label>
            <br>    
            <label><input type="radio" name="engaging" value="1" required> 1 (unsatisfied)</label>
            <label><input type="radio" name="engaging" value="2"> 2</label>
            <label><input type="radio" name="engaging" value="3"> 3</label>
            <label><input type="radio" name="engaging" value="4"> 4</label>
            <label><input type="radio" name="engaging" value="5"> 5 (satisfied)</label>
            <br><br>
            <label>3. How valuable were the insights provided by the faculty member in deepening your understanding of the subject matter during the appointment?</label>
            <br>  
            <label><input type="radio" name="insightful" value="1" required> 1 (unsatisfied)</label>
            <label><input type="radio" name="insightful" value="2"> 2</label>
            <label><input type="radio" name="insightful" value="3"> 3</label>
            <label><input type="radio" name="insightful" value="4"> 4</label>
            <label><input type="radio" name="insightful" value="5"> 5 (satisfied)</label>
            <br><br>            
            <button class="submit-rating" type="submit" name="submit_rating">Submit Rating</button>
        </form>
    </div>
</div><br><br><br><br><br>
  


        <!-- Include the help modal HTML content -->
        <button class="help-button" onclick="toggleHelp()">
    <img src="../Header/question mark.jpg" class="help-icon">
</button>

<!-- Add the help modal container with the modal content -->
<div class="modal-container" id="helpModalContainer">
    <div class="modal-content">
    <?php include "../LoginPage/help.html"; ?>
</div>
</div>
<script>
    function showRatingForm(appointmentId) {
        // Display the rating form modal
        document.getElementById('ratingForm').style.display = 'block';

        // Set the appointment ID in the hidden input field
        document.getElementById('ratingAppointmentId').value = appointmentId;
    }

    function closeRatingForm() {
        // Hide the rating form modal
        document.getElementById('ratingForm').style.display = 'none';
    }


</script>

</body>
</html>
