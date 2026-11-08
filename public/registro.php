<?php

require __DIR__ . '/../app/comun.php';
require __DIR__ . '/../app/base.php';

$error = '';
$nombres = '';
$apellidos = '';
$documento = '';
$telefono = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    comprobarFormulario();

    $nombres = trim(campo('nombres'));
    $apellidos = trim(campo('apellidos'));
    $documento = trim(campo('documento'));
    $telefono = trim(campo('telefono'));
    $correo = strtolower(trim(campo('correo')));
    $contrasena = campo('contrasena');

    if ($nombres === '' || $apellidos === '' || $documento === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'Revisa los campos obligatorios y el correo.';
    } elseif (strlen($contrasena) < 8 || strlen($contrasena) > 72 || str_contains($contrasena, "\0")) {
        $error = 'La contraseña debe tener entre 8 y 72 bytes.';
    } else {
        try {
            $consulta = baseDeDatos()->prepare(
                'INSERT INTO usuarios (nombres, apellidos, documento, telefono, correo, contrasena_hash)
                 VALUES (:nombres, :apellidos, :documento, :telefono, :correo, :hash)'
            );
            $consulta->execute([
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'documento' => $documento,
                'telefono' => $telefono === '' ? null : $telefono,
                'correo' => $correo,
                'hash' => password_hash($contrasena, PASSWORD_DEFAULT),
            ]);

            $_SESSION['mensaje'] = 'Cuenta creada. Ya puedes ingresar.';
            irA('/ingresar.php');
        } catch (PDOException $excepcion) {
            $error = match ($excepcion->getCode()) {
                '23505' => 'Ese correo o documento ya está registrado.',
                '22001' => 'Alguno de los datos supera el tamaño permitido.',
                default => 'No se pudo guardar la cuenta. Revisa la conexión a la base de datos.',
            };
        }
    }
}
?>
<!doctype html>
<html lang="es">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Crear cuenta · Papelería Luna</title>
<link rel="stylesheet" href="/estilos.css">
<main>
    <h1>Crear una cuenta</h1>
    <?php if ($error !== ''): ?>
        <p class="mensaje" role="alert"><?= escapar($error) ?></p>
    <?php endif; ?>

    <form action="/registro.php" method="post">
        <input type="hidden" name="csrf" value="<?= escapar(tokenFormulario()) ?>">

        <label for="nombres">Nombres</label>
        <input id="nombres" name="nombres" autocomplete="given-name" maxlength="80" value="<?= escapar($nombres) ?>" required>

        <label for="apellidos">Apellidos</label>
        <input id="apellidos" name="apellidos" autocomplete="family-name" maxlength="80" value="<?= escapar($apellidos) ?>" required>

        <label for="documento">Documento</label>
        <input id="documento" name="documento" maxlength="24" value="<?= escapar($documento) ?>" required>

        <label for="telefono">Teléfono (opcional)</label>
        <input id="telefono" name="telefono" type="tel" autocomplete="tel" maxlength="24" value="<?= escapar($telefono) ?>">

        <label for="correo">Correo</label>
        <input id="correo" name="correo" type="email" autocomplete="email" maxlength="254" value="<?= escapar($correo) ?>" required>

        <label for="contrasena">Contraseña (mínimo 8 caracteres)</label>
        <input id="contrasena" name="contrasena" type="password" autocomplete="new-password" minlength="8" maxlength="72" required>

        <button>Registrarme</button>
    </form>

    <p><a href="/ingresar.php">Ya tengo cuenta</a> · <a href="/">Inicio</a></p>
</main>
</html>
