<?php
session_start();
include '../settings/connection.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = 'SELECT users.id, users.firstname, accounts.password_hash
        FROM accounts
        INNER JOIN users ON users.id = accounts.user_id
        WHERE accounts.email = $1
          AND accounts.status = TRUE';
$result = pg_query_params($conn, $sql, array($email));
$row = pg_fetch_assoc($result);

if ($row && $row['password_hash'] != "" &&
    password_verify($password, $row['password_hash'])) {
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['firstname'] = $row['firstname'];
    header("Location: list_users.php");
    exit;
}

echo "Correo o contraseña incorrectos. <a href='signin.html'>Volver</a>";
