<?php
include "../db_connection.php";

// Start the session
session_start();

if (isset($_POST['approve_appointment'])) {
    $appointment_id = $_POST["approve_appointment"];
    $attendance_status = $_POST["attendance_status"];

    // Store the report if provided after trimming unnecessary white spaces
    $report = isset($_POST['appointment_report']) ? trim($_POST['appointment_report']) : '';

    // Fetch student's name based on appointment ID
    $sql_fetch_student_name = "SELECT a.first_name, a.last_name
                               FROM account a
                               JOIN appointment ap ON a.account_id = ap.student_id
                               WHERE ap.appointment_id = $appointment_id";
    $result_student_name = $db->query($sql_fetch_student_name);
    if ($result_student_name && $result_student_name->num_rows > 0) {
        $row_student_name = $result_student_name->fetch_assoc();
        $student_name = $row_student_name['first_name'] . ' ' . $row_student_name['last_name'];
    } else {
        // If unable to fetch student's name, set a default value
        $student_name = "Unknown Student";
    }

    if ($attendance_status === 'attended') {
        // Update rating_status to 'pending'
        $sql_update = "UPDATE appointment SET status = 'approved', rating_status = 'pending' WHERE appointment_id = $appointment_id";
        include "rate_appointment_notification.php";
    } elseif ($attendance_status === 'unattended') {
        // Update status to 'cancelled' and rating_status to 'unattended'
        $sql_update = "UPDATE appointment SET status = 'approved', rating_status = 'unattended' WHERE appointment_id = $appointment_id";
    }

    if ($db->query($sql_update) === TRUE) {
        $_SESSION['success'] = "Your appointment with $student_name has been approved";

        // Update the report in the database if it's not empty
        if (!empty($report)) {
            // Update the report in the database
            $sql_update_report = "UPDATE appointment SET report = ? WHERE appointment_id = ?";

            // Prepare the SQL statement for the report update
            $stmt_report = $db->prepare($sql_update_report);

            // Bind the parameters
            $stmt_report->bind_param("si", $report, $appointment_id);

            // Execute the statement
            if ($stmt_report->execute()) {
                // If report updated successfully, keep the same success message
            } else {
                // Set the error message if there's an error updating the report
                $_SESSION['error'] = "Error updating report: " . $stmt_report->error;
            }

            // Close the statement for the report update
            $stmt_report->close();
        }
    } else {
        // Set the error message if there's an error updating the appointment status
        $_SESSION['error'] = "Error updating appointment status: " . $db->error;
    }

    // Redirect to another page after processing
    header("Location:FacultyAppointmentView.php");
    exit();
}
?>
