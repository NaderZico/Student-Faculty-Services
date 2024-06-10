<?php
session_start();

// Unset the profile photo session variable
unset($_SESSION['profile_photo']);

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student' && $_SESSION['user_type'] !== 'faculty') {
    header("Location: ../LoginPage/LoginPage.php");
    exit();
}

// Check if a profile photo is being uploaded and if the upload was successful
if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    // Specify the directory where uploaded files will be stored
    $upload_dir = __DIR__ . "/uploads/";

    // Remove the old photo file if it exists
    if (isset($_SESSION['profile_photo'])) {
        $old_photo_path = $_SESSION['profile_photo'];
        if (file_exists($old_photo_path)) {
            unlink($old_photo_path); // Remove the old photo file
        }
    }

    // Get the name of the uploaded file and sanitize it
    $file_name = basename($_FILES['profile_photo']['name']);
    $file_name = preg_replace("/[^A-Za-z0-9_.]/", "", $file_name); // Remove non-alphanumeric characters

    // Generate a unique name for the file to avoid overwriting existing files
    $unique_name = uniqid() . '_' . $file_name;

    // Specify the path where the uploaded file will be moved to
    $target_path = $upload_dir . $unique_name;

    // Check if the file is an image
    $file_type = exif_imagetype($_FILES['profile_photo']['tmp_name']);
    if (!$file_type) {
        echo "Error: Uploaded file is not an image.";
        exit();
    }

    // Move the uploaded file to the specified location
    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_path)) {
        // Update the session variable with the path to the uploaded photo
        $_SESSION['profile_photo'] = 'uploads/' . $unique_name; // Relative path to the uploaded photo

        // Insert the file path into the database
        $conn = new mysqli("localhost", "root", "", "capstone");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $user_id = $_SESSION['user_id'];
        $user_type = $_SESSION['user_type'];

        $update_sql = "UPDATE account SET uploads = ? WHERE account_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $_SESSION['profile_photo'], $user_id);

        if ($stmt->execute()) {
            echo "File uploaded and database updated successfully.";
            // Redirect back to Student Profile page
            if ($user_type == 'student') {
                header("Location: StudentProfile.php");
            } else {
                header("Location: FacultyProfile.php");
            }
            // No need for exit() here
        } else {
            echo "Error: Failed to update file path in database.";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Error: Failed to upload file.";
        // No need for exit() here
    }
}
?>
