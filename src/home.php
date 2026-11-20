<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

header("Location: list_users.php");
exit;
