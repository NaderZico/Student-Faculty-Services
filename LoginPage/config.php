<?php
session_start();

$db = mysqli_connect("localhost", "root", "", "capstone");
$email = $_POST["email"];
$password = $_POST["password"];

$query = "SELECT * FROM account WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($db ,$query);
$row = mysqli_fetch_assoc($result);

if(!empty($row)){

    $_SESSION['user_id'] = $row['account_id']; // Store user ID in session
    $_SESSION['user_email'] = $row['email']; // Store user ID in session
    $_SESSION['user_name'] = $row['first_name']. " " . $row['last_name'];
    $_SESSION['user_type'] = $row['account_type'];  
    if($row['account_type']==='student'){
        header("Location: http://localhost/Code/ProfilePage/StudentProfile.php");

    }
    else if($row['account_type']==='faculty'){
        header("Location: http://localhost/Code/ProfilePage/FacultyProfile.php");

    }
    exit(); // Ensure script stops execution after redirection
} else {
    // Login failed
    $_SESSION['error'] = true;
    header("Location: LoginPage.php");
    exit();
}

mysqli_close($db);

?>
