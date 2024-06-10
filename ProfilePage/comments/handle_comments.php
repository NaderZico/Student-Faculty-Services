<?php
include_once "../Notifications/send_notifications.php";

ob_start(); // Start output buffering

$conn = mysqli_connect("localhost", "root", "", "capstone");

// Check if form is submitted and insert comment
if (isset($_POST['comment-submit'])) {
    setComments($conn);
}

// Check if delete button is clicked
if (isset($_POST['delete-comment'])) {
    deleteComment($conn);
}



// Display comments
function getComments($conn)
{
    $user_id = $_SESSION['user_id'];
    $sql1 = "SELECT c.sender_id, c.receiver_id, c.content, c.date, c.time, a_sender.first_name AS sender_first_name, a_sender.last_name AS sender_last_name, c.comment_id 
             FROM commendation c
             INNER JOIN account a_receiver ON a_receiver.account_id = c.receiver_id
             INNER JOIN account a_sender ON a_sender.account_id = c.sender_id
             WHERE c.receiver_id = $user_id";

    $result = mysqli_query($conn, $sql1);
    $count = 0; 
    while ($row = $result->fetch_assoc()) {
        $count++; // Increment counter for each comment
        // Define background color based on the count
        $border_color = ($count % 2 == 0) ? '#467495' : '#8CA4D1'; // Alternating colors

        echo "<div style='border: 1px solid $border_color; padding: 5px; margin-bottom: 5px; position: relative;'>";

        echo "<strong>{$row['sender_first_name']} {$row['sender_last_name']}</strong><br>";

        echo "<span style='font-size: 12px; color: #666;' id='date{$row['comment_id']}'>Date: {$row['date']}</span>";
        echo "<span style='font-size: 12px; color: #666;' id='time{$row['comment_id']}'>  Time: " . date('H:i', strtotime($row['time'])) . "</span><br>";
        echo "{$row['content']}";

        // Add delete button

        echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' style='position: absolute; top: 5px; right: 5px;' onsubmit='return confirm(\"Are you sure you want to delete this comment?\");'>";
        echo "<input type='hidden' name='comment_id' value='{$row['comment_id']}'>";
        echo "<button type='submit' name='delete-comment' class='delete-button'>Delete</button>";
        echo "</form>";

        echo "</div>";
    }
}



function deleteComment($conn)
{
    $comment_id = $_POST['comment_id'];
    $sql = "DELETE FROM commendation WHERE comment_id = $comment_id";
    if (mysqli_query($conn, $sql)) {
        // Successfully deleted from database
        echo "<script>window.location.href = window.location.href;</script>";
        exit();
    } else {
        // Error in SQL query
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }
}



// Insert comment into database
function setComments($conn)
{
    ob_start(); // Start output buffering to prevent any output before header() call
    if (!empty($_POST['content'])) { // Check if the comment content is not empty
        $receiver_id = $_POST['receiver_id'];
        $sender_id = $_POST['sender_id'];
        $content = $_POST['content'];
        $date = $_POST['date'];
        $time = $_POST['time'];

        // Check if the length of the comment is within the specified range
        if (strlen($content) < 20 || strlen($content) > 200) {
            // Set error message
            $_SESSION['comment_error'] = "Comment must be between 20 and 200 characters long.";
            return; // Stop further execution
        }

        // Check if the sender has already written a comment for the receiver
        $sql_check_comment = "SELECT COUNT(*) AS num_comments FROM commendation WHERE receiver_id = $receiver_id AND sender_id = $sender_id";
        $result_check_comment = mysqli_query($conn, $sql_check_comment);
        $row_check_comment = mysqli_fetch_assoc($result_check_comment);
        if ($row_check_comment['num_comments'] > 0) {
            // Set error message
            $_SESSION['comment_error'] = "You have already written a comment for this user. You can write only one comment for each user";
            return; // Stop further execution
        }

        // Fetch sender's name
        $sql_sender_name = "SELECT first_name, last_name FROM account WHERE account_id = $sender_id";
        $result_sender_name = mysqli_query($conn, $sql_sender_name);
        $row_sender_name = mysqli_fetch_assoc($result_sender_name);
        $sender_name = $row_sender_name['first_name'] . " " . $row_sender_name['last_name'];

        $sql = "INSERT INTO commendation (receiver_id, sender_id, content, date, time) VALUES ('$receiver_id', '$sender_id', '$content', '$date', '$time')";
        if (mysqli_query($conn, $sql)) {

            // Send notification to the receiver
            $message = "$sender_name has posted a new comment on your profile.";
            sendNotification($receiver_id, $message);

            ob_end_clean(); // Clean the output buffer before header() call
            // Redirect to the same page after inserting the comment
            header("Location: " . $_SERVER['PHP_SELF']);
        } else {
            // Error in SQL query
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }
    }
    ob_end_flush(); // Flush the output buffer in case of error
}





// Function to check if comments exist for the current user
function checkCommentsExist($conn, $user_id)
{
    $sql = "SELECT COUNT(*) AS num_comments FROM commendation WHERE receiver_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['num_comments'] > 0;
}

// Display comments for view profile
function getViewProfileComments($conn, $profile_id)
{
    $profile_id = mysqli_real_escape_string($conn, $profile_id);
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
    $sql = "SELECT sender_id, receiver_id, content, date, time, first_name, last_name, comment_id FROM commendation
    INNER JOIN account ON account.account_id = commendation.sender_id
    WHERE commendation.receiver_id = $profile_id";

    $result = mysqli_query($conn, $sql);
    $count = 0; // Initialize a counter variable
    while ($row = $result->fetch_assoc()) {
        $count++; // Increment counter for each comment
        // Define background color based on the count
        $border_color = ($count % 2 == 0) ? '#467495' : '#8CA4D1'; // Alternating colors

        // Output each comment wrapped in a container with a distinct background color
        echo "<div style='border: 1px solid  $border_color; padding: 5px; margin-bottom: 5px; position: relative;' id='comment{$row['comment_id']}'>";
        echo "<strong>" . $row['first_name'] . ' ' . $row['last_name'] . "</strong><br>";
        echo "<span style='font-size: 12px; color: #666;' id='date{$row['comment_id']}'>Date: {$row['date']}</span>";
        echo "<span style='font-size: 12px; color: #666;' id='time{$row['comment_id']}'>  Time: " . date('H:i', strtotime($row['time'])) . "</span><br>";
        if ($user_id == $row['sender_id']) {

            echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' style='position: absolute; top: 5px; right: 5px;' onsubmit='return confirm(\"Are you sure you want to delete this comment?\");'>";
            echo "<input type='hidden' name='comment_id' value='{$row['comment_id']}'>";
            echo "<button type='button' onclick='toggleEdit({$row['comment_id']})' class='edit-button'>Edit</button>";
            echo "<button type='submit' name='delete-comment' class='delete-button'>Delete</button>";
            echo "</form>";


            // If comment posted by the logged-in user, display editable content

            echo "<div id='edit{$row['comment_id']}' style='display: none;'>";
            echo "<textarea id='textarea{$row['comment_id']}' rows='4' cols='50'>{$row['content']}</textarea><br>";
            echo "<button onclick='saveEdit({$row['comment_id']})' class='save-button'>Save</button>";
            echo "<button onclick='cancelEdit({$row['comment_id']})'class='cancel-button'>Cancel</button>";
            echo "</div>";
            echo "<div id='content{$row['comment_id']}'>";
            echo $row['content'] . "<br>";
            echo "</div>";
        } else {
            // If comment not posted by the logged-in user, display non-editable content
            echo $row['content'] . "<br>";
        }

        echo "</div>";
    }
}


// Insert comment into database for view profile
function setViewProfileComments($conn, $profile_id)
{
    if (!empty($_POST['content'])) { // Check if the comment content is not empty
        $receiver_id = $profile_id;
        $sender_id = $_SESSION['user_id']; // Use logged-in user's id
        $content = $_POST['content'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $sql = "INSERT INTO commendation (receiver_id, sender_id, content, date, time)
         VALUES ('$receiver_id', '$sender_id', '$content', '$date', '$time')";
        if (mysqli_query($conn, $sql)) {
            // Successfully inserted into database
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            // Error in SQL query
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }
    }
}



echo <<<HTML
<script>
function toggleEdit(commentId) {
    document.getElementById('content' + commentId).style.display = 'none';
    document.getElementById('edit' + commentId).style.display = 'block';
}

function cancelEdit(commentId) {
    document.getElementById('content' + commentId).style.display = 'block';
    document.getElementById('edit' + commentId).style.display = 'none';
}

function saveEdit(commentId) {
    var newContent = document.getElementById('textarea' + commentId).value;
    var newDate = new Date().toISOString().slice(0, 10); // Get current date
    var currentTime = new Date(); // Get current time
    var newTime = currentTime.getHours() + ':' + currentTime.getMinutes(); // Extract hour and minute

    // Check if content is empty
    if (newContent.trim() === '') {
        alert('Please enter some content.');
        document.getElementById('edit' + commentId).style.display = 'none';
        document.getElementById('content' + commentId).style.display = 'block';
        return false;
    }

    // Check if content length is within the allowed range
    if (newContent.length < 20 || newContent.length > 200) {
        alert('The comment must be between 20 and 200 characters long.');
        document.getElementById('edit' + commentId).style.display = 'none';
        document.getElementById('content' + commentId).style.display = 'block';
        return false;
    }

    // If conditions are met, display the confirmation message
    var confirmation = confirm("Are you sure you want to save the edited comment?");
    if (confirmation) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../ProfilePage/comments/edit_comment.php', true); // Endpoint to handle update operation
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Check the response from the server
                if (xhr.responseText === 'success') {
                    // Update comment content directly on the page if update was successful
                    document.getElementById('content' + commentId).innerHTML = newContent;
                    // Update date and time fields
                    document.getElementById('edit' + commentId).style.display = 'none';
                    document.getElementById('content' + commentId).style.display = 'block';
                    document.getElementById('date' + commentId).style.display = 'inline'; // Ensure the Date label is displayed
                    document.getElementById('time' + commentId).style.display = 'inline'; // Ensure the Time label is displayed
                    document.getElementById('date' + commentId).innerHTML = "Date: " + newDate;
                    document.getElementById('time' + commentId).innerHTML = " Time: " + newTime;
                } else if (xhr.responseText === 'char_limit_error') {
                    // Show error message if character limit is not met or exceeded
                    alert('Failed to update comment. The comment must be between 20 and 200 characters long.');
                } else if (xhr.responseText === 'empty_content_error') {
                    // Show error message if content is empty
                    alert('Failed to update comment. Please enter some content.');
                } else {
                    // Show generic error message if update failed
                    alert('Failed to update comment. Please try again.');
                }
            }
        };
        // Send comment_id, new content, new date, and new time to the server
        xhr.send('comment_id=' + commentId + '&content=' + encodeURIComponent(newContent) + '&date=' + encodeURIComponent(newDate) + '&time=' + encodeURIComponent(newTime));
    }
    return confirmation;
}



</script> 

HTML;

ob_end_flush();
