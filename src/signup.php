<?php
include '../settings/connection.php';

$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$ident_number = trim($_POST['ident_number']);
$mobile_phone = trim($_POST['mobile_phone']);
$email = strtolower(trim($_POST['email']));
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = 'SELECT id FROM accounts WHERE email = $1';
$result = pg_query_params($conn, $sql, array($email));

if (pg_fetch_assoc($result)) {
    echo "El correo ya existe. <a href='signup.html'>Volver</a>";
    exit;
}

pg_query($conn, 'BEGIN');

$sql = 'INSERT INTO users
        (firstname, lastname, ident_number, mobile_phone, email, photo_url)
        VALUES ($1, $2, $3, $4, $5, $6)
        RETURNING id';
$userResult = pg_query_params(
    $conn, $sql,
    array($firstname, $lastname, $ident_number, $mobile_phone, $email, '')
);

$user = pg_fetch_assoc($userResult);

if ($user) {
    $accountResult = pg_query_params(
        $conn,
        'INSERT INTO accounts (user_id, email, password_hash)
         VALUES ($1, $2, $3)',
        array($user['id'], $email, $password)
    );
}

if ($user && $accountResult) {
    pg_query($conn, 'COMMIT');
    header("Location: signin.html");
    exit;
}

pg_query($conn, 'ROLLBACK');
echo "No se pudo crear el usuario.";
