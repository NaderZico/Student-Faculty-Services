<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginPage/LoginPage.php");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "capstone");
if ($conn->connect_error) {
    // Log the database connection error
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Retrieve the current photo path from the database
$select_sql = "SELECT uploads FROM account WHERE account_id = ?";
$stmt = $conn->prepare($select_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$response = ['status' => 'error', 'message' => 'Unknown error'];

if ($row && file_exists($row['uploads'])) {
    unlink($row['uploads']);

    // Update the database to reflect that the photo has been removed
    $update_sql = "UPDATE account SET uploads = NULL WHERE account_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $user_id);

    if ($update_stmt->execute()) {
        // Update the session variable
        unset($_SESSION['profile_photo']);
        
        // Prepare the success response with the user type
        $response = ['status' => 'success', 'user_type' => $_SESSION['user_type']];
    } else {
        // Log the error if the update fails
        error_log("Update failed: " . $update_stmt->error);
        $response['message'] = $update_stmt->error;
    }

    $update_stmt->close();
} else {
    // Log the error if no photo is found
    error_log("No photo found for user ID: " . $user_id);
    $response['message'] = 'No photo found';
}

$stmt->close();
$conn->close();

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
