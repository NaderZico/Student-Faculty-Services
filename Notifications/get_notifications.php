<?php
session_start();

$db = mysqli_connect("localhost", "root", "", "capstone");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (isset($_SESSION['user_id'])) {
    $account_id = $_SESSION['user_id'];
    $sql = "SELECT *, DATE_FORMAT(timestamp, '%d-%m-%Y %H:%i') AS formatted_timestamp 
    FROM notification WHERE account_id = $account_id ORDER BY timestamp DESC LIMIT 100";

    $result = $db->query($sql);
    
    // Check if there are notifications
    if ($result->num_rows > 0) {
        $notifications = array();
        while ($row = $result->fetch_assoc()) {
            $status = $row['status'] == 'read' ? 'read' : 'unread'; // Check the status of the notification
            $notifications[] = array(
                'notification_id' => $row['notification_id'],
                'message' => $row['message'],
                'timestamp' => $row['formatted_timestamp'],
                'status' => $status
            );
        }
        echo json_encode($notifications);
    } else {
        echo json_encode(array('message' => 'No new notifications'));
    }
} else {
    echo json_encode(array('message' => 'User not logged in'));
}
?>
