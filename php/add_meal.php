<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $meal_type = $_POST['meal_type'];
    $food = $_POST['food'];

    // Aggiungi il pasto
    $sql = "INSERT INTO meal (user_id, date, meal_type, food) VALUES ('$user_id', '$date', '$meal_type', '$food')";

    if ($conn->query($sql) === TRUE) {
        // Se il pasto è spuntino o merenda, aggiorna l'altro
        if ($meal_type == 'spuntino' || $meal_type == 'merenda') {
            $other_meal_type = ($meal_type == 'spuntino') ? 'merenda' : 'spuntino';
            $check_sql = "SELECT * FROM meal WHERE user_id='$user_id' AND date='$date' AND meal_type='$other_meal_type'";
            $check_result = $conn->query($check_sql);

            if ($check_result->num_rows > 0) {
                // Aggiorna l'altro pasto
                $update_sql = "UPDATE meal SET food='$food' WHERE user_id='$user_id' AND date='$date' AND meal_type='$other_meal_type'";
                $conn->query($update_sql);
            } else {
                // Aggiungi l'altro pasto
                $insert_sql = "INSERT INTO meal (user_id, date, meal_type, food) VALUES ('$user_id', '$date', '$other_meal_type', '$food')";
                $conn->query($insert_sql);
            }
        }

        header("Location: ../views/day.php?date=$date");
        exit();
    } else {
        echo "Errore: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
