<?php
$servername = "localhost";
$username = "mealtracker";
$password = "";
$dbname = "my_mealtracker";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?>
