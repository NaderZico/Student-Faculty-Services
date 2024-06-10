<?php
session_start();

include "../db_connection.php";

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'faculty') {
    header("Location: ../LoginPage/LoginPage.php");
    exit();
}

// Get the logged-in faculty id (consider using prepared statements here)
$faculty_id = $_SESSION["user_id"];

// Query the appointment table to get the booked appointments of the faculty member
$sql_select_booked_appointments = "SELECT a.appointment_id, 
    SUBSTRING(ac.email, 1, 9) AS student_id,
    CONCAT(ac.first_name, ' ', ac.last_name) AS student_name, 
    sl.date, sl.time, 'booked' as status
    FROM appointment a
    JOIN account ac ON a.student_id = ac.account_id
    JOIN slot sl ON a.slot_id = sl.slot_id
    WHERE a.faculty_id = $faculty_id AND a.status = 'booked' AND ac.account_type = 'student'
    ORDER BY sl.date ASC, sl.time ASC";

// Query to get completed appointments of the faculty member
$sql_select_completed_appointments = "SELECT a.appointment_id, 
    SUBSTRING(ac.email, 1, 9) AS student_id,
    CONCAT(ac.first_name, ' ', ac.last_name) AS student_name, 
    sl.date, sl.time, 'completed' as status
    FROM appointment a
    JOIN account ac ON a.student_id = ac.account_id
    JOIN slot sl ON a.slot_id = sl.slot_id
    WHERE a.faculty_id = $faculty_id AND a.status = 'completed' AND ac.account_type = 'student'
    ORDER BY sl.date ASC, sl.time ASC";

// Execute the queries and store the results (add error handling)
$bookedAppointments = $db->query($sql_select_booked_appointments);
$completedAppointments = $db->query($sql_select_completed_appointments);

// Close the connection
$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <link rel="stylesheet" href="AppointmentView.css">
</head>

<body>
    <?php 
    include "../Header/FacultyHeader.php"; 
    include "../Header/SearchProfile.php";
    include "../Chatbot/Chatbot.php";
    ?>

    <div class="main-content">
        <h2>Appointments</h2>
        <h4 class="description">Manage your appointments here</h4>

        <div class='message'>
            <?php if (isset($_SESSION['success'])) : ?>
                <span class="success-message"><?php echo $_SESSION['success']; ?></span>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])) : ?>
                <span class="error-message"><?php echo $_SESSION['error']; ?></span>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($appointment = $bookedAppointments->fetch_assoc()) {
                    echo "<tr class='booked'>";
                    echo "<td>{$appointment['appointment_id']}</td>";
                    echo "<td>{$appointment['student_id']}</td>";
                    echo "<td>{$appointment['student_name']}</td>";

                    $date_formatted = date("d-m-Y", strtotime($appointment['date']));
                    $time_formatted = date("H:i", strtotime($appointment['time']));
                    echo "<td>{$date_formatted}</td>";
                    echo "<td>{$time_formatted}</td>";
                    echo "<td>{$appointment['status']}</td>";
                    echo "<td>";
                    echo "<button type='button' class='cancel-button' onclick='confirmCancellation({$appointment['appointment_id']})'>Cancel</button>";
                    echo "<form id='cancel_form_{$appointment['appointment_id']}' method='post' action='cancel_appointment.php' style='display: none;'>";
                    echo "<input type='hidden' name='cancel_appointment' value='{$appointment['appointment_id']}'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }

                while ($appointment = $completedAppointments->fetch_assoc()) {
                    echo "<tr class='completed'>";
                    echo "<td>{$appointment['appointment_id']}</td>";
                    echo "<td>{$appointment['student_id']}</td>";
                    echo "<td>{$appointment['student_name']}</td>";

                    $date_formatted = date("d-m-Y", strtotime($appointment['date']));
                    $time_formatted = date("H:i", strtotime($appointment['time']));
                    echo "<td>{$date_formatted}</td>";
                    echo "<td>{$time_formatted}</td>";
                    echo "<td>{$appointment['status']}</td>";
                    echo "<td><button class='Approve-button' type='button' onclick='showForm(\"approve_form_{$appointment['appointment_id']}\")'>Approve</button></td>";
                    echo "</tr>";

                    echo "<tr id='approve_form_{$appointment['appointment_id']}' class='hidden-form'>";
                    echo "<td colspan='7'>";
                    echo "<form class='approval-form' method='post' action='approve_appointment.php'>";
                    echo "<input type='hidden' name='approve_appointment' value='{$appointment['appointment_id']}'>";
                    echo "<label><input type='radio' name='attendance_status' value='attended' required> The student has attended the appointment</label>";
                    echo "<label><input type='radio' name='attendance_status' value='unattended' required> The student did not attend the appointment</label>";
                    echo "<label>Appointment report: <textarea name='appointment_report' maxlength='350' placeholder='Write a report (optional)'></textarea></label><br>";
                    echo "<button class='Approve-button' type='submit'>Approve Appointment</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <button class="help-button" onclick="toggleHelp()">
        <img src="../images/icons/question mark.jpg" class="help-icon">
    </button>

    <div class="modal-container" id="helpModalContainer">
        <div class="modal-content">
            <?php include "../HelpModal/help.html"; ?>
        </div>
    </div>

    <script>
        function showForm(formId) {
            var form = document.getElementById(formId);
            if (form) {
                form.classList.toggle('show');
            } else {
                console.error("Form not found:", formId);
            }
        }

        function confirmCancellation(appointmentId) {
            if (confirm('Are you sure you want to cancel this appointment?')) {
                document.getElementById('cancel_form_' + appointmentId).submit();
            }
        }
    </script>
</body>
</html>