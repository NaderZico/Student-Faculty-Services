<?php
// Get the student_id and faculty_id associated with the appointment
    $sql_get_ids = "SELECT student_id, faculty_id, slot_id FROM appointment WHERE appointment_id = $appointment_id";
    $result_ids = $db->query($sql_get_ids);

    if ($result_ids->num_rows > 0) {
        $row_ids = $result_ids->fetch_assoc();
        $student_id = $row_ids["student_id"];
        $faculty_id = $row_ids["faculty_id"];
        $slot_id = $row_ids["slot_id"];

        // Get slot information including date and time
        $sql_get_slot_info = "SELECT date, time FROM slot WHERE slot_id = $slot_id";
        $result_slot_info = $db->query($sql_get_slot_info);

        if ($result_slot_info->num_rows > 0) {
            $row_slot_info = $result_slot_info->fetch_assoc();
            $date = date('d-m-Y', strtotime($row_slot_info["date"]));
            $time = date('H:i', strtotime($row_slot_info["time"]));

            // Get faculty name
            $sql_get_faculty_name = "SELECT first_name, last_name FROM account WHERE account_id = $faculty_id";
            $result_faculty_name = $db->query($sql_get_faculty_name);

            if ($result_faculty_name->num_rows > 0) {
                $row_faculty_name = $result_faculty_name->fetch_assoc();
                $faculty_name = $row_faculty_name["first_name"] . " " . $row_faculty_name["last_name"];

                // Compose the notification message
                $message = "Awaiting Rating: You have completed the appointment with $faculty_name scheduled for $date at $time. Please rate your experience.";

                // Send the notification to the student
                sendNotification($student_id, $message);
            }
        }
    }