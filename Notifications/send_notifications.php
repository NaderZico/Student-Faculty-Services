<?php
function sendNotification($account_id, $message)
{

    $db = mysqli_connect("localhost", "root", "", "capstone");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Prepare the SQL statement to insert the notification
    $sql = "INSERT INTO notification (account_id, message, timestamp, status) VALUES (?, ?, NOW(), 'unread')";

    // Prepare the statement
    $stmt = $db->prepare($sql);

    // Bind parameters
    $stmt->bind_param("is", $account_id, $message);

    // Execute the statement
    if ($stmt->execute()) {
        // Notification successfully sent
        // echo "Notification sent successfully.";
    } else {
        // Error occurred while sending notification
        // echo "Error sending notification: " . $db->error;
    }

    // Close statement and database connection
    $stmt->close();
    $db->close();
}
