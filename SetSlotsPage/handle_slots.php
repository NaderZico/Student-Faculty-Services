<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'faculty') {
    header("Location: ../LoginPage/LoginPage.php");
    exit();
}

$db = mysqli_connect("localhost", "root", "", "capstone");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];



// Check the user type and the request method
if ($user_type == "faculty" && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user input for the slot time and date
    $time = $_POST['time'];
    $date = $_POST['date'];

    // Set status as available initially
    $status = 'available';

    // Check if the slot already exists
    $existing_slot_query = "SELECT COUNT(*) FROM slot WHERE faculty_id = '$user_id' AND time = '$time' AND date = '$date'";
    $existing_slot_result = $db->query($existing_slot_query);
    $existing_slot_count = $existing_slot_result->fetch_row()[0];

    if ($existing_slot_count > 0) {
        $_SESSION['error'] = "Slot already exists. Try again with a different timeslot.";
    } else {
        // Prepare SQL statement to insert data into the slots table
        $insert_slot_query = "INSERT INTO slot (faculty_id, time, date, status) VALUES ('$user_id', '$time', '$date', '$status')";
        $insert_slot_result = $db->query($insert_slot_query);

        if ($insert_slot_result === TRUE) {
            $_SESSION['success'] = "Slot set successfully!";
        } else {
            $_SESSION['error'] = "Error: " . $db->error;
        }
    }
} else {
    $_SESSION['error'] = "Only faculty members can set slots.";
}

header("Location: SetSlotsPage.php");
exit();
?>