<?php
// Fetch appointment information to send as a notification
        $sql_appointment_info = "SELECT slot.date, slot.time, 
            student.first_name AS student_first_name, student.last_name AS student_last_name, 
            faculty.first_name AS faculty_first_name, faculty.last_name AS faculty_last_name,
            appointment.student_id, appointment.faculty_id
            FROM appointment 
            INNER JOIN slot ON appointment.slot_id = slot.slot_id
            INNER JOIN account AS student ON appointment.student_id = student.account_id 
            INNER JOIN account AS faculty ON appointment.faculty_id = faculty.account_id 
            WHERE appointment.appointment_id = $appointment_id";

        $result_appointment_info = $db->query($sql_appointment_info);

        if ($result_appointment_info->num_rows > 0) {
            $row_appointment_info = $result_appointment_info->fetch_assoc();
            $date = date('d-m-Y', strtotime($row_appointment_info["date"]));
            $time = date('H:i', strtotime($row_appointment_info["time"]));
            $student_name = $row_appointment_info["student_first_name"] . " " . $row_appointment_info["student_last_name"];
            $faculty_name = $row_appointment_info["faculty_first_name"] . " " . $row_appointment_info["faculty_last_name"];
            $student_id = $row_appointment_info["student_id"];
            $faculty_id = $row_appointment_info["faculty_id"];

            $message = "Appointment Cancelled: ";

            if ($_SESSION['user_type'] == 'faculty') {
                $message .= "$faculty_name has cancelled the appointment scheduled for $date at $time.";
                sendNotification($student_id, $message);
            } else {
                $message .= "$student_name has cancelled the appointment scheduled for $date at $time.";
                sendNotification($faculty_id, $message);
            }
        }
