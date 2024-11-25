<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: index.php"); // Redirect if not logged in
    exit;
}

// Restrict access to admins only
if ($_SESSION['role'] != 'admin') {
    echo "Access Denied: You do not have permission to view this page.";
    exit;
}

include '_dbconnect.php'; // Database connection
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $state_name = $_POST['state_name'];
    $query = "INSERT INTO states (state_name) VALUES ('$state_name')";
    if (mysqli_query($conn, $query)) {
        echo "State added successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add State</title>
</head>

<body>
    <h1>Add New State</h1>
    <form method="POST" action="">
        <label for="state_name">State Name:</label>
        <input type="text" id="state_name" name="state_name" required>
        <button type="submit">Add State</button>
    </form>
</body>

</html>