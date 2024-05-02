<?php

$db = mysqli_connect("localhost", "root", "", "capstone");
if ($db->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_type = $_SESSION['user_type'];
if ($user_type === 'faculty') {
    $sql = "SELECT *
        FROM account
        INNER JOIN faculty ON account.account_id = faculty.faculty_id
        WHERE account.account_id = $user_id";
    $result = mysqli_query($db, $sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<span><strong>Name:</strong> " . $row["first_name"] . " " . $row["last_name"] . "</span><br>";
            echo "<br><span><strong>Email:</strong> " . $row["email"] . "</span><br>";
            echo "<br><span><strong>Department:</strong> " . $row["department"] . "</span>";
        }
    } else {
        echo "0 results";
    }
} elseif ($user_type === 'student') {
    $sql1 = "SELECT email,
    LEFT(email, LOCATE('@', email) - 1) AS Domain, first_name, last_name ,major,college
    FROM account
    INNER JOIN student ON account.account_id = student.student_id
    WHERE account.account_id = $user_id";


    $result = mysqli_query($db, $sql1);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<span style='display: block; margin-bottom: 15px;'><strong>Student ID:</strong> " . $row["Domain"] . "</span>";
            echo "<span style='display: block; margin-bottom: 15px;'><strong>Name:</strong> " . $row["first_name"] . " " . $row["last_name"] . "</span>";
            echo "<span style='display: block; margin-bottom: 15px;'><strong>Email:</strong> " . $row["email"] . "</span>";
            echo "<span style='display: block; margin-bottom: 15px;'><strong>Major:</strong> " . $row["major"] . "</span>";
            echo "<span style='display: block; '><strong>College: </strong>" . $row["college"] . "</span>";
        }
    } else {
        echo "0 results";
    }
}
$db->close();
