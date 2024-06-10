<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'faculty') {
    header("Location: ../LoginPage/LoginPage.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

$db = mysqli_connect("localhost", "root", "", "capstone");
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cancel_slot'])) {
        $slot_id = $_POST['cancel_slot'];

        // Delete the slot from the database
        $delete_sql = "DELETE FROM slot WHERE slot_id = '$slot_id' AND faculty_id = '$user_id'";
        if ($db->query($delete_sql) === TRUE) {
            $_SESSION['success'] = "Slot has been canceled successfully.";
        } else {
            $_SESSION['error'] = "Error canceling slot: " . $db->error;
        }
    }
}

$sql = "SELECT slot_id, time, date FROM slot WHERE faculty_id = '$user_id' AND status = 'available' ORDER BY date, time";

$result = $db->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Slots</title>
    <link rel="stylesheet" href="ViewSlotsPage.css">
</head>

<body>

    <?php include "../Header/FacultyHeader.php"; ?>
    <?php include "../Chatbot/Chatbot.php"; ?>

    <div class="container">
        <h2>Your Slots</h2>
        <h4 class="description-text">Manage your availability for appointments</h4>
        <div class="message">
            <?php
            if (isset($_SESSION['error'])) {
                echo "<span class='error-message'>" . $_SESSION['error'] . "</span>";
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo "<span class='success-message'>" . $_SESSION['success'] . "</span>";
                unset($_SESSION['success']);
            }
            ?>
        </div>
        <table>
            <tr>
                <th>Time</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='hidden-slot-id'>" . $row["slot_id"] . "</td>";
                    $time_formatted = date("H:i", strtotime($row["time"]));
                    echo "<td>" . $time_formatted . "</td>";

                    // Convert the date format to DD-MM-YYYY
                    $date_formatted = date("d-m-Y", strtotime($row["date"]));

                    echo "<td>" . $date_formatted . "</td>";

                    echo "<td>";
                    echo "<form class='cancel-form' method='post' action='" . $_SERVER["PHP_SELF"] . "'>";
                    echo "<input type='hidden' name='cancel_slot' value='" . $row["slot_id"] . "'>";
                    echo "<button type='button' class='cancel-btn' onclick='confirmCancellation(" . json_encode($time_formatted) . ", " . json_encode($date_formatted) . ")'>Cancel</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No slots available</td></tr>";
            }
            ?>
        </table>

        <script>
            function confirmCancellation(time, date) {
                if (confirm("Are you sure you want to cancel the slot for " + time + " on " + date + "?")) {
                    // Submit the form if the user confirms
                    event.target.closest(".cancel-form").submit();
                }
            }
        </script>

</body>

</html>