<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student' && $_SESSION['user_type'] !== 'faculty') {
    header("Location: ../LoginPage/LoginPage.php");
    exit();
}


// Default user icon path
$default_user_icon = "../ProfilePage/User icon.png";
$user_type = $_SESSION['user_type'];

// Remove the profile photo if it exists
if (isset($_SESSION['profile_photo'])) {
    // Get the path to the uploaded photo
    $photo_path = $_SESSION['profile_photo'];

    // Check if the photo file exists
    if (file_exists($photo_path)) {
        // Attempt to delete the photo file
        if (unlink($photo_path)) {
            // Photo deletion successful
            unset($_SESSION['profile_photo']); // Remove photo path from session

            // Update the profile photo path in the session with the default user icon
            $_SESSION['profile_photo'] = $default_user_icon;

            // Insert the default file path into the database
            $db_host = "localhost";
            $db_user = "root";
            $db_password = "";
            $db_name = "capstone";

            $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $user_id = $_SESSION['user_id'];

            $update_sql = "UPDATE account SET uploads = ? WHERE account_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("si", $default_user_icon, $user_id);

            if ($stmt->execute()) {
                // Success: Profile photo updated with default user icon
            } else {
                // Error: Failed to update profile photo path in the database
            }

            $stmt->close();
            $conn->close();
        } else {
            // Failed to delete the photo file
        }
    } else {
        // Photo file does not exist
    }
} else {
    // No uploaded photo found in session
}

// Redirect back to the profile page
if($user_type=='student'){
    header("Location: StudentProfile.php");
}
    else{
        header("Location: FacultyProfile.php");

    }exit();
?>
