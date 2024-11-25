<?php
include '_dbconnect.php';
$states = mysqli_query($conn, "SELECT * FROM states");
while ($row = mysqli_fetch_assoc($states)) {
    echo '<option value="' . $row['state_name'] . '">' . $row['state_name'] . '</option>';
}
?>