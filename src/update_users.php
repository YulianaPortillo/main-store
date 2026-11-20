<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

include '../settings/connection.php';

$id = $_POST['id'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$mobile_phone = $_POST['mobile_phone'];

$sql = 'UPDATE users
        SET firstname = $1, lastname = $2, email = $3, mobile_phone = $4
        WHERE id = $5';
$result = pg_query_params($conn, $sql, array($firstname, $lastname, $email, $mobile_phone, $id));

if (!$result) {
    echo "Error al actualizar los datos";
    exit;
}

if (isset($_FILES['photo']) && $_FILES['photo']['name'] != "") {
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

    if ($ext == "jpg" || $ext == "jpeg" || $ext == "png") {
        $photo_url = "photos/" . $mobile_phone . "." . $ext;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . "/photos/" . $mobile_phone . "." . $ext)) {
            $sql = 'UPDATE users SET photo_url = $1 WHERE id = $2';
            pg_query_params($conn, $sql, array($photo_url, $id));
        } else {
            echo "No se pudo guardar la foto. Revisa la carpeta photos.";
            exit;
        }
    } else {
        echo "Solo se permiten fotos JPG o PNG. <a href='edit_users.php?id=$id'>Volver</a>";
        exit;
    }
}

header('Location: list_users.php');
exit;