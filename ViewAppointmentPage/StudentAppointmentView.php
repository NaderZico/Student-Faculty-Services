<?php
session_start();

include "../db_connection.php";

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    header("Location: ../LoginPage/LoginPage.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Select appointments for the student where status is 'booked' and order them by date and time
$sql = "SELECT a.appointment_id, CONCAT(ac.first_name, ' ', ac.last_name) AS faculty_name, sl.date, sl.time
        FROM appointment a
        JOIN account ac ON a.faculty_id = ac.account_id
        JOIN slot sl ON a.slot_id = sl.slot_id
        WHERE a.student_id = $student_id AND a.status = 'booked' AND ac.account_type = 'faculty'
        ORDER BY sl.date, sl.time";

$result = $db->query($sql);

$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointment</title>
    <link rel="stylesheet" href="AppointmentView.css">
    <script>
        function confirmCancellation(form) {
            if (confirm("Are you sure you want to cancel this appointment?")) {
                form.submit();
            }
        }
    </script>
</head>

<body>

    <?php 
    include "../Header/StudentHeader.php";
    include "../Chatbot/Chatbot.php"; 
    ?>

    <div class="main-content">
        <h2>Appointments</h2>
        <h4 class="description">Manage your appointments here</h4>

        <div class='message'>
            <?php if(isset($_SESSION['success'])): ?>
                <span class="success-message"><?php echo $_SESSION['success']; ?></span>
                <?php unset($_SESSION['success']); // Clear the success message ?>
            <?php endif; ?>

            <!-- Display error message if it exists -->
            <?php if(isset($_SESSION['error'])): ?>
                <span class="error-message"><?php echo $_SESSION['error']; ?></span>
                <?php unset($_SESSION['error']); // Clear the error message ?>
            <?php endif; ?>
        </div>

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

                        // Convert the date format to DD-MM-YYYY
                        $date_formatted = date("d-m-Y", strtotime($row["date"]));
                        $time_formatted = date("H:i", strtotime($row["time"]));

                        echo "<td>" . $date_formatted . "</td>";
                        echo "<td>" . $time_formatted . "</td>";

                        echo "<td>";
                        echo "<form method='post' action='cancel_appointment.php' onsubmit='confirmCancellation(this); return false;'>";
                        echo "<input type='hidden' name='cancel_appointment' value='" . $row["appointment_id"] . "'>";
                        echo "<button class='cancel-button' type='submit'>Cancel</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    // Display a message if no appointments are found
                    echo "<tr><td colspan='5'>No appointments found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>

</body>

</html>
