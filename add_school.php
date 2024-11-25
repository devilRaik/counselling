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
    $school_name = $_POST['school_name'];
    $city_id = $_POST['city_id']; // From dropdown

    $query = "INSERT INTO schools (school_name, city_id) VALUES ('$school_name', $city_id)";
    if (mysqli_query($conn, $query)) {
        echo "School added successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch cities for the dropdown
$cities = mysqli_query($conn, "SELECT * FROM cities");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add School</title>
</head>

<body>
    <h1>Add New School</h1>
    <form method="POST" action="">
        <label for="school_name">School Name:</label>
        <input type="text" id="school_name" name="school_name" required>
        <label for="city_id">Select City:</label>
        <select id="city_id" name="city_id" required>
            <option value="" disabled selected>Select City</option>
            <?php while ($row = mysqli_fetch_assoc($cities)) { ?>
                <option value="<?= $row['city_name'] ?>"><?= $row['city_name'] ?></option>
            <?php } ?>
        </select>
        <button type="submit">Add School</button>
    </form>
</body>

</html>