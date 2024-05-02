<?php
session_start();

include "../db_connection.php";

if (isset($_POST["cancel_appointment"])) {
    // Get the appointment id from the POST data
    $appointment_id = $_POST["cancel_appointment"];

    // Update the status of the appointment to 'cancelled' in the database
    $sql_cancel_appointment = "UPDATE appointment SET status = 'cancelled' WHERE appointment_id = $appointment_id";

    // Execute the query to cancel the appointment
    if ($db->query($sql_cancel_appointment) === TRUE) {

        include "cancel_appointment_notification.php";

        // Get the user type to determine whose appointment is being cancelled
        $user_type = $_SESSION['user_type'];

        if ($user_type == 'student') {
            // If the user is a student, get the doctor's name
            $sql_get_doctor_name = "SELECT CONCAT(account.first_name, ' ', account.last_name) AS doctor_name FROM appointment INNER JOIN account ON appointment.faculty_id = account.account_id WHERE appointment.appointment_id = $appointment_id";
        } elseif ($user_type == 'faculty') {
            // If the user is a doctor, get the student's name
            $sql_get_doctor_name = "SELECT CONCAT(account.first_name, ' ', account.last_name) AS student_name FROM appointment INNER JOIN account ON appointment.student_id = account.account_id WHERE appointment.appointment_id = $appointment_id";
        }

        $result_get_doctor_name = $db->query($sql_get_doctor_name);

        if ($result_get_doctor_name->num_rows > 0) {
            $row_get_doctor_name = $result_get_doctor_name->fetch_assoc();
            if ($user_type == 'student') {
                $doctor_name = $row_get_doctor_name["doctor_name"];
            } elseif ($user_type == 'faculty') {
                $student_name = $row_get_doctor_name["student_name"];
            }
        }

        if ($user_type == 'student') {
            $_SESSION['success'] = "Your appointment with Dr. " . $doctor_name . " has been cancelled";
        } elseif ($user_type == 'faculty') {
            $_SESSION['success'] = "Your appointment with " . $student_name . " has been cancelled";
        }

        // Update the status of the corresponding slot to 'available'
        $sql_get_slot_id = "SELECT slot_id FROM appointment WHERE appointment_id = $appointment_id";
        $result_get_slot_id = $db->query($sql_get_slot_id);

        if ($result_get_slot_id->num_rows > 0) {
            $row_get_slot_id = $result_get_slot_id->fetch_assoc();
            $slot_id = $row_get_slot_id["slot_id"];

            // Update the status of the corresponding slot to 'available'
            $sql_update_slot_status = "UPDATE slot SET status = 'available' WHERE slot_id = $slot_id";
            if (!$db->query($sql_update_slot_status) === TRUE) {
                $_SESSION['error'] = "Error updating slot status: " . $db->error;
            }
        }
    } else {
        $_SESSION['error'] = "Error cancelling appointment: " . $db->error;
    }
} else {
    $_SESSION['error'] = "Error fetching appointment information: " . $db->error;
}

// Redirect to the appropriate page based on user type
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'student') {
    header("Location: StudentAppointmentView.php");
} elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'faculty') {
    header("Location: FacultyAppointmentView.php");
} else {
    // Redirect to a default page if user type is not set or unrecognized
    header("Location: ../Code/LoginPage.php");
}
exit();
?>
