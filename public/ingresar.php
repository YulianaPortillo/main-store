<?php

require __DIR__ . '/../app/comun.php';
require __DIR__ . '/../app/base.php';

if (isset($_SESSION['usuario_id'])) {
    irA('/cuenta.php');
}

$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);
$error = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    comprobarFormulario();
    $correo = strtolower(trim(campo('correo')));
    $contrasena = campo('contrasena');

    try {
        $consulta = baseDeDatos()->prepare(
            'SELECT id, nombres, contrasena_hash FROM usuarios WHERE correo = :correo'
        );
        $consulta->execute(['correo' => $correo]);
        $usuario = $consulta->fetch();

        if ($usuario && strlen($contrasena) <= 72 && password_verify($contrasena, $usuario['contrasena_hash'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombres'] = $usuario['nombres'];
            unset($_SESSION['csrf']);
            irA('/cuenta.php');
        }

        $error = 'Revisa tu correo y contraseña.';
    } catch (PDOException) {
        $error = 'No se pudo consultar la base de datos.';
    }
}
?>
<!doctype html>
<html lang="es">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ingresar · Papelería Luna</title>
<link rel="stylesheet" href="/estilos.css">
<main>
    <h1>Iniciar sesión</h1>
    <?php if ($mensaje !== ''): ?>
        <p class="mensaje" role="status"><?= escapar($mensaje) ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p class="mensaje" role="alert"><?= escapar($error) ?></p>
    <?php endif; ?>

    <form action="/ingresar.php" method="post">
        <input type="hidden" name="csrf" value="<?= escapar(tokenFormulario()) ?>">

        <label for="correo">Correo</label>
        <input id="correo" name="correo" type="email" autocomplete="username" maxlength="254" value="<?= escapar($correo) ?>" required>

        <label for="contrasena">Contraseña</label>
        <input id="contrasena" name="contrasena" type="password" autocomplete="current-password" required>

        <button>Ingresar</button>
    </form>

    <p><a href="/registro.php">Crear una cuenta</a> · <a href="/">Inicio</a></p>
</main>
</html>
