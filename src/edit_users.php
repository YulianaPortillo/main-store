<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

include '../settings/connection.php';

$id = $_GET['id'];
$sql = 'SELECT * FROM users WHERE id = $1';
$result = pg_query_params($conn, $sql, array($id));
$user = pg_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar usuario</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <main class="page">
        <section class="card card-wide">
            <h1>Editar usuario</h1>

            <form action="update_users.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

        <label for="firstname">Nombre</label><br>
        <input id="firstname" type="text" name="firstname" value="<?php echo $user['firstname']; ?>" required><br><br>

        <label for="lastname">Apellido</label><br>
        <input id="lastname" type="text" name="lastname" value="<?php echo $user['lastname']; ?>" required><br><br>

        <label for="email">Correo</label><br>
        <input id="email" type="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>

        <label for="mobile_phone">Celular</label><br>
        <input id="mobile_phone" type="text" name="mobile_phone" value="<?php echo $user['mobile_phone']; ?>" required><br><br>

        <?php if ($user['photo_url'] != "") { ?>
            <p>Foto actual:</p>
            <img class="photo" src="<?php echo $user['photo_url']; ?>" alt="Foto actual"><br><br>
        <?php } ?>

        <label for="photo">Foto (JPG o PNG)</label><br>
        <input id="photo" type="file" name="photo" accept=".jpg,.jpeg,.png"><br><br>

                <button type="submit">Guardar</button>
                <a class="button" href="list_users.php">Cancelar</a>
            </form>
        </section>
    </main>
</body>
</html>