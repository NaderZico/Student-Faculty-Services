<?php

session_start();

include "../db_connection.php";

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];


// Check if the search query is set and not empty
if (isset($_GET['query']) && !empty($_GET['query'])) {

    // Sanitize the search query to prevent SQL injection
    $search_query = mysqli_real_escape_string($db, $_GET['query']);

    // Query to search for faculty names based on the search query
    $sql = "SELECT faculty_id, first_name, last_name 
        FROM faculty 
        INNER JOIN account ON faculty.faculty_id = account.account_id
        WHERE account.account_type = 'faculty' 
        AND CONCAT(account.first_name, ' ', account.last_name) LIKE '%$search_query%'";

    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {

        // Fetch and output faculty names as options

        while ($row = mysqli_fetch_assoc($result)) {

            echo "<option value='" . $row['faculty_id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
        }
    } else {

        // If no matching faculty names are found

        echo "<option value=''>No faculty found</option>";
    }
}



if (isset($_GET['faculty_id'])) {

    $faculty_id = intval($_GET['faculty_id']);



    $sql = "SELECT slot_id, time, date FROM slot WHERE faculty_id = $faculty_id AND status = 'available'
    ORDER BY CONCAT(date, ' ', time) ASC";

    $result = $db->query($sql);

    if ($result) {

        if (mysqli_num_rows($result) > 0) {

            while ($row = $result->fetch_assoc()) {

                // Concatenate date and time

                $slotDateTime = date('d/m H:i', strtotime($row['time'] . ' ' . $row['date']));

                echo "<option value='" . $row['slot_id'] . "'>" . $slotDateTime . "</option>";
            }
        } else {

            echo "<option value=''>No available slots</option>";
        }
    } else {
        echo "<option value=''>Error fetching slots</option>";
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION['user_id'];
    $faculty_id = $_POST['faculty_id'];
    $slot_id = $_POST['slot_id'];

    // Begin a transaction
    $db->begin_transaction();

    // Get the date and time from the slot table
    $sql_get_slot = "SELECT date, time FROM slot WHERE slot_id = '$slot_id' FOR UPDATE";
    $result_get_slot = $db->query($sql_get_slot);

    if ($result_get_slot && $result_get_slot->num_rows > 0) {
        $row_get_slot = $result_get_slot->fetch_assoc();
        $date = $row_get_slot['date'];
        $time = $row_get_slot['time'];

        // Check if the slot is already booked
        $sql_check_slot = "SELECT status FROM slot WHERE slot_id = '$slot_id' FOR UPDATE";
        $result_check_slot = $db->query($sql_check_slot);

        if ($result_check_slot && $result_check_slot->num_rows > 0) {
            $row_check_slot = $result_check_slot->fetch_assoc();
            $slot_status = $row_check_slot['status'];

            if ($slot_status == 'available') {
                // Check if the student already has an appointment booked at the same date and time with any faculty
                $sql_check_appointment = "SELECT COUNT(*) FROM appointment 
                                          INNER JOIN slot ON appointment.slot_id = slot.slot_id
                                          WHERE student_id = '$student_id' 
                                          AND slot.date = '$date' 
                                          AND slot.time = '$time' 
                                          AND appointment.status='booked' FOR UPDATE";
                $result_check_appointment = $db->query($sql_check_appointment);

                if ($result_check_appointment) {
                    $count_check_appointment = $result_check_appointment->fetch_row()[0];

                    if ($count_check_appointment == 0) {
                        // Insert the appointment
                        $sql_insert_appointment = "INSERT INTO appointment (student_id, faculty_id, slot_id, status) VALUES ('$student_id', '$faculty_id', '$slot_id', 'booked')";
                        if ($db->query($sql_insert_appointment)) {
                            // Update the slot status
                            $sql_update_slot = "UPDATE slot SET status = 'booked' WHERE slot_id = '$slot_id'";
                            if ($db->query($sql_update_slot)) {
                                // Retrieve student's name and email from the database
                                $sql_student_info = "SELECT first_name, last_name, email FROM Account WHERE account_id = $student_id";
                                $result_student_info = $db->query($sql_student_info);

                                if ($result_student_info && $result_student_info->num_rows > 0) {
                                    $row_student_info = $result_student_info->fetch_assoc();
                                    $student_name = $row_student_info['first_name'] . ' ' . $row_student_info['last_name'];
                                    $email = $row_student_info['email'];

                                    // Extract the first nine characters from the email
                                    $student_id = substr($email, 0, 9);

                                    // Compose the notification message
                                    $formatted_date = date('d-m-Y', strtotime($date));
                                    $formatted_time = date('H:i', strtotime($time));
                                    $message = "Appointment Scheduled: Student $student_name has scheduled an appointment with you on $formatted_date at $formatted_time. Student ID: $student_id.";

                                    sendNotification($faculty_id, $message);

                                    $db->commit();
                                    $_SESSION['success'] = "Appointment booked successfully!";
                                } else {
                                    $db->rollback();
                                    $_SESSION['error'] = "Failed to retrieve student information.";
                                }
                            } else {
                                $db->rollback();
                                $_SESSION['error'] = "Failed to update slot status.";
                            }
                        } else {
                            $db->rollback();
                            $_SESSION['error'] = "Failed to book appointment.";
                        }
                    } else {
                        $db->rollback();
                        $_SESSION['error'] = "You already have an appointment booked at the same date and time.";
                    }
                } else {
                    $db->rollback();
                    $_SESSION['error'] = "Error checking existing appointments.";
                }
            } else {
                $db->rollback();
                $_SESSION['error'] = "The selected slot is no longer available. Please select another timeslot.";
            }
        } else {
            $db->rollback();
            $_SESSION['error'] = "Error fetching slot status.";
        }
    } else {
        $db->rollback();
        $_SESSION['error'] = "The slot you selected has been canceled. Please select another timeslot.";
    }

    // Redirect back to the booking page
    header("location: BookAppointmentPage.php");
    exit();
}

?>