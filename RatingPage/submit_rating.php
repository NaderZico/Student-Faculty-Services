<?php
session_start();

// Include database connection
include "../db_connection.php";

// Check if the form has been submitted and appointment ID is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_rating'])) {
    // Retrieve appointment ID from the form submission
    $appointment_id = $_POST['appointment_id'];

    // Retrieve ratings from the form submission
    $concise = $_POST['concise'];
    $engaging = $_POST['engaging'];
    $insightful = $_POST['insightful'];

    // Multiply each rating by 20 to scale it to a range of 0 to 100
    $concise_score = $concise * 20;
    $engaging_score = $engaging * 20;
    $insightful_score = $insightful * 20;

    // Retrieve student_id and faculty_id from the appointment table
    $appointment_query = "SELECT student_id, faculty_id FROM appointment WHERE appointment_id = $appointment_id";
    $appointment_result = $db->query($appointment_query);

    if ($appointment_result && $appointment_result->num_rows > 0) {
        $row = $appointment_result->fetch_assoc();
        $student_id = $row['student_id'];
        $faculty_id = $row['faculty_id'];

        // Insert the ratings and related IDs into the rating table
        $insert_sql = "INSERT INTO rating (appointment_id, rater_student_id, rated_faculty_id, concise_score, engaging_score, insightful_score) 
                       VALUES ($appointment_id, $student_id, $faculty_id, $concise_score, $engaging_score, $insightful_score)";
        $insert_result = $db->query($insert_sql);

        if ($insert_result) {
            // Update the rating status to 'approved' in the appointment table
            $update_sql = "UPDATE appointment SET rating_status = 'rated' WHERE appointment_id = $appointment_id";
            $update_result = $db->query($update_sql);

            if ($update_result) {
                $_SESSION['message'] = "Rating submitted successfully!";
            } else {
                $_SESSION['message'] = "Error updating rating status: " . $db->error;
            }
        } else {
            $_SESSION['message'] = "Error inserting ratings: " . $db->error;
        }
    } else {
        $_SESSION['message'] = "Error retrieving appointment details: " . $db->error;
    }
    header("Location:RatePage.php");
    exit();
}
?>


