<?php

$db = mysqli_connect("localhost", "root", "", "capstone");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if the search query is set and not empty
if (isset($_GET['query']) && !empty($_GET['query'])) {
    // Sanitize the search query to prevent SQL injection (using prepared statement)
    $search_query = '%' . mysqli_real_escape_string($db, $_GET['query']) . '%';

    // Query to search for profiles (both students and faculty) using prepared statement
    $sql = "SELECT account_id, first_name, last_name 
            FROM account 
            WHERE CONCAT(first_name, ' ', last_name) LIKE ?";

    $stmt = $db->prepare($sql);

    $stmt->bind_param("s", $search_query);

    $stmt->execute();

    $result = $stmt->get_result();

    // Check if any matching names are found
    if ($result->num_rows > 0) {
        // Fetch and output names as options
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['account_id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
        }
    } else {
        // If no matching names are found
        echo "<option value=''>No profile found</option>";
    }

    $stmt->close();
}

$db->close();
?>
