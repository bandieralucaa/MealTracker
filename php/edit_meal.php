<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meal_id = $_POST['meal_id'];
    $meal_type = $_POST['meal_type'];
    $food = $_POST['food'];

    $sql = "UPDATE meal SET meal_type='$meal_type', food='$food' WHERE id='$meal_id'";

    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'error';
    }
}

$conn->close();
?>
