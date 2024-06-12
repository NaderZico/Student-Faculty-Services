<?php
session_start();

$db = mysqli_connect("localhost", "root", "", "capstone");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $account_type = $_POST['account-type'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $password_hash = password_hash($password, PASSWORD_BCRYPT);  // Hash password
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];

  $db->begin_transaction();

  $sql_check = "SELECT email FROM account WHERE email = '$email'";
  $result_check = $db->query($sql_check);

  if ($result_check->num_rows > 0) {
    $db->rollback();
    $_SESSION['error'] = "Email already exists. Please choose a different email address.";
  } else {
    if ($account_type === 'student') {
      $college = $_POST['college'];
      $major = $_POST['major'];

      $sql = "INSERT INTO account (account_type, email, password, first_name, last_name) VALUES ('$account_type', '$email', '$password_hash', '$first_name', '$last_name')";
      $result = $db->query($sql);

      if ($result === TRUE) {
        $student_id = $db->insert_id; // This is the account_id for the new account
        $sql_student = "INSERT INTO student (student_id, major, college) VALUES ('$student_id', '$major', '$college')";
        $result_student = $db->query($sql_student);

        if ($result_student === TRUE) {
          // Registration successful - Commit transaction
          $db->commit();
          $_SESSION['user_type'] = 'student';
          $_SESSION['user_id'] = $student_id;
          $_SESSION['user_name'] = $first_name . ' ' . $last_name; 
          $_SESSION['user_email'] = $email;
          header("Location: http://localhost/Code/ProfilePage/StudentProfile.php");
          exit();
        } else {
          // Student record insertion failed - Rollback transaction
          $db->rollback();
          $_SESSION['error'] = "Error: Student record insertion failed. " . $db->error;
        }
      } else {
        // Account record insertion failed - Rollback transaction
        $db->rollback();
        $_SESSION['error'] = "Error: Account record insertion failed. " . $db->error;
      }
    } elseif ($account_type === 'faculty') {
      $department = $_POST['department'];

      $sql = "INSERT INTO account (account_type, email, password, first_name, last_name) VALUES ('$account_type', '$email', '$password_hash', '$first_name', '$last_name')";
      $result = $db->query($sql);

      if ($result === TRUE) {
        $faculty_id = $db->insert_id;
        $sql_faculty = "INSERT INTO faculty (faculty_id, department) VALUES ('$faculty_id', '$department')";
        $result_faculty = $db->query($sql_faculty);

        if ($result_faculty === TRUE) {
          // Registration successful - Commit transaction
          $db->commit();
          $_SESSION['user_type'] = 'faculty';
          $_SESSION['user_id'] = $faculty_id;
          $_SESSION['user_name'] = $first_name . ' ' . $last_name; 
          $_SESSION['user_email'] = $email;
          header("Location: http://localhost/Code/ProfilePage/FacultyProfile.php");
          exit();
        } else {
          // Faculty record insertion failed - Rollback transaction
          $db->rollback();
          echo "Error: Faculty record insertion failed. " . $db->error;
        }
      } else {
        // Account record insertion failed - Rollback transaction
        $db->rollback();
        echo "Error: Account record insertion failed. " . $db->error;
      }
    } else {
      // Account record insertion failed - Rollback transaction
      $db->rollback();
      echo "Error: Account record insertion failed. " . $db->error;
    }
  }

  // If execution reaches here, a rollback likely happened 
  $db->close();
} else {
  $_SESSION['error'] = "Error: Unexpected request method";
}

header("Location:RegisterPage.php");
exit();
