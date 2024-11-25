<?php
$conn = mysqli_connect('localhost','root', '','counselling');
if(!$conn){
    die("Connection Error" . mysqli_connect_error());
}
?>