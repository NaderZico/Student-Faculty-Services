<?php
session_start();

$db = mysqli_connect("localhost", "root", "", "capstone");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $query = "SELECT * FROM account WHERE email = '$email'";
  $result = mysqli_query($db, $query);
  $row = mysqli_fetch_assoc($result);

  if (!empty($row)) {
    $password_hash = $row['password']; // Get hashed password from database

    if (password_verify($password, $password_hash)) {
      // Password matches - Login successful
      $_SESSION['user_id'] = $row['account_id']; // Store user ID in session
      $_SESSION['user_email'] = $row['email']; // Store user ID in session
      $_SESSION['user_name'] = $row['first_name'] . " " . $row['last_name'];
      $_SESSION['user_type'] = $row['account_type'];

      if ($row['account_type'] === 'student') {
        header("Location: http://localhost/Code/ProfilePage/StudentProfile.php");
      } else if ($row['account_type'] === 'faculty') {
        header("Location: http://localhost/Code/ProfilePage/FacultyProfile.php");
      }
      exit();
    } else {
      // Password mismatch - Login failed
      $_SESSION['error'] = true;
      header("Location: LoginPage.php");
      exit();
    }
  } else {
    // Login failed - User not found
    $_SESSION['error'] = true;
    header("Location: LoginPage.php");
    exit();
  }
}