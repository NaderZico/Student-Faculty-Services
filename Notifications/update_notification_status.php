<?php
include "../db_connection.php";

if (isset($_POST['notification_id'])) {
    $notification_id = $_POST['notification_id'];

    // Prepare and execute the update query
    $updateStatusQuery = "UPDATE notification SET status = 'read' WHERE notification_id = ?";
    $stmt = $db->prepare($updateStatusQuery);
    $stmt->bind_param("i", $notification_id);

    if ($stmt->execute()) {
        // Query executed successfully
        echo "Notification marked as read";
    } else {
        // Error occurred during query execution
        echo "Error updating notification status: " . $db->error;
    }

    // Close prepared statement
    $stmt->close();
} else {
    // Notification ID not provided in the request
    echo "Notification ID not provided";
}
