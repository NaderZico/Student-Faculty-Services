<title>Faculty Profile</title>

<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'faculty') {
    header("Location: ../LoginPage/LoginPage.php");
    exit();
}
include "../db_connection.php";

// Fetch ratings for the logged-in faculty user
$user_id = $_SESSION['user_id'];
$sql = "SELECT AVG(concise_score) AS avg_concise, AVG(engaging_score) AS avg_engaging, AVG(insightful_score) AS avg_insightful, COUNT(*) AS appointment_count
        FROM rating
        WHERE rated_faculty_id = $user_id";
$result = $db->query($sql);
$row = $result->fetch_assoc();

// Calculate average ratings and appointment count
$avg_concise = $row['avg_concise'];
$avg_engaging = $row['avg_engaging'];
$avg_insightful = $row['avg_insightful'];
$appointment_count = $row['appointment_count'];

// Close the database connection
$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Profile</title>
    <link rel="stylesheet" href="Profile.css">
</head>

<body>
    <?php
    include "../Header/FacultyHeader.php";
    include "../Chatbot/Chatbot.php";
    date_default_timezone_set('Asia/Dubai');
    include_once 'comments/handle_comments.php';

    $commentsExist = checkCommentsExist($conn, $_SESSION['user_id']);


    // Fetch the profile photo from the database for the current user
    $conn = new mysqli("localhost", "root", "", "capstone");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id'];

    $select_sql = "SELECT uploads FROM account WHERE account_id = ?";
    $stmt = $conn->prepare($select_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($profile_photo);
    $stmt->fetch();
    $stmt->close();

    // Determine the profile photo path
    if ($profile_photo) {
        $profile_photo_path = $profile_photo;
    } else {
        $profile_photo_path = "../images/icons/User icon.png"; // Default photo path
    }
    ?>


    <div class="profile-content">
        <!-- <img src="../LoginPage/AAU logo.png" alt="logo" class="logo"> -->
        <div class="user-details">
            <button class="editProfileBtn" id="editProfileBtn">
                <img src="../images/icons/pen icon.jpg" class="edit_icon-faculty">
            </button>
            <img src="<?php echo $profile_photo_path; ?>" class="user-photo">

            <?php echo $_SESSION['user_name']; ?>


            <!-- Container for the profile photo upload form -->
            <div id="editProfileFormContainer" style="display: none;">
                <form action="upload_photo.php" method="post" enctype="multipart/form-data" class="edit-profile-form">
                    <label for="profile_photo" class="edit-profile-label">Upload Profile Photo:</label>
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" required class="edit-profile-input"><br>
                    <button type="submit" name="Upload" class="edit-profile-submit-btn">Upload</button>
                    <button class="remove-button" onclick="removePhoto()">Remove</button>
                </form>
            </div>

        </div>

        <div class="personal-details-faculty">
            <h2 class="profile-type-faculty">Academic Staff - Profile Details</h2>
            <div class="personal-details-content-faculty">
                <?php include 'Profile.php'; ?>
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
    </div>

    <div class="comment-section-faculty">
        <div class="comment-list-faculty">
            <ul class="content-comment-list">
                <?php
                // Display comments if they exist
                if ($commentsExist) {
                    echo getComments($conn);
                } else {
                    echo "<p class='no-comments-message'>No comments found for the current user.</p>";
                }
                ?>
            </ul>
        </div>

        <div class="comment-form">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <?php
                $receiver_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : 0; // Default to 0 if not set or invalid
                ?>
                <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($receiver_id); ?>">
                <input type="hidden" name="date" value="<?php echo date('Y/m/d'); ?>">
                <input type="hidden" name="time" value="<?php echo date('H:i:s'); ?>">
                <?php
                if (isset($_SESSION['user_id'])) {
                    $sender_id = $_SESSION['user_id'];
                } else {
                    // Handle the case where user_id is not set
                    $sender_id = 0; // or any default value
                }
                ?>
                <input type="hidden" name="sender_id" value="<?php echo htmlspecialchars($sender_id); ?>">

            </form>
        </div>

    </div>

    <!-- Include the help modal HTML content -->
    <button class="help-button" onclick="toggleHelp()">
        <img src="../images/icons/question mark.jpg" class="help-icon">
    </button>

    <!-- Add the help modal container with the modal content -->
    <div class="modal-container" id="helpModalContainer">
        <div class="modal-content">
            <?php include "../HelpModal/help.html"; ?>
        </div>
    </div>
    <script>
        // JavaScript to toggle the visibility of the profile photo upload form
        document.getElementById('editProfileBtn').addEventListener('click', function() {
            var formContainer = document.getElementById('editProfileFormContainer');
            if (formContainer.style.display === 'none') {
                formContainer.style.display = 'block';
            } else {
                formContainer.style.display = 'none';
            }
        });

        function removePhoto() {
            if (confirm("Are you sure you want to remove the photo?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "remove_photo.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            // Redirect to the appropriate profile page
                            if (response.user_type === 'student') {
                                window.location.href = 'StudentProfile.php';
                            } else if (response.user_type === 'faculty') {
                                window.location.href = 'FacultyProfile.php';
                            }
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                };

                xhr.send();
            }
        }
    </script>

</body>

</html>