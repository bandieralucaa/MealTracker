<?php
session_start();

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../views/login.php"); // Assicurati che il percorso sia corretto
        exit();
    }
}
?>
