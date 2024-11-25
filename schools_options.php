<?php
include '_dbconnect.php';
$schools = mysqli_query($conn, "SELECT * FROM schools");
while ($row = mysqli_fetch_assoc($schools)) {
    echo '<option value="' . $row['school_name'] . '">' . $row['school_name'] . '</option>';
}
?>