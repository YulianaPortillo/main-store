<?php

require __DIR__ . '/../app/comun.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit;
}

comprobarFormulario();

$_SESSION = [];
session_regenerate_id(true);
$_SESSION['mensaje'] = 'Sesión cerrada.';
irA('/ingresar.php');
