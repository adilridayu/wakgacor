<?php
    session_start();

    if (!isset($_SESSION["admin_id"])) {
        header("Location: login.php");
        exit();
    }

    $_SESSION = [];
    session_destroy();
    header("Location: login.php");
    exit();
?>