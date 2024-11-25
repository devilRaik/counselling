<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: index.php");
    exit;
}

// Restrict access to admins only
if ($_SESSION['role'] != 'admin') {
    echo "Access Denied: You do not have permission to view this page.";
    exit;
}

include '_dbconnect.php'; // Database connection
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $city_name = $_POST['city_name'];
    $state_id = $_POST['state_id']; // From dropdown

    $query = "INSERT INTO cities (city_name, state_id) VALUES ('$city_name', $state_id)";
    if (mysqli_query($conn, $query)) {
        echo "City added successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch states for the dropdown
$states = mysqli_query($conn, "SELECT * FROM states");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add City</title>
</head>

<body>
    <h1>Add New City</h1>
    <form method="POST" action="">
        <label for="city_name">City Name:</label>
        <input type="text" id="city_name" name="city_name" required>
        <label for="state_id">Select State:</label>
        <select id="state_id" name="state_id" required>
            <option value="" disabled selected>Select State</option>
            <?php while ($row = mysqli_fetch_assoc($states)) { ?>
                <option value="<?= $row['state_name'] ?>"><?= $row['state_name'] ?></option>
            <?php } ?>
        </select>
        <button type="submit">Add City</button>
    </form>
</body>

</html>