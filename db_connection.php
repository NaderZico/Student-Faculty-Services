<?php
$db = mysqli_connect("localhost", "root", "", "capstone");
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
include_once "Notifications/send_notifications.php";

// Update slot status from available to closed if they have not been booked 12 hours before the current time
$currentDateTimeMinusTwelveHours = date('Y-m-d H:i:s', strtotime('-12 hours'));
$sql_update_slot = "UPDATE slot 
        SET status = 'closed' 
        WHERE status = 'available' 
        AND CONCAT(date, ' ', time) < '$currentDateTimeMinusTwelveHours'";
$db->query($sql_update_slot);

// Update appointment status based on the current date and time
$currentDateTime = date('Y-m-d H:i:s');
$sql_update_appointment = "UPDATE appointment AS a
                           INNER JOIN slot AS s ON a.slot_id = s.slot_id
                           SET a.status = 'completed' 
                           WHERE CONCAT(s.date, ' ', s.time) < '$currentDateTime' AND s.status = 'booked' AND a.status = 'booked'";
$db->query($sql_update_appointment);

// Check completed appointments then send notification for approval
$sql_completed_appointments = "SELECT a.appointment_id, a.faculty_id, s.date, s.time 
                                FROM appointment AS a 
                                INNER JOIN slot AS s ON a.slot_id = s.slot_id 
                                WHERE a.status = 'completed' AND s.status = 'booked' AND a.notification_sent = 0";
$result_completed_appointments = $db->query($sql_completed_appointments);
if ($result_completed_appointments->num_rows > 0) {
    while ($row = $result_completed_appointments->fetch_assoc()) {
        $appointment_id = $row['appointment_id'];
        $faculty_id = $row['faculty_id'];
        $date = date('d-m-Y', strtotime($row['date']));
        $time = date('H:i', strtotime($row['time']));
        $message = "Pending Approval: Appointment $appointment_id scheduled for $date at $time has been completed and urgently requires your approval.";
        sendNotification($faculty_id, $message);
        // Mark the notification as sent
        $sql_update_appointment_notification = "UPDATE appointment SET notification_sent = 1 WHERE appointment_id = $appointment_id";
        $db->query($sql_update_appointment_notification);
    }
}
