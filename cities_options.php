<?php
include '_dbconnect.php';
$cities = mysqli_query($conn, "SELECT * FROM cities");
while ($row = mysqli_fetch_assoc($cities)) {
    echo '<option value="' . $row['city_name'] . '">' . $row['city_name'] . '</option>';
}
?>