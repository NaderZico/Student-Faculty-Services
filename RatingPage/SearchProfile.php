<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Establish database connection
$db = mysqli_connect("localhost", "root", "", "capstone");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if the search query is set and not empty
if (isset($_GET['query']) && !empty($_GET['query'])) {
    // Sanitize the search query to prevent SQL injection
    $search_query = mysqli_real_escape_string($db, $_GET['query']);

    // Query to search for profiles (both students and faculty)
$sql = "SELECT account_id, first_name, last_name 
FROM account 
WHERE CONCAT(first_name, ' ', last_name) LIKE '%$search_query%'";



    $result = mysqli_query($db, $sql);

   // Check if any matching faculty names are found
   if (mysqli_num_rows($result) > 0) {
    // Fetch and output faculty names as options
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['account_id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
    }
} else {
    // If no matching faculty names are found
    echo "<option value=''>No profile found</option>";
}
}
$db->close();
?>