<?php
session_start();

// Default title
$title = "Welcome";

// Change title based on user type
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] == 'admin') {
        $title = "Admin Dashboard";
    } elseif ($_SESSION['userid'] == $username) {
        $title = "Enter Student Details";
    }
}
?>