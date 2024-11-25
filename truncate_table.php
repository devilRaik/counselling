<?php
include '_dbconnect.php';
if (isset($_POST['empty'])) {
    $selectTable = "SELECT * FROM states";

    $query = mysqli_query($conn, $selectTable);
    if ($query) {
        $empty = "SET FOREIGN_KEY_CHECKS = 0";
        if (mysqli_query($conn, $empty)) {
            echo "Set unforeign";
            $truncate = "TRUNCATE TABLE states";
            if (mysqli_query($conn, $truncate)) {
                echo "Empty Table";
                $reset = "SET FOREIGN_KEY_CHECKS = 1";
                if (mysqli_query($conn, $reset)) {
                    echo "Reset Table";
                }
            }
        }
    }
}
