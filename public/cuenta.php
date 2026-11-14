<?php

require __DIR__ . '/../app/comun.php';

if (!isset($_SESSION['usuario_id'])) {
    irA('/ingresar.php');
}

header('Cache-Control: no-store');
?>
<!doctype html>
<html lang="es">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mi cuenta · Papelería Luna</title>
<link rel="stylesheet" href="/estilos.css">
<main>
    <h1>Mi cuenta</h1>
    <p>Hola, <?= escapar($_SESSION['nombres']) ?>.</p>
    <form action="/salir.php" method="post">
        <input type="hidden" name="csrf" value="<?= escapar(tokenFormulario()) ?>">
        <button>Cerrar sesión</button>
    </form>
</main>
</html>
