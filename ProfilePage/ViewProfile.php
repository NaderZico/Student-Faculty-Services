<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginPage/LoginPage.php");
    exit();
}

include "../Chatbot/Chatbot.php";
date_default_timezone_set('Asia/Dubai');
include_once 'comments/handle_comments.php';

// Include the appropriate header based on user type
if ($_SESSION['user_type'] === 'faculty') {
    include "../Header/FacultyHeader.php";
} else {
    include "../Header/StudentHeader.php";
}

// Store profile_id in session if provided via GET parameters
if (isset($_GET["profile_id"])) {
    $_SESSION['profile_id'] = $_GET["profile_id"];
}

// Fetch profile_id from session
$profile_id = isset($_SESSION['profile_id']) ? $_SESSION['profile_id'] : null;

// Fetch user type based on profile_id
$account_type = fetchUserType($conn, $profile_id);

// Fetch user details based on profile_id
$userDetails = fetchUserProfileDetails($conn, $profile_id);

// Fetch faculty rating if the user is a faculty member
if ($account_type === 'faculty') {
    $ratings = fetchFacultyRatings($conn, $profile_id);
    $avg_concise = $ratings['avg_concise'];
    $avg_engaging = $ratings['avg_engaging'];
    $avg_insightful = $ratings['avg_insightful'];
    $appointment_count = $ratings['appointment_count'];
}

// Check if comments exist for the profile
$commentsExist = $profile_id ? checkCommentsExist($conn, $profile_id) : false;

function fetchUserType($conn, $profile_id)
{
    // Sanitize the profile_id
    $profile_id = mysqli_real_escape_string($conn, $profile_id);

    // Query to retrieve user type from the database
    $sql = "SELECT account_type FROM account WHERE account_id = $profile_id";
    $result = mysqli_query($conn, $sql);

    // Fetch the user type
    $row = mysqli_fetch_assoc($result);
    return $row['account_type'];
}

function fetchUserProfileDetails($conn, $profile_id)
{
    // Sanitize the profile_id
    $profile_id = mysqli_real_escape_string($conn, $profile_id);

    // Query to retrieve user profile details from the database
    $sql = "SELECT * FROM account WHERE account_id = $profile_id";
    $result = mysqli_query($conn, $sql);

    // Fetch the user details
    $row = mysqli_fetch_assoc($result);
    return $row;
}

function fetchFacultyRatings($conn, $profile_id)
{
    // Sanitize the profile_id
    $profile_id = mysqli_real_escape_string($conn, $profile_id);

    // Query to retrieve faculty ratings from the database
    $sql = "SELECT AVG(concise_score) AS avg_concise, 
    AVG(engaging_score) AS avg_engaging, 
    AVG(insightful_score) AS avg_insightful, 
    COUNT(*) AS appointment_count
            FROM rating
            WHERE rated_faculty_id = $profile_id";
    $result = mysqli_query($conn, $sql);

    // Fetch the ratings
    $row = mysqli_fetch_assoc($result);
    return $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="Profile.css">
</head>
<body>
    <div class="profile-content">
        <!-- User details section -->
        <div class="user-details">
            <?php
            // Fetch profile photo path from the database
            $profile_photo_path = ''; // Default empty path
            $sql = "SELECT uploads FROM account WHERE account_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $profile_id);
            $stmt->execute();
            $stmt->bind_result($profile_photo_path);
            $stmt->fetch();
            $stmt->close();

            // Display profile photo or default icon
            if (!empty($profile_photo_path)) {
                echo '<img src="' . $profile_photo_path . '" class="user-photo-view" alt="Profile Photo">';
            } else {
                echo '<img src="../images/icons/User icon.png" class="user-photo-view" alt="Profile Photo">';
            }
            ?>
            <h2><?php echo $userDetails['first_name'] . ' ' . $userDetails['last_name']; ?></h2>
        </div>

        <!-- Personal details section -->
        <div class="personal-details">
            <?php if ($account_type === 'faculty') : ?>
                <div class="personal-details-view-faculty">
                    <h3 class="profile-type">Academic Staff - Profile Details</h3>
                    <div class="personal-details-content">
                        <!-- Display faculty profile details -->
                        <?php
                        $sql = "SELECT * FROM account INNER JOIN faculty ON account.account_id = faculty.faculty_id WHERE account.account_id = $profile_id";
                        $result = mysqli_query($conn, $sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<p><strong>Name:</strong> " . $row["first_name"] . " " . $row["last_name"] . "</p>";
                                echo '<p><strong>Email:</strong> <a href="mailto:' . $row["email"] . '">' . $row["email"] . '</a></p>';
                                echo "<p><strong>Department:</strong> " . $row["department"] . "</p>";
                            }
                        } else {
                            echo "<p>No details found.</p>";
                        }
                        ?>
                        <div class="circular-container">
                            <div class="circular">
                                <div class="number"><?php echo $appointment_count; ?></div>
                                <div class="word">Appointments</div>
                            </div>
                            <div class="circular">
                                <div class="number"><?php echo round($avg_concise, 2); ?>%</div>
                                <div class="word">Concise</div>
                            </div>
                            <div class="circular">
                                <div class="number"><?php echo round($avg_engaging, 2); ?>%</div>
                                <div class="word">Engaging</div>
                            </div>
                            <div class="circular">
                                <div class="number"><?php echo round($avg_insightful, 2); ?>%</div>
                                <div class="word">Insightful</div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($account_type === 'student') : ?>
                <div class="personal-details-view-student">
                    <h3 class="profile-type">Student - Profile Details</h3>
                    <div class="personal-details-content">
                        <!-- Display student profile details -->
                        <?php
                        $sql = "SELECT email, LEFT(email, LOCATE('@', email) - 1) AS Domain, first_name, last_name, major, college FROM account INNER JOIN student ON account.account_id = student.student_id WHERE account.account_id = $profile_id";
                        $result = mysqli_query($conn, $sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<p><strong>Student ID:</strong> " . $row["Domain"] . "</p>";
                                echo "<p><strong>Name:</strong> " . $row['first_name'] . ' ' . $row['last_name'] . "</p>";
                                echo "<p><strong>Email:</strong> <a href='mailto:" . $row["email"] . "' style='color: blue;'>" . $row["email"] . "</a></p>";
                                echo "<p><strong>Major:</strong> " . $row["major"] . "</p>";
                                echo "<p><strong>College: </strong>" . $row["college"] . "</p>";
                            }
                        } else {
                            echo "<p>No details found.</p>";
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Comment section at the bottom -->
    <div class="comment-section-view">
        <div class="comment-form-student">
            <?php if (isset($_SESSION['comment_error'])): ?>
                <p style="color: red;"><?php echo $_SESSION['comment_error']; ?></p>
                <?php unset($_SESSION['comment_error']); ?>
            <?php endif; ?>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="commentForm">
                <?php
                $receiver_id = $profile_id ? intval($profile_id) : 0;
                $sender_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
                ?>
                <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($receiver_id); ?>">
                <input type="hidden" name="date" value="<?php echo date('Y/m/d'); ?>">
                <input type="hidden" name="time" value="<?php echo date('H:i:s'); ?>">
                <input type="hidden" name="sender_id" value="<?php echo htmlspecialchars($sender_id); ?>">
                <?php if ($receiver_id != $sender_id) : ?>
                    <div class="comment-input-container">
                        <textarea class="comment-text" name="content" id="commentContent" placeholder="Write a commendation..." maxlength="200" minlength="20"></textarea>
                        <span id="charCount"></span>
                        <button class="comment-button" name="comment-submit" type="submit">Post</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <div class="comment-list">
            <ul class="content-comment-list">
                <?php
                if ($commentsExist) {
                    echo getViewProfileComments($conn, $profile_id);
                } else {
                    echo "<p class='no-comments-message'>No comments found for the current user.</p>";
                }
                ?>
            </ul>
        </div>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var commentInput = document.getElementById('commentContent');
    var charCount = document.getElementById('charCount');

    // Update character count on input
    commentInput.addEventListener('input', function() {
        var count = commentInput.value.length;
        charCount.textContent = count + '/200'; // Update character count
    });

    // Form submission validation
    document.getElementById('commentForm').addEventListener('submit', function(event) {
        var commentLength = commentInput.value.length;
        if (commentLength > 200) {
            // Prevent form submission
            event.preventDefault();
            // Display error message
            alert('Comment must be less than or equal to 200 characters.');
        }
    });
});
</script>
</html>
