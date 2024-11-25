<?php
include '_dbconnect.php';

if (isset($_POST['type'])) {
    $type = $_POST['type'];

    if ($type == 'state') {
        $query = "SELECT * FROM states";
        $result = mysqli_query($conn, $query);
        $states = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $states[] = $row;
        }
        echo json_encode($states);
    } elseif ($type == 'city' && isset($_POST['state_id'])) {
        $state_id = $_POST['state_id'];
        $query = "SELECT * FROM cities WHERE state_id = $state_id";
        $result = mysqli_query($conn, $query);
        $cities = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $cities[] = $row;
        }
        echo json_encode($cities);
    } elseif ($type == 'school' && isset($_POST['city_id'])) {
        $city_id = $_POST['city_id'];
        $query = "SELECT * FROM schools WHERE city_id = $city_id";
        $result = mysqli_query($conn, $query);
        $schools = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $schools[] = $row;
        }
        echo json_encode($schools);
    }
}
