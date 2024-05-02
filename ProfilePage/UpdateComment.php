<?php
// Include database connection or initialization if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have established the database connection
    $conn = mysqli_connect("localhost", "root", "", "capstone");

    // Check if the comment_id, content, date, and time are sent via POST
    if (isset($_POST['comment_id']) && isset($_POST['content']) && isset($_POST['date']) && isset($_POST['time'])) {
        $comment_id = $_POST['comment_id'];
        $content = $_POST['content'];
        $date = $_POST['date'];
        $time = $_POST['time'];

        if (empty($content)) {
            echo 'empty_content_error'; // Return error message if content is empty
            exit();
        }
        // Check if the new content meets the character length condition
        if (strlen($content) < 20 || strlen($content) > 200) {
            echo 'char_limit_error'; // Return error message if character length condition is not met
            exit();
        }

        // Format the time to display only hour and minute (HH:MM)
        $formatted_time = date("H:i", strtotime($time));

        // Update the comment in the database
        $sql = "UPDATE commendation SET content = '$content', date = '$date', time = '$formatted_time' WHERE comment_id = $comment_id";
        if (mysqli_query($conn, $sql)) {
            echo 'success'; // Return success message
        } else {
            echo 'error'; // Return error message
        }
    } else {
        echo 'error'; // Return error message if data is not received
    }

    mysqli_close($conn); // Close the database connection
}
?>
